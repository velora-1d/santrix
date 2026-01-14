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
        Schema::table('jurnal_kbm', function (Blueprint $table) {
            $table->string('foto_awal')->nullable()->after('status');
            $table->string('foto_akhir')->nullable()->after('foto_awal');
            $table->timestamp('jam_mulai')->nullable()->after('foto_akhir');
            $table->timestamp('jam_selesai')->nullable()->after('jam_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jurnal_kbm', function (Blueprint $table) {
            $table->dropColumn(['foto_awal', 'foto_akhir', 'jam_mulai', 'jam_selesai']);
        });
    }
};
