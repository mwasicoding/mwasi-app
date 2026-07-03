<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package; // Import the Package model

class HomeController extends Controller
{
    // Display the public landing page with available SaaS tiers
    public function index()
    {
        $packages = Package::all();
        return view('welcome', compact('packages'));
    }
}