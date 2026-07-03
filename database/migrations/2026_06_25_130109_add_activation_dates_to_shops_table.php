<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $blueprint) {
            $blueprint->timestamp('activated_at')->nullable()->after('is_active');
            $blueprint->timestamp('expires_at')->nullable()->after('activated_at');
        });
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['activated_at', 'expires_at']);
        });
    }
};