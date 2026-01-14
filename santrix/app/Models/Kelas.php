<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPesantren;

/**
 * @property int $id
 * @property int|null $pesantren_id
 * @property string $nama_kelas
 * @property string|null $kitab_talaran
 * @property string $tingkat
 * @property int|null $kapasitas
 * @property string|null $tahun_ajaran
 * @property int|null $level
 * @property string|null $wali_kelas
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $wali_kelas_ttd_path
 * @property string $tipe_wali_kelas
 * @property string|null $wali_kelas_putra
 * @property string|null $wali_kelas_putri
 * @property string|null $wali_kelas_ttd_path_putra
 * @property string|null $wali_kelas_ttd_path_putri
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MataPelajaran> $mataPelajaran
 * @property-read int|null $mata_pelajaran_count
 * @property-read \App\Models\Pesantren|null $pesantren
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Santri> $santri
 * @property-read int|null $santri_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereKapasitas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereKitabTalaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereNamaKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas wherePesantrenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereTahunAjaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereTingkat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereTipeWaliKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereWaliKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereWaliKelasPutra($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereWaliKelasPutri($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereWaliKelasTtdPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereWaliKelasTtdPathPutra($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereWaliKelasTtdPathPutri($value)
 * @mixin \Eloquent
 */
class Kelas extends Model
{
    use BelongsToPesantren;
    protected $table = 'kelas';
    
    protected $fillable = [
        'pesantren_id',
        'nama_kelas',
        'kitab_talaran',
        'tingkat',
        'kapasitas',
        'tahun_ajaran',
        'wali_kelas',
        'wali_kelas_ttd_path',
        'tipe_wali_kelas', // 'tunggal' or 'dual'
        'wali_kelas_putra',
        'wali_kelas_putri',
        'wali_kelas_ttd_path_putra',
        'wali_kelas_ttd_path_putri',
    ];
    
    public function santri()
    {
        return $this->hasMany(Santri::class);
    }
    
    // Helper to get Wali Kelas Name based on Student Gender
    public function getWaliKelasName($gender)
    {
        if ($this->tipe_wali_kelas === 'dual') {
            return ($gender === 'putra' || $gender === 'L') ? $this->wali_kelas_putra : $this->wali_kelas_putri;
        }
        return $this->wali_kelas;
    }
    
    // Helper to get TTD Path based on Student Gender
    public function getWaliKelasTtd($gender)
    {
        if ($this->tipe_wali_kelas === 'dual') {
            return ($gender === 'putra' || $gender === 'L') ? $this->wali_kelas_ttd_path_putra : $this->wali_kelas_ttd_path_putri;
        }
        return $this->wali_kelas_ttd_path;
    }

    public function mataPelajaran()
    {
        return $this->belongsToMany(MataPelajaran::class, 'kelas_mata_pelajaran', 'kelas_id', 'mata_pelajaran_id')
                    ->withTimestamps();
    }
}
