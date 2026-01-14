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
        Schema::table('payment_gateway', function (Blueprint $table) {
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('payment_gateway', 'processed_at')) {
                $table->timestamp('processed_at')->nullable()->after('json_response');
            }
        });
        
        // Check if unique index doesn't exist before adding (using raw SQL)
        $indexExists = DB::select("SHOW INDEX FROM payment_gateway WHERE Key_name = 'payment_gateway_order_id_unique'");
        
        if (empty($indexExists)) {
            Schema::table('payment_gateway', function (Blueprint $table) {
                $table->unique('order_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if unique index exists before dropping
        $indexExists = DB::select("SHOW INDEX FROM payment_gateway WHERE Key_name = 'payment_gateway_order_id_unique'");
        
        if (!empty($indexExists)) {
            Schema::table('payment_gateway', function (Blueprint $table) {
                $table->dropUnique(['order_id']);
            });
        }
        
        Schema::table('payment_gateway', function (Blueprint $table) {
            if (Schema::hasColumn('payment_gateway', 'processed_at')) {
                $table->dropColumn('processed_at');
            }
        });
    }
};
