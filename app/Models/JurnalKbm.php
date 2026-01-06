<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalKbm extends Model
{
    use HasFactory;
    
    protected $table = 'jurnal_kbm';
    
    protected $fillable = [
        'pesantren_id',
        'tahun_ajaran_id',
        'kelas_id',
        'user_id',
        'mapel_id',
        'tanggal',
        'jam_ke',
        'materi',
        'catatan',
        'status',
        'foto_awal',
        'foto_akhir',
        'jam_mulai',
        'jam_selesai',
    ];
    
    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'datetime',
        'jam_selesai' => 'datetime',
    ];
    
    // Relationships
    public function pesantren()
    {
        return $this->belongsTo(Pesantren::class);
    }
    
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class); // Ustadz
    }
    
    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }
    
    public function details()
    {
        return $this->hasMany(AbsensiKbmDetail::class, 'jurnal_kbm_id');
    }
}
