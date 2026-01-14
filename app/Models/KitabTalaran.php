<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $pesantren_id
 * @property int|null $kelas_id
 * @property int $semester
 * @property string $nama_kitab
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Kelas|null $kelas
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KitabTalaran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KitabTalaran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KitabTalaran query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KitabTalaran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KitabTalaran whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KitabTalaran whereKelasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KitabTalaran whereNamaKitab($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KitabTalaran wherePesantrenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KitabTalaran whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KitabTalaran whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class KitabTalaran extends Model
{
    protected $fillable = [
        'kelas_id',
        'tahun_ajaran_id',
        'semester',
        'nama_kitab',
    ];
    
    protected $casts = [
        'semester' => 'integer',
    ];
    
    // Relationships
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
