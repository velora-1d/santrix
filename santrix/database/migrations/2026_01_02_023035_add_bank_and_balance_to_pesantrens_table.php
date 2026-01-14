<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pesantrens', function (Blueprint $table) {
            // Add 'trial' to enum by raw statement if needed, or just nullable string for now if easier.
            // Since altering enum is driver specific, let's use valid approach or just string.
            // Using DB::statement for generic MySQL/MariaDB.
            DB::statement("ALTER TABLE pesantrens MODIFY COLUMN package ENUM('basic', 'advance', 'trial') DEFAULT 'trial'");

            // Bank Details
            $table->string('bank_name')->nullable()->after('expired_at');
            $table->string('account_number')->nullable()->after('bank_name');
            $table->string('account_name')->nullable()->after('account_number');
            
            // Payment Gateway Balance (Dompet Pesantren)
            $table->decimal('saldo_pg', 15, 2)->default(0)->after('account_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesantrens', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'account_number', 'account_name', 'saldo_pg']);
            // Revert enum usually tricky, let's keep it simple
        });
    }
};
