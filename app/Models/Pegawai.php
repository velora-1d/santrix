<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
use App\Traits\BelongsToPesantren;

class Pegawai extends Model
{
    use LogsActivity, BelongsToPesantren;
    
    protected $table = 'pegawai';
    
    protected $fillable = [
        'pesantren_id',
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
