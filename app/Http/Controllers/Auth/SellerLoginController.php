<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerLoginController extends Controller
{
    // 1. Render the dedicated login page for shop attendants
    public function showLoginForm()
    {
        return view('auth.seller-login');
    }

    // 2. Process the incoming authentication request
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt to log in using our custom 'seller' guard configuration
        if (Auth::guard('seller')->attempt($credentials, $request->remember)) {
            
            // Check if the shop owner has suspended this seller account
            if (!Auth::guard('seller')->user()->is_active) {
                Auth::guard('seller')->logout();
                return redirect()->back()->withErrors([
                    'username' => 'Your seller account has been suspended by the shop owner.',
                ]);
            }

            // Authentication passed! Send them to their workspace
            $request->session()->regenerate();
            return redirect()->intended(route('seller.dashboard'));
        }

        // Authentication failed
        return redirect()->back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }

    // 3. Process the logout request
    public function logout(Request $request)
    {
        Auth::guard('seller')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/seller/login');
    }
}