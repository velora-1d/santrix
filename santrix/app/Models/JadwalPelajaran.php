<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\LogsActivity;
use App\Traits\BelongsToPesantren;

/**
 * @property int $id
 * @property int|null $pesantren_id
 * @property int|null $tahun_ajaran_id
 * @property int $kelas_id
 * @property int $mapel_id
 * @property string $hari
 * @property string $jam_mulai
 * @property string $jam_selesai
 * @property string|null $ruangan
 * @property string|null $guru_badal
 * @property string $tahun_ajaran
 * @property string $semester
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Kelas $kelas
 * @property-read \App\Models\MataPelajaran $mapel
 * @property-read \App\Models\MataPelajaran $mataPelajaran
 * @property-read \App\Models\Pesantren|null $pesantren
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereGuruBadal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereHari($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereJamMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereJamSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereKelasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereMapelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran wherePesantrenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereRuangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereTahunAjaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereTahunAjaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class JadwalPelajaran extends Model
{
    use BelongsToPesantren, LogsActivity;
    
    protected $table = 'jadwal_pelajaran';
    
    protected $fillable = [
        'pesantren_id',
        'tahun_ajaran_id',
        'kelas_id',
        'mapel_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'tahun_ajaran',
        'semester',
    ];
    
    // protected $casts = [
    //     'jam_mulai' => 'datetime:H:i',
    //     'jam_selesai' => 'datetime:H:i',
    // ];
    
    // Relationships
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }
    
    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }
    
    // Alias for shorter name
    public function mapel(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }
}
