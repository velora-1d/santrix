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
        // Pemasukan
        Schema::table('pemasukan', function (Blueprint $table) {
            $table->foreignId('tahun_ajaran_id')->nullable()->after('pesantren_id');
        });

        // Pengeluaran
        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->foreignId('tahun_ajaran_id')->nullable()->after('pesantren_id');
        });

        // Syahriah
        Schema::table('syahriah', function (Blueprint $table) {
            $table->foreignId('tahun_ajaran_id')->nullable()->after('pesantren_id');
        });

        // Gaji Pegawai
        // Note: Check if gaji_pegawai exists first, just in case
        if (Schema::hasTable('gaji_pegawai')) {
            Schema::table('gaji_pegawai', function (Blueprint $table) {
               // Assuming it has relations, add nullable first
                $table->foreignId('tahun_ajaran_id')->nullable()->after('id'); 
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemasukan', function (Blueprint $table) {
            $table->dropColumn('tahun_ajaran_id');
        });

        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->dropColumn('tahun_ajaran_id');
        });

        Schema::table('syahriah', function (Blueprint $table) {
            $table->dropColumn('tahun_ajaran_id');
        });

        if (Schema::hasTable('gaji_pegawai')) {
             Schema::table('gaji_pegawai', function (Blueprint $table) {
                $table->dropColumn('tahun_ajaran_id');
            });
        }
    }
};
