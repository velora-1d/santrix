<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
use App\Traits\BelongsToPesantren;

/**
 * @property int $id
 * @property int|null $pesantren_id
 * @property int|null $tahun_ajaran_id
 * @property int $santri_id
 * @property int $bulan
 * @property int $tahun
 * @property numeric $nominal
 * @property bool $is_lunas
 * @property \Illuminate\Support\Carbon|null $tanggal_bayar
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pesantren|null $pesantren
 * @property-read \App\Models\Santri $santri
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Syahriah newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Syahriah newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Syahriah query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Syahriah whereBulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Syahriah whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Syahriah whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Syahriah whereIsLunas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Syahriah whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Syahriah whereNominal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Syahriah wherePesantrenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Syahriah whereSantriId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Syahriah whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Syahriah whereTahunAjaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Syahriah whereTanggalBayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Syahriah whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Syahriah extends Model
{
    use LogsActivity, BelongsToPesantren;
    
    protected $table = 'syahriah';
    
    protected $fillable = [
        'pesantren_id',
        'tahun_ajaran_id',
        'santri_id',
        'nama_syahriah',
        'bulan',
        'tahun',
        'nominal',
        'is_lunas',
        'tanggal_bayar',
        'keterangan',
    ];
    
    protected $casts = [
        'is_lunas' => 'boolean',
        'tanggal_bayar' => 'date',
        'nominal' => 'decimal:2',
    ];
    
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}
