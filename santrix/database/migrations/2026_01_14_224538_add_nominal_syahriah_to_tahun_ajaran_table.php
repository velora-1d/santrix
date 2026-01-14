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
        Schema::table('tahun_ajaran', function (Blueprint $table) {
            // Default 0 for existing records
            $table->decimal('nominal_syahriah', 15, 2)->default(0)->after('semester');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tahun_ajaran', function (Blueprint $table) {
            $table->dropColumn('nominal_syahriah');
        });
    }
};
