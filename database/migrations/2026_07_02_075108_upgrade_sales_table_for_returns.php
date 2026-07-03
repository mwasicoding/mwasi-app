<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Generates a searchable string invoice number (e.g., INV-2026-10023)
            $table->string('receipt_number')->nullable()->index()->after('id');
            // 'sale' or 'return' status type identification
            $table->string('transaction_type')->default('sale')->after('item_type');
            // Stores the parent sale ID if this line item is a multi-day return adjustment
            $table->unsignedBigInteger('original_sale_id')->nullable()->index()->after('product_id');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['receipt_number', 'transaction_type', 'original_sale_id']);
        });
    }
};