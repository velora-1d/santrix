<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    
    protected $fillable = [
        'nama_pegawai',
        'jabatan',
        'departemen',
        'no_hp',
        'alamat',
        'is_active',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    public function gaji()
    {
        return $this->hasMany(GajiPegawai::class);
    }
}
