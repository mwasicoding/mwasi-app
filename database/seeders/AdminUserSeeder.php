<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Check if the admin already exists to prevent duplication
        if (!Admin::where('username', 'admin_master')->exists()) {
            Admin::create([
                'username' => 'admin_master',
                'password' => Hash::make('MwasiSecure2026!'), // This safely hashes your real password
            ]);
        }
    }
}