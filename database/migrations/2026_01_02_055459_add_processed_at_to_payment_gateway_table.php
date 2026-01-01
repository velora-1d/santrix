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
            $table->timestamp('processed_at')->nullable()->after('json_response');
            
            // SECURITY: Prevent duplicate order_id (idempotency)
            $table->unique('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_gateway', function (Blueprint $table) {
            $table->dropUnique(['order_id']);
            $table->dropColumn('processed_at');
        });
    }
};
