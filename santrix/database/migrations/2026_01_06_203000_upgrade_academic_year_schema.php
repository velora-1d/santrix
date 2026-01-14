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
        // 1. Update tahun_ajaran table
        Schema::table('tahun_ajaran', function (Blueprint $table) {
            if (!Schema::hasColumn('tahun_ajaran', 'pesantren_id')) {
                $table->foreignId('pesantren_id')->nullable()->after('id')->constrained('pesantrens')->onDelete('cascade');
            }
            if (!Schema::hasColumn('tahun_ajaran', 'tanggal_mulai')) {
                $table->date('tanggal_mulai')->nullable()->after('nama');
            }
            if (!Schema::hasColumn('tahun_ajaran', 'tanggal_selesai')) {
                $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
            }
            // We keep 'semester' as a global setting or part of the record? 
            // Better to have 'semester_aktif' column in tahun_ajaran? No, usually semester is toggleable.
            // Let's add 'semester' to the table if we want to define periods like "2024/2025 Ganjil".
            // But usually Tahun Ajaran is the parent. 
            // Let's stick to standard: Tahun Ajaran = "2024/2025". Semester is handled in code or simple setting.
        });

        // 2. Create riwayat_kelas table
        Schema::create('riwayat_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesantren_id')->constrained('pesantrens')->onDelete('cascade');
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
            $table->enum('semester', ['1', '2'])->nullable(); // Optional: if we want to track per semester
            $table->text('catatan')->nullable(); // e.g. "Naik Kelas", "Tinggal Kelas"
            $table->string('status')->default('promoted'); // promoted, retained, graduated
            $table->timestamps();

            $table->index(['santri_id', 'tahun_ajaran_id']);
        });

        // 3. Add tahun_ajaran_id to mutasi_santri
        Schema::table('mutasi_santri', function (Blueprint $table) {
            $table->foreignId('tahun_ajaran_id')->nullable()->after('santri_id')->constrained('tahun_ajaran')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mutasi_santri', function (Blueprint $table) {
            $table->dropForeign(['tahun_ajaran_id']);
            $table->dropColumn('tahun_ajaran_id');
        });

        Schema::dropIfExists('riwayat_kelas');

        Schema::table('tahun_ajaran', function (Blueprint $table) {
            if (Schema::hasColumn('tahun_ajaran', 'pesantren_id')) {
                $table->dropForeign(['pesantren_id']);
                $table->dropColumn(['pesantren_id', 'tanggal_mulai', 'tanggal_selesai']);
            }
        });
    }
};
