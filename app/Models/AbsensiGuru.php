<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiGuru extends Model
{
    use HasFactory;
    
    protected $table = 'absensi_guru';
    
    protected $fillable = [
        'pesantren_id',
        'tahun_ajaran_id',
        'user_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status',
        'foto_masuk',
        'foto_pulang',
        'latitude',
        'longitude',
    ];
    
    protected $casts = [
        'tanggal' => 'date',
    ];
    
    public function pesantren()
    {
        return $this->belongsTo(Pesantren::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
