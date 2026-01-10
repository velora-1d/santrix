<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class MutasiSantri extends Model
{
    use LogsActivity;
    
    protected $table = 'mutasi_santri';
    
    protected $fillable = [
        'pesantren_id',
        'santri_id',
        'tahun_ajaran_id',
        'jenis_mutasi',
        'tanggal_mutasi',
        'keterangan',
        'dari',
        'ke',
    ];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
    
    protected $casts = [
        'tanggal_mutasi' => 'date',
    ];
    
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}
