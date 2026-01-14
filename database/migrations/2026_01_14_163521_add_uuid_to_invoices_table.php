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
        Schema::table('invoices', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable();
        });

        // Backfill existing records
        DB::table('invoices')->orderBy('id')->chunk(100, function ($invoices) {
            foreach ($invoices as $invoice) {
                DB::table('invoices')
                    ->where('id', $invoice->id)
                    ->update(['uuid' => \Illuminate\Support\Str::uuid()]);
            }
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->change();
            $table->unique('uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
