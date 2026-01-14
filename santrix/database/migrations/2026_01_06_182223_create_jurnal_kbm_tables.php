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
        // 1. Jurnal KBM (Main Record)
        Schema::create('jurnal_kbm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesantren_id')->constrained('pesantrens')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Ustadz
            $table->foreignId('mapel_id')->constrained('mata_pelajaran')->onDelete('cascade');
            
            $table->date('tanggal');
            $table->string('jam_ke')->nullable(); // e.g., '1-2'
            $table->text('materi'); // e.g., 'Bab Wudhu hal 15-20'
            $table->text('catatan')->nullable(); // Optional notes from Ustadz
            $table->enum('status', ['scheduled', 'completed'])->default('completed');
            
            $table->timestamps();
            
            // Indexing for faster queries
            $table->index(['pesantren_id', 'tanggal']);
            $table->index('user_id');
            $table->index('kelas_id');
        });

        // 2. Detail Absensi per Santri for specific Jurnal
        Schema::create('absensi_kbm_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jurnal_kbm_id')->constrained('jurnal_kbm')->onDelete('cascade');
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alfa'])->default('hadir');
            $table->string('keterangan')->nullable();
            
            $table->timestamps();
            
            $table->unique(['jurnal_kbm_id', 'santri_id']); // One status per student per session
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_kbm_detail');
        Schema::dropIfExists('jurnal_kbm');
    }
};
