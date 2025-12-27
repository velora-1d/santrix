<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutasiSantri extends Model
{
    protected $table = 'mutasi_santri';
    
    protected $fillable = [
        'santri_id',
        'jenis_mutasi',
        'tanggal_mutasi',
        'keterangan',
        'dari',
        'ke',
    ];
    
    protected $casts = [
        'tanggal_mutasi' => 'date',
    ];
    
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}
