<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MerchantLoginController extends Controller
{
    // 1. Show the clean login page
    public function showLoginForm()
    {
        // If a Shop Owner is already logged in, send them to their dashboard
        if (Auth::guard('shop')->check()) {
            return redirect()->route('merchant.dashboard');
        }

        // If a Shop Seller is already logged in, send them to their dashboard
        if (Auth::guard('seller')->check()) {
            return redirect()->route('seller.dashboard');
        }
        
        return view('auth.login');
    }

    // 2. Handle the unified login attempt (Detects Owner vs Seller automatically)
    public function login(Request $request)
    {
        // Validate inputs generically since it could be an email or a username string
        $request->validate([
            'login_input' => 'required|string',
            'password' => 'required|string',
        ], [
            'login_input.required' => 'Please type your business email or seller username.',
            'password.required' => 'Please enter your account password.',
        ]);

        $loginInput = $request->input('login_input');
        $password = $request->input('password');
        $remember = $request->has('remember');

        // CONDITION CHECK: If it contains an '@', treat it as a Shop Owner Email
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            
            $credentials = ['email' => $loginInput, 'password' => $password];

            if (Auth::guard('shop')->attempt($credentials, $remember)) {
                $request->session()->regenerate();
                return redirect()->intended(route('merchant.dashboard'))
                    ->with('success', 'Welcome back to your shop control center!');
            }

        } else {
            
            // OTHERWISE: Treat it as a Shop Seller Username
            $credentials = ['username' => $loginInput, 'password' => $password];

            if (Auth::guard('seller')->attempt($credentials, $remember)) {
                
                // Additional Gate: Check if the seller account is active
                if (!Auth::guard('seller')->user()->is_active) {
                    Auth::guard('seller')->logout();
                    return back()->withErrors([
                        'login_input' => 'Your seller account has been suspended by the shop owner.',
                    ]);
                }

                $request->session()->regenerate();
                return redirect()->intended(route('seller.dashboard'))
                    ->with('success', 'Logged in successfully to counter terminal.');
            }
        }

        // Generic error fallback for absolute privacy protection
        return back()->withErrors([
            'login_input' => 'The credentials you provided do not match our records.',
        ])->withInput($request->only('login_input'));
    }

    // 3. Handle logging the user out safely depending on which guard they are using
    public function logout(Request $request)
    {
        if (Auth::guard('shop')->check()) {
            Auth::guard('shop')->logout();
        } elseif (Auth::guard('seller')->check()) {
            Auth::guard('seller')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out successfully.');
    }
}