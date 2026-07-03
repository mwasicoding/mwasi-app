<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Shop;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ShopRegistrationController extends Controller
{
    // Show the registration form with the pre-selected package details
    public function showRegistrationForm($package_id)
    {
        $package = Package::findOrFail($package_id);
        return view('shop.register', compact('package'));
    }

    // Process the merchant registration securely
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:shops,name',
            'owner_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:shops,email',
            'password' => 'required|string|min:8|confirmed',
            'package_id' => 'required|exists:packages,id',
        ]);

        // Automatically generate a unique URL slug from the shop name
        $slug = Str::slug($request->name);

        // Create the new client shop database record
        Shop::create([
            'name' => $request->name,
            'slug' => $slug,
            'package_id' => $request->package_id,
            'owner_name' => $request->owner_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => true, // Active immediately upon setup
        ]);

        // Redirect back to the homepage with a clean success flash alert
        return redirect('/')->with('success', 'Your storefront has been successfully initialized! Welcome to the network.');
    }
}