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
        $tables = [
            'absensi_santri',
            'activity_log', // Log perlu diisolasi juga
            'asrama',
            'gaji_pegawai',
            'ijazah_settings',
            'jadwal_pelajaran',
            'kalender_pendidikan',
            'kelas',
            'kitab_talarans',
            'kobong',
            // 'mata_pelajaran', // Perlu cek manual karena ada relasi
            'mutasi_santri',
            'nilai_santri',
            // 'notifications', // Laravel default, usually not needed or handled differently
            'pegawai',
            'pemasukan',
            'pengeluaran',
            'report_settings',
            // 'santri', // SUDAH
            'syahriah',
            'tahun_ajaran',
            // 'talaran', // Cek nama tabel di model Talaran.php
            'talaran_mingguan',
            'ujian_mingguan',
        ];
        
        // Manual addition for specific tables that might exist
        $tables = array_filter($tables, function($table) {
            return Schema::hasTable($table);
        });

        foreach ($tables as $tableName) {
            if (!Schema::hasColumn($tableName, 'pesantren_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $table->foreignId('pesantren_id')->nullable()->after('id')->constrained('pesantrens')->cascadeOnDelete();
                    $table->index('pesantren_id');
                });
            }
        }

        // Mata Pelajaran (Check singular/plural)
        if (Schema::hasTable('mata_pelajaran') && !Schema::hasColumn('mata_pelajaran', 'pesantren_id')) {
             Schema::table('mata_pelajaran', function (Blueprint $table) {
                $table->foreignId('pesantren_id')->nullable()->after('id')->constrained('pesantrens')->cascadeOnDelete();
                 $table->index('pesantren_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         $tables = [
            'absensi_santri', 'activity_log', 'asrama', 'gaji_pegawai', 'ijazah_settings',
            'jadwal_pelajaran', 'kalender_pendidikan', 'kelas', 'kitab_talarans', 'kobong',
            'mata_pelajaran', 'mutasi_santri', 'nilai_santri', 'pegawai', 'pemasukan',
            'pengeluaran', 'report_settings', 'syahriah', 'tahun_ajaran', 'talaran_mingguan', 'ujian_mingguan'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'pesantren_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropForeign(['pesantren_id']);
                    $table->dropColumn('pesantren_id');
                });
            }
        }
    }
};
