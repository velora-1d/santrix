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
        Schema::table('subscriptions', function (Blueprint $table) {
            // Change status to string/varchar to allow 'trial', 'active', etc. dynamically
            $table->string('status', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // We cannot easily revert to enum with data validation, but we can try
            // or just leave it as string. For strict revert:
            // $table->enum('status', ['active', 'expired', 'cancelled', 'pending'])->change();
        });
    }
};
