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
        Schema::table('pesantrens', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('subdomain');
            $table->text('alamat')->nullable();
            $table->string('telepon', 20)->nullable();
            $table->string('kota', 100)->nullable();
            $table->string('pimpinan_nama')->nullable();
            $table->string('pimpinan_jabatan')->default('Pengasuh');
            $table->string('pimpinan_ttd_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesantrens', function (Blueprint $table) {
            $table->dropColumn([
                'logo_path',
                'alamat',
                'telepon',
                'kota',
                'pimpinan_nama',
                'pimpinan_jabatan',
                'pimpinan_ttd_path',
            ]);
        });
    }
};
