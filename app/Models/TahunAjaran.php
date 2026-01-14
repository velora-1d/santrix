<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPesantren;

/**
 * @property int $id
 * @property int|null $pesantren_id
 * @property string $nama
 * @property string $semester
 * @property \Illuminate\Support\Carbon|null $tanggal_mulai
 * @property \Illuminate\Support\Carbon|null $tanggal_selesai
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pesantren|null $pesantren
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TahunAjaran active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TahunAjaran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TahunAjaran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TahunAjaran query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TahunAjaran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TahunAjaran whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TahunAjaran whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TahunAjaran whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TahunAjaran wherePesantrenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TahunAjaran whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TahunAjaran whereTanggalMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TahunAjaran whereTanggalSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TahunAjaran whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
