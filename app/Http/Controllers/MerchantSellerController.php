<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MerchantSellerController extends Controller
{
    // 1. List all sellers belonging to the logged-in merchant shop
    public function index()
    {
        $shop = Auth::user();
        
        // Strict Tenant Scoping: Fetch only workers assigned to this shop's ID
        $sellers = Seller::where('shop_id', $shop->id)
                         ->orderBy('created_at', 'desc')
                         ->get();

        return view('merchant.sellers.index', compact('sellers'));
    }

    // 2. Process and store a brand-new shop attendant
    public function store(Request $request)
    {
        $shop = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:sellers,username',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|string|min:4',
        ]);

        Seller::create([
            'shop_id'   => $shop->id,
            'name'      => $request->name,
            'username'  => $request->username,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', "New shop attendant '{$request->name}' registered successfully!");
    }

    // 3. Toggle employee account status (Suspend/Activate)
    public function toggleStatus($id)
    {
        $shop = Auth::user();
        
        // Ensure the merchant owns this seller account before touching it
        $seller = Seller::where('shop_id', $shop->id)->findOrFail($id);
        
        $seller->is_active = !$seller->is_active;
        $seller->save();

        $statusMessage = $seller->is_active ? 'activated' : 'suspended';
        return redirect()->back()->with('success', "Attendant status updated to {$statusMessage}.");
    }

    // 4. Force override password reset from the shop owner
    public function resetPassword(Request $request, $id)
    {
        $shop = Auth::user();
        
        // Ensure the merchant owns this seller account
        $seller = Seller::where('shop_id', $shop->id)->findOrFail($id);

        $request->validate([
            'password' => 'required|string|min:4',
        ]);

        $seller->password = Hash::make($request->password);
        $seller->save();

        return redirect()->back()->with('success', "Password for employee '{$seller->name}' has been reset successfully!");
    }
}