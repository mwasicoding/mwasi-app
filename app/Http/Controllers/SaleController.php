<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display the real-time POS terminal screen for both Owners and Sellers.
     */
    public function index()
    {
        // Hybrid Authorization Guard Check: Safely fetch the shop_id based on who is logged in
        if (Auth::guard('seller')->check()) {
            $shopId = Auth::guard('seller')->user()->shop_id;
        } elseif (Auth::guard('shop')->check()) {
            $shopId = Auth::guard('shop')->id(); // The authenticated shop profile ID
        } else {
            return redirect()->route('login')->with('error', 'Please log in to access the terminal.');
        }

        $products = Product::where('shop_id', $shopId)
            ->where('stock_quantity', '>', 0)
            ->get();

        $sales = Sale::where('shop_id', $shopId)
            ->whereDate('created_at', today())
            ->latest()
            ->get();

        // Pass down user info back to header variables to prevent layout crashes
        $seller = Auth::guard('seller')->user() ?? Auth::guard('shop')->user();

        return view('seller.sales.index', compact('products', 'sales', 'seller'));
    }

    /**
     * Store a newly created sale in storage via AJAX.
     */
    public function store(Request $request)
    {
        // Hybrid authorization capture for the transaction data
        if (Auth::guard('seller')->check()) {
            $seller = Auth::guard('seller')->user();
            $shopId = $seller->shop_id;
            $sellerId = $seller->id; 
        } elseif (Auth::guard('shop')->check()) {
            $shopId = Auth::guard('shop')->id();
            $sellerId = null; // Shop owners don't have a record in the sellers table
        } else {
            return response()->json(['success' => false, 'message' => 'Unauthorized operation.'], 401);
        }

        // 1. Core Validation Rules (UPDATED TO VALIDATE PAYMENT CHOICE)
        $request->validate([
            'item_type'      => 'required|in:product,service',
            'price_charged'  => 'required|numeric|min:0',
            'quantity'       => 'required|integer|min:1',
            'product_id'     => 'required_if:item_type,product|nullable|exists:products,id',
            'service_name'   => 'required_if:item_type,service|nullable|string|max:255',
            'payment_method' => 'nullable|in:cash,lipa_number', // Added validation rule
        ]);

        // 2. Use a Database Transaction to ensure data integrity
        return DB::transaction(function () use ($request, $shopId, $sellerId) {
            $qty = $request->quantity;
            $price = $request->price_charged;
            $totalRevenue = $price * $qty;
            $itemName = '';

            if ($request->item_type === 'product') {
                // Fetch product and lock the row to prevent race conditions
                $product = Product::where('shop_id', $shopId)
                    ->lockForUpdate()
                    ->findOrFail($request->product_id);

                // Verify stock availability
                if ($product->stock_quantity < $qty) {
                    return response()->json([
                        'success' => false, 
                        'message' => "Incomplete! Only {$product->stock_quantity} units left in stock."
                    ], 422);
                }

                // Deduct the inventory quantity balance
                $product->decrement('stock_quantity', $qty);
                $itemName = $product->name . ' [' . ($product->brand ?? 'Generic') . ']';
            } else {
                // For custom manual services, use the typed description text
                $itemName = $request->service_name;
            }

            // Fallback default choice if frontend field passes empty metrics
            $paymentMethod = $request->get('payment_method', 'cash');

            // 3. Save the record while temporarily ignoring the users table foreign check constraint
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            $sale = Sale::create([
                'shop_id'        => $shopId,
                'seller_id'      => $sellerId,
                'product_id'     => $request->item_type === 'product' ? $request->product_id : null,
                'item_type'      => $request->item_type,
                'item_name'      => $itemName,
                'price_charged'  => $price,
                'quantity'       => $qty,
                'total_revenue'  => $totalRevenue,
                'payment_method' => $paymentMethod, // Saved choice token safely here
            ]);
            
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // 4. Return the brand new transaction row details straight to our front-end interface
            return response()->json([
                'success'        => true,
                'message'        => 'Transaction logged successfully!',
                'time'           => $sale->created_at->format('H:i:s'),
                'type'           => $sale->item_type,
                'item_name'      => $sale->item_name,
                'payment_method' => $sale->payment_method, // Return choice token back to AJAX handler string string
                'price_charged'  => number_format($sale->price_charged) . ' TZS',
                'quantity'       => $sale->quantity,
                'total_revenue'  => number_format($sale->total_revenue) . ' TZS',
                // Send running summary updates back too
                'prod_total'     => number_format(Sale::where('shop_id', $shopId)->whereDate('created_at', today())->where('item_type', 'product')->sum('total_revenue')) . ' TZS',
                'serv_total'     => number_format(Sale::where('shop_id', $shopId)->whereDate('created_at', today())->where('item_type', 'service')->sum('total_revenue')) . ' TZS',
                'grand_total'    => number_format(Sale::where('shop_id', $shopId)->whereDate('created_at', today())->sum('total_revenue')) . ' TZS',
            ]);
        });
    }

    /**
     * Search and list all old transactions matching a chosen calendar date.
     */
    public function searchReceipt(Request $request)
    {
        // Hybrid Authorization Guard Capture
        $shopId = Auth::guard('seller')->check() ? Auth::guard('seller')->user()->shop_id : Auth::guard('shop')->id();
        if (!$shopId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized profile session.'], 401);
        }

        // Get the chosen date from the input (Format: Y-m-d)
        $targetDate = $request->get('query');
        
        if (!$targetDate) {
            return response()->json(['success' => false, 'message' => 'Please select a valid calendar date.']);
        }

        // Find all historical primary sales belonging to this shop for that specific calendar day
        $sales = Sale::where('shop_id', $shopId)
            ->whereDate('created_at', $targetDate)
            ->where('transaction_type', 'sale')
            ->get();

        if ($sales->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No transaction records found for ' . $targetDate]);
        }

        // Map line items out for the interactive layout modal stack
        $items = $sales->map(function ($item) {
            // Check if this specific row item was already returned historically
            $alreadyReturned = Sale::where('original_sale_id', $item->id)->where('transaction_type', 'return')->exists();
            
            return [
                'id'             => $item->id,
                'item_name'      => $item->item_name,
                'payment_method' => $item->payment_method, // Added to provide visual lookup metrics on modal lookup elements
                'price'          => $item->price_charged,
                'quantity'       => $item->quantity,
                'total'          => $item->total_revenue,
                'date'           => $item->created_at->format('H:i'), // Show time so they can distinguish separate orders
                'is_refundable'  => !$alreadyReturned
            ];
        });

        return response()->json(['success' => true, 'items' => $items]);
    }

    /**
     * Issue a multi-day return adjustment row for today's log stack.
     */
    public function processReturn(Request $request)
    {
        // Hybrid Authorization Guard Capture
        if (Auth::guard('seller')->check()) {
            $seller = Auth::guard('seller')->user();
            $shopId = $seller->shop_id;
            $sellerId = $seller->id;
        } elseif (Auth::guard('shop')->check()) {
            $shopId = Auth::guard('shop')->id();
            $sellerId = null;
        } else {
            return response()->json(['success' => false, 'message' => 'Session identification expired.'], 401);
        }

        $request->validate([
            'sale_id'   => 'required|exists:sales,id',
            'condition' => 'required|in:resalable,damaged'
        ]);

        return DB::transaction(function () use ($request, $shopId, $sellerId) {
            // Fetch the historical sale targeting this exact shop store footprint
            $originalSale = Sale::where('shop_id', $shopId)->findOrFail($request->sale_id);

            // Double check protection layers
            $alreadyReturned = Sale::where('original_sale_id', $originalSale->id)->where('transaction_type', 'return')->exists();
            if ($alreadyReturned) {
                return response()->json(['success' => false, 'message' => 'Action locked. This line item was already refunded.'], 422);
            }

            // 1. Restock Inventory if it is a physical product and marked resalable
            if ($originalSale->item_type === 'product' && $originalSale->product_id && $request->condition === 'resalable') {
                $product = Product::where('shop_id', $shopId)->find($originalSale->product_id);
                if ($product) {
                    $product->increment('stock_quantity', $originalSale->quantity);
                }
            }

            // 2. Log a BRAND NEW adjustment entry record stamped with today's date
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $returnRow = Sale::create([
                'receipt_number'   => $originalSale->receipt_number,
                'shop_id'          => $shopId,
                'seller_id'        => $sellerId,
                'product_id'       => $originalSale->product_id,
                'original_sale_id' => $originalSale->id,
                'item_type'        => $originalSale->item_type,
                'transaction_type' => 'return', // Flagged as a return modification entry
                'item_name'        => '[RETURNED] ' . $originalSale->item_name . ($request->condition === 'damaged' ? ' (Damaged)' : ''),
                'price_charged'    => $originalSale->price_charged,
                'quantity'         => $originalSale->quantity,
                // Negative value tracking drops today's financial metrics natively without warping old days
                'total_revenue'    => -($originalSale->total_revenue), 
                'payment_method'   => $originalSale->payment_method, // Refunds are marked against the original checkout method channels
            ]);
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return response()->json([
                'success'     => true,
                'message'     => 'Return adjustment completed successfully!',
                'grand_total' => number_format(Sale::where('shop_id', $shopId)->whereDate('created_at', today())->sum('total_revenue')) . ' TZS'
            ]);
        });
    }
}