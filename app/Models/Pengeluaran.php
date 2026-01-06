<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
use App\Traits\BelongsToPesantren;

class Pengeluaran extends Model
{
    use LogsActivity, BelongsToPesantren;
    
    protected $table = 'pengeluaran';
    
    protected $fillable = [
        'pesantren_id',
        'tahun_ajaran_id',
        'kategori_pengeluaran',
        'tanggal',
        'nominal',
        'keterangan',
        'kategori',
    ];
    
    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
    ];
}
