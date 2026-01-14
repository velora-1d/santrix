<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\BelongsToPesantren;
use App\Traits\LogsActivity;

/**
 * @property int $id
 * @property int|null $pesantren_id
 * @property string $nama_mapel
 * @property string $kode_mapel
 * @property string $kategori
 * @property int $kkm
 * @property bool $is_talaran
 * @property bool $has_weekly_exam
 * @property string|null $guru_pengampu
 * @property string|null $guru_badal
 * @property string $waktu_pelajaran
 * @property string|null $deskripsi
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JadwalPelajaran> $jadwalPelajaran
 * @property-read int|null $jadwal_pelajaran_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Kelas> $kelas
 * @property-read int|null $kelas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\NilaiSantri> $nilaiSantri
 * @property-read int|null $nilai_santri_count
 * @property-read \App\Models\Pesantren|null $pesantren
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran whereGuruBadal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran whereGuruPengampu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran whereHasWeeklyExam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran whereIsTalaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran whereKkm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran whereKodeMapel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran whereNamaMapel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran wherePesantrenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran whereWaktuPelajaran($value)
 * @mixin \Eloquent
 */
class MataPelajaran extends Model
{
    use BelongsToPesantren;
    use LogsActivity;
    
    protected $table = 'mata_pelajaran';
    
    protected $fillable = [
        'pesantren_id',
        'nama_mapel',
        'nama_pelajaran',
        'kode_mapel',
        'kategori',
        'guru_pengampu',
        'waktu_pelajaran',
        'deskripsi',
        'is_active',
        'is_talaran',
        'has_weekly_exam',
        'kkm', // Added
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'is_talaran' => 'boolean',
        'has_weekly_exam' => 'boolean',
    ];
    
    // Relationships
    public function nilaiSantri(): HasMany
    {
        return $this->hasMany(NilaiSantri::class, 'mapel_id');
    }
    
    public function jadwalPelajaran(): HasMany
    {
        return $this->hasMany(JadwalPelajaran::class, 'mapel_id');
    }
    
    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_mata_pelajaran', 'mata_pelajaran_id', 'kelas_id')
                    ->withTimestamps()
                    ->withPivot('is_kelas_umum');
    }
}
