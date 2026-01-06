<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
use App\Models\Traits\BelongsToPesantren;

class Pemasukan extends Model
{
    use LogsActivity, BelongsToPesantren;
    
    protected $table = 'pemasukan';
    
    protected $fillable = [
        'pesantren_id',
        'tahun_ajaran_id',
        'sumber_pemasukan',
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
