<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
use App\Traits\BelongsToPesantren;

/**
 * @property int $id
 * @property int|null $pesantren_id
 * @property int|null $tahun_ajaran_id
 * @property string $jenis_pengeluaran
 * @property \Illuminate\Support\Carbon $tanggal
 * @property numeric $nominal
 * @property string|null $keterangan
 * @property string|null $kategori
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pesantren|null $pesantren
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengeluaran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengeluaran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengeluaran query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengeluaran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengeluaran whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengeluaran whereJenisPengeluaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengeluaran whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengeluaran whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengeluaran whereNominal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengeluaran wherePesantrenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengeluaran whereTahunAjaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengeluaran whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengeluaran whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Pengeluaran extends Model
{
    use LogsActivity, BelongsToPesantren;
    
    protected $table = 'pengeluaran';
    
    protected $fillable = [
        'pesantren_id',
        'tahun_ajaran_id',
        'jenis_pengeluaran',
        'kategori_pengeluaran',
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
