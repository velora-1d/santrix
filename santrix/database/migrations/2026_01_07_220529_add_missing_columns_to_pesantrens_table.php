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
            if (!Schema::hasColumn('pesantrens', 'kategori')) {
                $table->string('kategori')->default('Modern')->after('domain');
            }
            if (!Schema::hasColumn('pesantrens', 'alamat')) {
                $table->text('alamat')->nullable()->after('kategori');
            }
            if (!Schema::hasColumn('pesantrens', 'kota')) {
                $table->string('kota')->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('pesantrens', 'telepon')) {
                $table->string('telepon')->nullable()->after('kota');
            }
            if (!Schema::hasColumn('pesantrens', 'pimpinan_nama')) {
                $table->string('pimpinan_nama')->nullable()->after('telepon');
            }
            if (!Schema::hasColumn('pesantrens', 'pimpinan_jabatan')) {
                $table->string('pimpinan_jabatan')->nullable()->after('pimpinan_nama');
            }
            if (!Schema::hasColumn('pesantrens', 'pimpinan_ttd_path')) {
                $table->string('pimpinan_ttd_path')->nullable()->after('pimpinan_jabatan');
            }
            if (!Schema::hasColumn('pesantrens', 'logo_path')) {
                $table->string('logo_path')->nullable()->after('pimpinan_ttd_path');
            }
            if (!Schema::hasColumn('pesantrens', 'logo_pendidikan_path')) {
                $table->string('logo_pendidikan_path')->nullable()->after('logo_path');
            }
            if (!Schema::hasColumn('pesantrens', 'is_demo')) {
                $table->boolean('is_demo')->default(false)->after('status');
            }
            
            // Advance Funding Columns
            if (!Schema::hasColumn('pesantrens', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('expired_at');
            }
            if (!Schema::hasColumn('pesantrens', 'account_number')) {
                $table->string('account_number')->nullable()->after('bank_name');
            }
            if (!Schema::hasColumn('pesantrens', 'account_name')) {
                $table->string('account_name')->nullable()->after('account_number');
            }
            if (!Schema::hasColumn('pesantrens', 'saldo_pg')) {
                $table->decimal('saldo_pg', 15, 2)->default(0)->after('account_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesantrens', function (Blueprint $table) {
            $columns = [
                'kategori', 'alamat', 'kota', 'telepon', 
                'pimpinan_nama', 'pimpinan_jabatan', 'pimpinan_ttd_path',
                'logo_path', 'logo_pendidikan_path', 'is_demo',
                'bank_name', 'account_number', 'account_name', 'saldo_pg'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('pesantrens', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
