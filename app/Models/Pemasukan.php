<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
use App\Traits\BelongsToPesantren;

/**
 * @property int $id
 * @property int|null $pesantren_id
 * @property int|null $tahun_ajaran_id
 * @property string $sumber_pemasukan
 * @property \Illuminate\Support\Carbon $tanggal
 * @property numeric $nominal
 * @property string|null $keterangan
 * @property string|null $kategori
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pesantren|null $pesantren
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemasukan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemasukan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemasukan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemasukan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemasukan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemasukan whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemasukan whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemasukan whereNominal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemasukan wherePesantrenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemasukan whereSumberPemasukan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemasukan whereTahunAjaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemasukan whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pemasukan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Pemasukan extends Model
{
    use LogsActivity, BelongsToPesantren;
    
    protected $table = 'pemasukan';
    
    protected $fillable = [
        'pesantren_id',
        'tahun_ajaran_id',
        'sumber_pemasukan',
        'tanggal',
        'nominal',
        'keterangan',
        'kategori',
    ];
    
    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
    ];
}
