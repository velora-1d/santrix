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
        // Absensi Santri
        if (Schema::hasTable('absensi_santri')) {
            Schema::table('absensi_santri', function (Blueprint $table) {
                if (!Schema::hasColumn('absensi_santri', 'tahun_ajaran_id')) {
                    $table->foreignId('tahun_ajaran_id')->nullable()->after('pesantren_id');
                }
            });
        }

        // Absensi Guru
        if (Schema::hasTable('absensi_guru')) {
            Schema::table('absensi_guru', function (Blueprint $table) {
                if (!Schema::hasColumn('absensi_guru', 'tahun_ajaran_id')) {
                    $table->foreignId('tahun_ajaran_id')->nullable()->after('pesantren_id');
                }
            });
        }

        // Jurnal KBM
        if (Schema::hasTable('jurnal_kbm')) {
            Schema::table('jurnal_kbm', function (Blueprint $table) {
                 if (!Schema::hasColumn('jurnal_kbm', 'tahun_ajaran_id')) {
                    $table->foreignId('tahun_ajaran_id')->nullable()->after('pesantren_id');
                }
            });
        }
        
        // Jadwal Pelajaran
        if (Schema::hasTable('jadwal_pelajaran')) {
            Schema::table('jadwal_pelajaran', function (Blueprint $table) {
                 if (!Schema::hasColumn('jadwal_pelajaran', 'tahun_ajaran_id')) {
                    $table->foreignId('tahun_ajaran_id')->nullable()->after('pesantren_id');
                }
            });
        }

        // Nilai Santri
        if (Schema::hasTable('nilai_santri')) {
            Schema::table('nilai_santri', function (Blueprint $table) {
                 if (!Schema::hasColumn('nilai_santri', 'tahun_ajaran_id')) {
                    $table->foreignId('tahun_ajaran_id')->nullable()->after('pesantren_id');
                }
            });
        }

        // Ujian Mingguan (ex-Talaran Mingguan)
        if (Schema::hasTable('ujian_mingguan')) {
            Schema::table('ujian_mingguan', function (Blueprint $table) {
                 if (!Schema::hasColumn('ujian_mingguan', 'tahun_ajaran_id')) {
                    $table->foreignId('tahun_ajaran_id')->nullable()->after('pesantren_id');
                }
            });
        }

        // Talaran Santri (Progress hafalan per santri)
        if (Schema::hasTable('talaran_santri')) {
            Schema::table('talaran_santri', function (Blueprint $table) {
                 if (!Schema::hasColumn('talaran_santri', 'tahun_ajaran_id')) {
                    $table->foreignId('tahun_ajaran_id')->nullable()->after('pesantren_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'absensi_santri', 
            'absensi_guru', 
            'jurnal_kbm', 
            'jadwal_pelajaran', 
            'nilai_santri', 
            'ujian_mingguan', 
            'talaran_santri'
        ];

        foreach ($tables as $t) {
            if (Schema::hasTable($t) && Schema::hasColumn($t, 'tahun_ajaran_id')) {
                Schema::table($t, function (Blueprint $table) {
                    $table->dropColumn('tahun_ajaran_id');
                });
            }
        }
    }
};
