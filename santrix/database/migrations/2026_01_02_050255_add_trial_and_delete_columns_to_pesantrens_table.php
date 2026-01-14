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
        Schema::table('pesantrens', function (Blueprint $table) {
            $table->timestamp('trial_ends_at')->nullable()->after('expired_at');
            $table->timestamp('delete_at')->nullable()->after('trial_ends_at')->comment('Auto-delete account if unpaid after grace period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesantrens', function (Blueprint $table) {
            $table->dropColumn(['trial_ends_at', 'delete_at']);
        });
    }
};
