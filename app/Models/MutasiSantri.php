<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

/**
 * @property int $id
 * @property int|null $pesantren_id
 * @property int $santri_id
 * @property int|null $tahun_ajaran_id
 * @property string $jenis_mutasi
 * @property \Illuminate\Support\Carbon $tanggal_mutasi
 * @property string|null $keterangan
 * @property string|null $dari
 * @property string|null $ke
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Santri $santri
 * @property-read \App\Models\TahunAjaran|null $tahunAjaran
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiSantri newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiSantri newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiSantri query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiSantri whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiSantri whereDari($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiSantri whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiSantri whereJenisMutasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiSantri whereKe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiSantri whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiSantri wherePesantrenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiSantri whereSantriId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiSantri whereTahunAjaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiSantri whereTanggalMutasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MutasiSantri whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MutasiSantri extends Model
{
    use LogsActivity;
    
    protected $table = 'mutasi_santri';
    
    protected $fillable = [
        'pesantren_id',
        'santri_id',
        'tahun_ajaran_id',
        'jenis_mutasi',
        'tanggal_mutasi',
        'keterangan',
        'dari',
        'ke',
    ];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
    
    protected $casts = [
        'tanggal_mutasi' => 'date',
    ];
    
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}
