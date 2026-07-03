<?php

use App\Http\Controllers\ShopRegistrationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\MerchantLoginController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MerchantSellerController;
use App\Http\Controllers\SellerLoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController; // <--- IMPORTED HERE

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Front-Facing SaaS Landing Page Route
Route::get('/', [HomeController::class, 'index']);

// Admin Authentication Routes
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);

// Admin Dashboard Protected Routes
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminAuthController::class, 'dashboard']);
    Route::get('/admin/packages', [AdminAuthController::class, 'packages']);
    Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    
    // Core Shop Approval and Listing Routes linked directly to AdminAuthController
    Route::get('/admin/shops', [AdminAuthController::class, 'manageShops'])->name('admin.shops.index');
    Route::post('/admin/shops/{id}/toggle-status', [AdminAuthController::class, 'toggleShopStatus'])->name('admin.shops.toggle');
});

// Shop Registration Pathways
Route::get('/register/{package_id}', [ShopRegistrationController::class, 'showRegistrationForm'])->name('shop.register');
Route::post('/register', [ShopRegistrationController::class, 'register'])->name('shop.register.submit');


// Shop Owner Login Routes
Route::get('/login', [MerchantLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [MerchantLoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [MerchantLoginController::class, 'logout'])->name('logout');

// Protected Merchant Dashboard Routes
Route::middleware(['auth:shop'])->group(function () {
    Route::get('/dashboard', function () {
        $shop = Auth::user();

        // HYBRID SECURITY CHECK:
        // If the shop is currently active, but the master admin's set expiration date has passed...
        if ($shop->is_active && $shop->expires_at && now()->greaterThanOrEqualTo($shop->expires_at)) {
            // Automatically reset status flags directly in the database record
            $shop->update([
                'is_active' => false,
                'activated_at' => null,
                'expires_at' => null
            ]);
        }

        return view('merchant.dashboard');
    })->name('merchant.dashboard');

    // --- TOOL 1: Shop Seller Management Module Routes ---
    Route::get('/dashboard/sellers', [MerchantSellerController::class, 'index'])->name('merchant.sellers.index');
    Route::post('/dashboard/sellers', [MerchantSellerController::class, 'store'])->name('merchant.sellers.store');
    Route::post('/dashboard/sellers/{id}/toggle', [MerchantSellerController::class, 'toggleStatus'])->name('merchant.sellers.toggle');
    Route::post('/dashboard/sellers/{id}/reset-password', [MerchantSellerController::class, 'resetPassword'])->name('merchant.sellers.reset');

    // --- TOOL 2: Product & Inventory Management Module Routes (OWNER MANAGE ROUTE BLOCK) ---
    Route::get('/merchant/products', [ProductController::class, 'index'])->name('merchant.products.index');
    Route::get('/merchant/products/create', [ProductController::class, 'create'])->name('merchant.products.create');
    Route::post('/merchant/products/store', [ProductController::class, 'store'])->name('merchant.products.store');
    Route::get('/merchant/products/{id}/edit', [ProductController::class, 'edit'])->name('merchant.products.edit');
    Route::put('/merchant/products/{id}/update', [ProductController::class, 'update'])->name('merchant.products.update');
    Route::delete('/merchant/products/{id}/delete', [ProductController::class, 'destroy'])->name('merchant.products.destroy');

    // --- REPORTING MANAGEMENT: OWNER ACCESS POINT ---
    Route::get('/dashboard/reports', [ReportController::class, 'index'])->name('merchant.reports.index');
});

// Shop Seller Login Routes
Route::get('/seller/login', [SellerLoginController::class, 'showLoginForm'])->name('seller.login');
Route::post('/seller/login', [SellerLoginController::class, 'login'])->name('seller.login.submit');
Route::post('/seller/logout', [MerchantLoginController::class, 'logout'])->name('seller.logout');

// Protected Seller Workspace Route
Route::middleware(['auth.seller'])->group(function () {
    Route::get('/seller/dashboard', function () {
        $seller = Auth::guard('seller')->user();
        
        // Fetch the real shop relationship data connected to this seller
        $shop = $seller->shop; 

        return view('seller.dashboard', compact('seller', 'shop'));
    })->name('seller.dashboard');

    // --- TOOL 2: Seller View-Only Product Catalog Route ---
    Route::get('/seller/products', [ProductController::class, 'index'])->name('seller.products.index');

    // --- REPORTING MANAGEMENT: SELLER ACCESS POINT ---
    Route::get('/seller/reports', [ReportController::class, 'index'])->name('seller.reports.index');
});

// --- HYBRID SHARED POS TERMINAL ROUTES ---
// This middleware block allows BOTH active owners (shop) and sellers to access the terminal 
Route::middleware(['auth:shop,seller'])->group(function () {
    Route::get('/seller/sales', [SaleController::class, 'index'])->name('seller.sales.index');
    Route::post('/seller/sales/store', [SaleController::class, 'store'])->name('seller.sales.store');
    
    // Multi-Day Return System Interface Endpoints
    Route::get('/seller/returns/search', [SaleController::class, 'searchReceipt'])->name('seller.sales.search');
    Route::post('/seller/returns/process', [SaleController::class, 'processReturn'])->name('seller.sales.process_return');
});