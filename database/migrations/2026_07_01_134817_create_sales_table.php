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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            
            // Multi-tenant tracking fields
            $table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');

            // Item classification & details
            $table->string('item_type'); // Will hold either 'product' or 'service'
            $table->string('item_name'); // Will hold names like 'Micro USB' or 'Phone Screen Repair'
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null'); // Blank for services

            // Financial tracking fields
            $table->decimal('price_charged', 12, 2); // Custom editable counter price
            $table->integer('quantity')->default(1);
            $table->decimal('total_revenue', 12, 2); // price_charged multiplied by quantity

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};