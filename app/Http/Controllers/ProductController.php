<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        // If logged in as a shop owner (merchant)
        if (Auth::guard('shop')->check()) {
            $shop = Auth::guard('shop')->user();
            $products = Product::where('shop_id', $shop->id)->latest()->get();
            return view('merchant.products.index', compact('products', 'shop'));
        }

        // If logged in as a seller terminal account
        if (Auth::guard('seller')->check()) {
            $seller = Auth::guard('seller')->user();
            $shop = $seller->shop; // Relationship from Seller model
            $products = Product::where('shop_id', $shop->id)->latest()->get();
            return view('seller.products.index', compact('products', 'shop'));
        }

        abort(403, 'Unauthorized action.');
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $shop = Auth::guard('shop')->user();
        return view('merchant.products.create', compact('shop'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $shop = Auth::guard('shop')->user();

        // Form fields data validation rules
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'wholesale_cost' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
        ]);

        // MAP form data 'wholesale_cost' explicitly to database column 'buying_price'
        $productData = [
            'shop_id'             => $shop->id,
            'name'                => $validated['name'],
            'brand'               => $validated['brand'],
            'buying_price'        => $validated['wholesale_cost'], 
            'selling_price'       => $validated['selling_price'],
            'stock_quantity'      => $validated['stock_quantity'],
            'low_stock_threshold' => $validated['low_stock_threshold'],
        ];

        // Save entry row into database using the mapped array parameters
        Product::create($productData);

        return redirect()->route('merchant.products.index')
            ->with('success', 'Product registered successfully into your catalog shelf.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit($id)
    {
        $shop = Auth::guard('shop')->user();
        
        // Find the product and verify ownership security context
        $product = Product::where('id', $id)->where('shop_id', $shop->id)->firstOrFail();

        return view('merchant.products.edit', compact('product', 'shop'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $id)
    {
        $shop = Auth::guard('shop')->user();
        
        // Find product record and enforce owner authorization security boundaries
        $product = Product::where('id', $id)->where('shop_id', $shop->id)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'wholesale_cost' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
        ]);

        // Remap updated user inputs back to the strict database structure schema layout
        $product->update([
            'name'                => $validated['name'],
            'brand'               => $validated['brand'],
            'buying_price'        => $validated['wholesale_cost'],
            'selling_price'       => $validated['selling_price'],
            'stock_quantity'      => $validated['stock_quantity'],
            'low_stock_threshold' => $validated['low_stock_threshold'],
        ]);

        return redirect()->route('merchant.products.index')
            ->with('success', 'Product details updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        $shop = Auth::guard('shop')->user();
        
        // Securely find item verification check before carrying out removal execution task
        $product = Product::where('id', $id)->where('shop_id', $shop->id)->firstOrFail();
        
        $product->delete();

        return redirect()->route('merchant.products.index')
            ->with('success', 'Product permanently removed from your catalog dashboard.');
    }
}