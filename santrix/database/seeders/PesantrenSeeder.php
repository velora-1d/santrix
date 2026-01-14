<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PesantrenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Pesantren::create([
            'nama' => 'Pondok Pesantren Al-Ikhlas',
            'subdomain' => 'al-ikhlas',
            'package' => 'advance',
            'status' => 'active',
            'expired_at' => now()->addYear(),
        ]);

        \App\Models\Pesantren::create([
            'nama' => 'Pondok Pesantren Al-Hidayah',
            'subdomain' => 'al-hidayah', // Demo tenant 2
            'package' => 'basic',
            'status' => 'active',
            'expired_at' => now()->addYear(),
        ]);
    }
}
