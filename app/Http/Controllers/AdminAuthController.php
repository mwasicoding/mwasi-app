<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Package; 
use App\Models\Shop;    

class AdminAuthController extends Controller
{
    // Show the custom admin login form
    public function showLogin()
    {
        if (Auth::guard('admin')->check()) {
            return redirect('/admin/dashboard');
        }
        return view('admin.login');
    }

    // Handle admin login logic
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }

    // Pass real database metrics to the dashboard view
   // Pass real database metrics to the dashboard view
    // Pass real database metrics to the dashboard view
    public function dashboard()
    {
        $totalPackages = Package::count();
        
        // Only count shops that have been manually approved (is_active = 1)
        $activeShops = Shop::where('is_active', true)->count(); 

        // 1. COMPUTE ACTUAL SYSTEM REVENUE FROM ACTIVE SUBSCRIPTIONS
        // Added 'shops.' prefix to clear ambiguity for MySQL execution
        $totalSystemRevenue = Shop::where('shops.is_active', true)
            ->join('packages', 'shops.package_id', '=', 'packages.id')
            ->sum('packages.price');

        // 2. FETCH THE 5 MOST RECENT REGISTERED SHOPS
        $recentShops = Shop::latest()->take(5)->get();

        return view('admin.dashboard', compact('totalPackages', 'activeShops', 'totalSystemRevenue', 'recentShops'));
    }
    // Display the list of active SaaS packages
    public function packages()
    {
        $packages = Package::all();
        return view('admin.packages', compact('packages'));
    }

    // Log the admin out securely
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }

    // Fetch and show all registered shops
    public function manageShops()
    {
        // Using the imported Shop model directly for cleaner execution
        $shops = Shop::with('package')->orderBy('created_at', 'desc')->get();
        return view('admin.shops', compact('shops'));
    }

    // Process the manual approval toggle with custom tracking timelines
    public function toggleShopStatus(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);
        
        // Flip the current status flag
        if (!$shop->is_active) {
            // We are APPROVING the store, let's grab the manual calendar values
            $request->validate([
                'activated_at' => 'required|date',
                'expires_at'   => 'required|date|after_or_equal:activated_at',
            ]);

            $shop->is_active = true;
            $shop->activated_at = $request->input('activated_at');
            $shop->expires_at = $request->input('expires_at');
            
            $message = "Store '{$shop->name}' has been manually verified, approved, and activated until {$shop->expires_at->format('Y-m-d')}!";
        } else {
            // We are SUSPENDING the store, clear out dates completely
            $shop->is_active = false;
            $shop->activated_at = null;
            $shop->expires_at = null;
            
            $message = "Store '{$shop->name}' has been suspended and timelines cleared.";
        }

        $shop->save();

        return redirect()->back()->with('success', $message);
    }
}