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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'Standard Monthly' or 'Premium Annual'
            $table->decimal('price', 8, 2); // e.g., 29.99
            $table->enum('billing_period', ['monthly', 'yearly']); // Restricts to only these two options
            $table->text('features')->nullable(); // Optional field to store what the plan offers
            $table->boolean('is_active')->default(true); // Easily turn plans on or off
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};