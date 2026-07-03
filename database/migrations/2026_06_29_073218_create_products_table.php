<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            
            // Multi-tenant key linking this product to its specific shop owner profile
            $table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');
            
            // Identity fields for your brand intelligence analytics
            $table->string('name');
            $table->string('brand')->nullable()->default('Generic');
            
            // Financial tracking metrics (Using decimal to ensure strict accurate math calculations)
            $table->decimal('buying_price', 12, 2);
            $table->decimal('selling_price', 12, 2);
            
            // Inventory stock balances & automated tracking limits
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};