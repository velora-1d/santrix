<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
use App\Traits\BelongsToPesantren;

/**
 * @property int $id
 * @property int|null $pesantren_id
 * @property string $nis
 * @property string|null $virtual_account_number VA Permanen untuk pembayaran (e.g. Midtrans)
 * @property string $nama_santri
 * @property string $negara
 * @property string $provinsi
 * @property string $kota_kabupaten
 * @property string $kecamatan
 * @property string $desa_kampung
 * @property string $rt_rw
 * @property string $nama_ortu_wali
 * @property string $no_hp_ortu_wali
 * @property int $asrama_id
 * @property int $kobong_id
 * @property int $kelas_id
 * @property string $gender
 * @property \Illuminate\Support\Carbon|null $tanggal_masuk
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Asrama $asrama
 * @property-read \App\Models\Kelas $kelas
 * @property-read \App\Models\Kobong $kobong
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MutasiSantri> $mutasi
 * @property-read int|null $mutasi_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\NilaiSantri> $nilai
 * @property-read int|null $nilai_count
 * @property-read \App\Models\Pesantren|null $pesantren
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UjianMingguan> $ujianMingguan
 * @property-read int|null $ujian_mingguan_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Santri newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Santri newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Santri query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Santri whereAsramaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Santri whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Santri whereDesaKampung($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Santri whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Santri whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Santri whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Santri whereKecamatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Santri whereKelasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Santri whereKobongId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Santri whereKotaKabupaten($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Santri whereNamaOrtuWali($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Santri whereNamaSantri($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Santri whereNegara($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Santri whereNis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Santri whereNoHpOrtuWali($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Santri wherePesantrenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Santri whereProvinsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Santri whereRtRw($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Santri whereTanggalMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Santri whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Santri whereVirtualAccountNumber($value)
 * @mixin \Eloquent
 */
class Santri extends Model
{
    use LogsActivity, BelongsToPesantren;
    
    protected $table = 'santri';
    
    protected $fillable = [
        'pesantren_id',
        'nis',
        'nama_santri',
        'negara',
        'provinsi',
        'kota_kabupaten',
        'kecamatan',
        'desa_kampung',
        'rt_rw',
        'nama_ortu_wali',
        'no_hp_ortu_wali',
        'asrama_id',
        'kobong_id',
        'kelas_id',
        'gender',
        'is_active',
        'tanggal_masuk',
        'virtual_account_number',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_masuk' => 'date',
    ];
    
    // Relationships
    public function asrama()
    {
        return $this->belongsTo(Asrama::class);
    }
    
    public function kobong()
    {
        return $this->belongsTo(Kobong::class);
    }
    
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
    
    public function mutasi()
    {
        return $this->hasMany(MutasiSantri::class);
    }

    public function nilai()
    {
        return $this->hasMany(NilaiSantri::class);
    }

    public function ujianMingguan()
    {
        return $this->hasMany(UjianMingguan::class);
    }
}
