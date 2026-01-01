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
        Schema::table('payment_gateway', function (Blueprint $table) {
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('payment_gateway', 'processed_at')) {
                $table->timestamp('processed_at')->nullable()->after('json_response');
            }
            
            // Check if unique index doesn't exist before adding
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $sm->listTableIndexes('payment_gateway');
            
            if (!isset($indexesFound['payment_gateway_order_id_unique'])) {
                $table->unique('order_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_gateway', function (Blueprint $table) {
            if (Schema::hasColumn('payment_gateway', 'processed_at')) {
                $table->dropColumn('processed_at');
            }
            
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $sm->listTableIndexes('payment_gateway');
            
            if (isset($indexesFound['payment_gateway_order_id_unique'])) {
                $table->dropUnique(['order_id']);
            }
        });
    }
};
