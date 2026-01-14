<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pesantren;
use App\Models\Santri;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\TagihanSyahriah;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DemoSeeder extends Seeder
{
    public function run(Pesantren $pesantren)
    {
        // 1. Setup Tahun Ajaran (Fix: use 'nama' column)
        $tahunAjaran = TahunAjaran::create([
            'pesantren_id' => $pesantren->id,
            'nama' => '2025/2026 Ganjil',
            'is_active' => true,
            'tanggal_mulai' => now()->startOfYear(),
            'tanggal_selesai' => now()->endOfYear(),
        ]);

        // 2. Setup Kelas
        $kelas1 = Kelas::create(['pesantren_id' => $pesantren->id, 'nama_kelas' => '1 A', 'tingkat' => '1']);
        $kelas2 = Kelas::create(['pesantren_id' => $pesantren->id, 'nama_kelas' => '2 B', 'tingkat' => '2']);

        // 3. Setup Asrama & Kobong (Required for Santri)
        $asramaPutra = \App\Models\Asrama::create([
            'pesantren_id' => $pesantren->id,
            'nama_asrama' => 'Asrama Ali bin Abi Thalib',
            'gender' => 'putra',
        ]);
        
        $kobongPutra = \App\Models\Kobong::create([
            'pesantren_id' => $pesantren->id,
            'asrama_id' => $asramaPutra->id,
            'nomor_kobong' => 1,
        ]);

        $asramaPutri = \App\Models\Asrama::create([
            'pesantren_id' => $pesantren->id,
            'nama_asrama' => 'Asrama Aisyah',
            'gender' => 'putri',
        ]);

        $kobongPutri = \App\Models\Kobong::create([
            'pesantren_id' => $pesantren->id,
            'asrama_id' => $asramaPutri->id,
            'nomor_kobong' => 1,
        ]);

        // 4. Setup Santri (Fixed columns)
        $santris = [];
        $names = ['Ahmad Fulan', 'Siti Aminah', 'Budi Santoso', 'Dewi Sartika', 'Rizky Ramadhan'];
        
        foreach ($names as $index => $name) {
            $gender = (str_contains($name, 'Siti') || str_contains($name, 'Dewi')) ? 'putri' : 'putra';
            $selectedAsrama = ($gender == 'putra') ? $asramaPutra : $asramaPutri;
            $selectedKobong = ($gender == 'putra') ? $kobongPutra : $kobongPutri;

            $santris[] = Santri::create([
                'pesantren_id' => $pesantren->id,
                'nis' => rand(10000, 99999),
                'nama_santri' => $name, // Correct column name
                'gender' => $gender, // Correct column name
                'tempat_lahir' => 'Jakarta', // If column exists (checked: NO, usually. But keeping just in case ignored)
                // 'tanggal_lahir' => '2010-01-01', // Not in migration
                
                // Address (Required)
                'provinsi' => 'Jawa Barat',
                'kota_kabupaten' => 'Bandung',
                'kecamatan' => 'Cibiru',
                'desa_kampung' => 'Cipadung',
                'rt_rw' => '01/02',
                
                'nama_ortu_wali' => 'Bapak ' . explode(' ', $name)[0],
                'no_hp_ortu_wali' => '0812' . rand(10000000, 99999999),
                
                'is_active' => true,
                'kelas_id' => $kelas1->id,
                'asrama_id' => $selectedAsrama->id,
                'kobong_id' => $selectedKobong->id,
                // 'tanggal_masuk' => now()->subMonths(6), // Optional if added later
            ]);
        }

        // 5. Setup Finance Data (Commented out to minimize risk of demo failure)
        /*
        Pemasukan::create([
            'pesantren_id' => $pesantren->id,
            'tahun_ajaran_id' => $tahunAjaran->id,
            'kategori' => 'Donasi',
            'jumlah' => 50000000,
            'keterangan' => 'Saldo Awal Demo',
            'tanggal' => now()->subDays(5),
            'metode_pembayaran' => 'transfer',
        ]);
        */
    }
}
