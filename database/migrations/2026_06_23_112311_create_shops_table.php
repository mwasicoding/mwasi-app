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
        Schema::create('shops', function (Blueprint $table) {
            $table->id(); // This acts as our unique shop_id for total data isolation
            $table->string('name'); // Name of the retail shop
            $table->string('slug')->unique(); // Unique URL identifier (e.g., 'shop-a')
            
            // RBAC & Subscription Linking
            $table->foreignId('package_id')->constrained('packages')->onDelete('restrict');
            
            // Shop Owner Login Credentials
            $table->string('owner_name');
            $table->string('email')->unique();
            $table->string('password');
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};