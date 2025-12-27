<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Syahriah extends Model
{
    protected $table = 'syahriah';
    
    protected $fillable = [
        'santri_id',
        'bulan',
        'tahun',
        'nominal',
        'is_lunas',
        'tanggal_bayar',
        'keterangan',
    ];
    
    protected $casts = [
        'is_lunas' => 'boolean',
        'tanggal_bayar' => 'date',
        'nominal' => 'decimal:2',
    ];
    
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}
