<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $pesantren_id
 * @property int $santri_id
 * @property int $kelas_id
 * @property int $tahun_ajaran_id
 * @property string|null $semester
 * @property string|null $catatan
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Kelas $kelas
 * @property-read \App\Models\Pesantren $pesantren
 * @property-read \App\Models\Santri $santri
 * @property-read \App\Models\TahunAjaran $tahunAjaran
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKelas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKelas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKelas query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKelas whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKelas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKelas whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKelas whereKelasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKelas wherePesantrenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKelas whereSantriId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKelas whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKelas whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKelas whereTahunAjaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKelas whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RiwayatKelas extends Model
{
    use HasFactory;

    protected $table = 'riwayat_kelas';

    protected $fillable = [
        'pesantren_id',
        'santri_id',
        'kelas_id',
        'tahun_ajaran_id',
        'semester',
        'catatan',
        'status', // promoted, retained, graduated
    ];

    public function pesantren()
    {
        return $this->belongsTo(Pesantren::class);
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
