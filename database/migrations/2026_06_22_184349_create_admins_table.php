<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $col) {
            $col->id();
            $col->string('username')->unique();
            $col->string('password');
            $col->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};