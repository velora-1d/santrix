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
        Schema::table('santri', function (Blueprint $table) {
            $table->foreignId('pesantren_id')->nullable()->after('id')->constrained('pesantrens')->cascadeOnDelete();
            $table->index('pesantren_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('santri', function (Blueprint $table) {
            $table->dropForeign(['pesantren_id']);
            $table->dropColumn('pesantren_id');
        });
    }
};
