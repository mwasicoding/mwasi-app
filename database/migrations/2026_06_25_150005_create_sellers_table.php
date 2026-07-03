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
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            
            // Core Multi-Tenant Boundary Layer: Links the seller strictly to their employer shop
            $table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');
            
            // Attendant Profile Details
            $table->string('name');
            $table->string('username')->unique(); // Unique identifier for their login screen gateway
            $table->string('phone')->nullable();
            $table->string('password');
            
            // Management Flag: Allows shop owners to lock/unlock staff access at will
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};