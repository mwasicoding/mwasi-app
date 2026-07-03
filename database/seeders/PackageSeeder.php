<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        // Insert standard Monthly Plan
        if (!Package::where('billing_period', 'monthly')->exists()) {
            Package::create([
                'name' => 'Standard Monthly Plan',
                'price' => 29.99, // You can change this value to your exact choice later
                'billing_period' => 'monthly',
                'features' => 'Full store access, automated inventory, regular support',
                'is_active' => true,
            ]);
        }

        // Insert standard Yearly Plan
        if (!Package::where('billing_period', 'yearly')->exists()) {
            Package::create([
                'name' => 'Standard Yearly Plan',
                'price' => 299.99, // Discounted flat annual rate
                'billing_period' => 'yearly',
                'features' => 'Full store access, automated inventory, priority support, 2 months free',
                'is_active' => true,
            ]);
        }
    }
}