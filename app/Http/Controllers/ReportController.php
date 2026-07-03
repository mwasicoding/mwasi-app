<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display the aggregated reporting dashboard screen.
     */
    public function index(Request $request)
    {
        // 1. Hybrid Authorization Session Identification
        if (Auth::guard('seller')->check()) {
            $shopId = Auth::guard('seller')->user()->shop_id;
            $layoutExtend = 'layouts.seller'; 
        } elseif (Auth::guard('shop')->check()) {
            $shopId = Auth::guard('shop')->id();
            $layoutExtend = 'layouts.merchant';
        } else {
            return redirect()->route('login')->with('error', 'Please sign in to view reports.');
        }

        // 2. Setup Timeframe Filter Boundaries (Default to Today)
        $filter = $request->get('timeframe', 'today');
        $startDate = now()->startOfDay();
        $endDate = now()->endOfDay();

        if ($filter === 'yesterday') {
            $startDate = now()->subDay()->startOfDay();
            $endDate = now()->subDay()->endOfDay();
        } elseif ($filter === 'week') {
            $startDate = now()->startOfWeek();
            $endDate = now()->endOfWeek();
        } elseif ($filter === 'month') {
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
        } elseif ($filter === 'custom') {
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $startDate = Carbon::parse($request->get('start_date'))->startOfDay();
                $endDate = Carbon::parse($request->get('end_date'))->endOfDay();
            }
        }

        // 3. Base Query Filtered by Shop Footprint and Date Range Range
        $baseQuery = Sale::where('shop_id', $shopId)
            ->whereBetween('created_at', [$startDate, $endDate]);

        // 4. Extract Line Items Log Collections
        $productSalesLogs = (clone $baseQuery)->where('item_type', 'product')->latest()->get();
        $serviceSalesLogs = (clone $baseQuery)->where('item_type', 'service')->latest()->get();

        // 5. Compute TRACK A Metrics: PRODUCT MANAGEMENT FINANCIALS
        $productGrossSales   = (clone $baseQuery)->where('item_type', 'product')->where('total_revenue', '>', 0)->sum('total_revenue');
        
        $productTotalRefunds = abs((clone $baseQuery)->where('item_type', 'product')
            ->where(function($query) {
                $query->where('transaction_type', 'return')
                      ->orWhere('total_revenue', '<', 0);
            })->sum('total_revenue'));
            
        $productNetRevenue   = $productGrossSales - $productTotalRefunds;

        $productCashIncome   = (clone $baseQuery)->where('item_type', 'product')->where('payment_method', 'cash')->sum('total_revenue');
        $productLipaIncome   = (clone $baseQuery)->where('item_type', 'product')->where('payment_method', 'lipa_number')->sum('total_revenue');

        // 6. Compute TRACK B Metrics: SERVICE MANAGEMENT FINANCIALS
        $serviceGrossRevenue = (clone $baseQuery)->where('item_type', 'service')->sum('total_revenue');
        $serviceCashIncome   = (clone $baseQuery)->where('item_type', 'service')->where('payment_method', 'cash')->sum('total_revenue');
        $serviceLipaIncome   = (clone $baseQuery)->where('item_type', 'service')->where('payment_method', 'lipa_number')->sum('total_revenue');

        // NEW: Compute Top Performing Item Metrics
        $topProduct = (clone $baseQuery)->where('item_type', 'product')
            ->select('item_name', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('item_name')
            ->orderByDesc('total_qty')
            ->first();

        $topService = (clone $baseQuery)->where('item_type', 'service')
            ->select('item_name', DB::raw('SUM(quantity) as total_count'))
            ->groupBy('item_name')
            ->orderByDesc('total_count')
            ->first();

        // 7. Dynamic View Parameter Array Stack Packaging
        return view('merchant.reports', compact(
            'filter', 'startDate', 'endDate',
            'productSalesLogs', 'serviceSalesLogs',
            'productGrossSales', 'productTotalRefunds', 'productNetRevenue',
            'productCashIncome', 'productLipaIncome',
            'serviceGrossRevenue', 'serviceCashIncome', 'serviceLipaIncome',
            'topProduct', 'topService'
        ));
    }
}