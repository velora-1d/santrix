<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
use App\Models\Traits\BelongsToPesantren;

class Syahriah extends Model
{
    use LogsActivity;
    use BelongsToPesantren;
    
    protected $table = 'syahriah';
    
    protected $fillable = [
        'pesantren_id',
        'tahun_ajaran_id',
        'nama_syahriah',
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
