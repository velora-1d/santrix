<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPesantren;

class TahunAjaran extends Model
{
    use BelongsToPesantren;
    protected $table = 'tahun_ajaran';
    
    protected $fillable = [
        'pesantren_id',
        'nama', // 2024/2025
        'semester',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function pesantren()
    {
        return $this->belongsTo(Pesantren::class);
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    //
}
