<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiKbmDetail extends Model
{
    use HasFactory;
    
    protected $table = 'absensi_kbm_detail';
    
    protected $fillable = [
        'jurnal_kbm_id',
        'santri_id',
        'status', // hadir, sakit, izin, alfa
        'keterangan'
    ];
    
    // Relationships
    public function jurnal()
    {
        return $this->belongsTo(JurnalKbm::class, 'jurnal_kbm_id');
    }
    
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}
