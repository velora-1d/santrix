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
        // 1. Rename subscription_invoices to invoices
        if (Schema::hasTable('subscription_invoices') && !Schema::hasTable('invoices')) {
            Schema::rename('subscription_invoices', 'invoices');
        }

        // 2. Modify invoices table
        Schema::table('invoices', function (Blueprint $table) {
            // Check if columns exist before adding
            if (!Schema::hasColumn('invoices', 'pesantren_id')) {
                 $table->unsignedBigInteger('pesantren_id')->after('id')->nullable()->index();
            }
            if (!Schema::hasColumn('invoices', 'period_start')) {
                $table->date('period_start')->nullable()->after('amount');
            }
            if (!Schema::hasColumn('invoices', 'period_end')) {
                $table->date('period_end')->nullable()->after('period_start');
            }
            if (!Schema::hasColumn('invoices', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('invoices', 'paid_by_user_id')) {
                $table->foreignId('paid_by_user_id')->nullable()->after('paid_at')->constrained('users')->nullOnDelete();
            }
            
            // Ensure unique index on invoice_number
            $table->unique('invoice_number');
            // Ensure index on pesantren_id for scoping
             // (Already added above line but in case it existed before check)
        });

        // 3. Modify subscriptions table
        Schema::table('subscriptions', function (Blueprint $table) {
             // Rename column start_date to started_at
             if (Schema::hasColumn('subscriptions', 'start_date') && !Schema::hasColumn('subscriptions', 'started_at')) {
                 $table->renameColumn('start_date', 'started_at');
             }
             // Rename column end_date to expired_at
             if (Schema::hasColumn('subscriptions', 'end_date') && !Schema::hasColumn('subscriptions', 'expired_at')) {
                 $table->renameColumn('end_date', 'expired_at');
             }
             
             // Ensure index on pesantren_id
             if (!Schema::hasIndex('subscriptions', 'subscriptions_pesantren_id_index')) {
                 $table->index('pesantren_id');
             }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Revert subscriptions table
        Schema::table('subscriptions', function (Blueprint $table) {
             if (Schema::hasColumn('subscriptions', 'started_at')) {
                 $table->renameColumn('started_at', 'start_date');
             }
             if (Schema::hasColumn('subscriptions', 'expired_at')) {
                 $table->renameColumn('expired_at', 'end_date');
             }
             $table->dropIndex(['pesantren_id']);
        });

        // 2. Revert invoices table
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropUnique('invoices_invoice_number_unique');
            $table->dropColumn(['paid_at', 'paid_by_user_id', 'period_start', 'period_end']);
            // Note: pesantren_id might be needed so keeping it logic carefully if rollback
        });
        
        // 3. Rename back to subscription_invoices
        if (Schema::hasTable('invoices')) {
            Schema::rename('invoices', 'subscription_invoices');
        }
    }
};
