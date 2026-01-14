<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int|null $pesantren_id
 * @property int $santri_id
 * @property int|null $tahun_ajaran_id
 * @property string $bulan
 * @property int $tahun
 * @property int $minggu_1
 * @property int $minggu_2
 * @property int $tamat_1_2
 * @property int $alfa_1_2
 * @property int $minggu_3
 * @property int $minggu_4
 * @property int $jumlah
 * @property int $tamat
 * @property int $alfa
 * @property string|null $total
 * @property int $jumlah_1_2
 * @property int $jumlah_3_4
 * @property string|null $total_1_2
 * @property string|null $total_3_4
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Santri $santri
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran whereAlfa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran whereAlfa12($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran whereBulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran whereJumlah12($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran whereJumlah34($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran whereMinggu1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran whereMinggu2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran whereMinggu3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran whereMinggu4($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran wherePesantrenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran whereSantriId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran whereTahunAjaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran whereTamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran whereTamat12($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran whereTotal12($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran whereTotal34($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Talaran withoutTrashed()
 * @mixin \Eloquent
 */
class Talaran extends Model
{
    use SoftDeletes;

    protected $table = 'talaran_santri';
    
    protected $fillable = [
        'pesantren_id',
        'santri_id',
        'tahun_ajaran_id',
        'bulan',
        'tahun',
        'minggu_1',
        'minggu_2',
        'minggu_3',
        'minggu_4',
        'jumlah',
        'tamat',
        'alfa',
        'total',
        'tamat_1_2',
        'alfa_1_2',
        'jumlah_1_2',
        'jumlah_3_4',
        'total_1_2',
        'total_3_4',
    ];
    
    protected $casts = [
        'tahun' => 'integer',
        'minggu_1' => 'integer',
        'minggu_2' => 'integer',
        'minggu_3' => 'integer',
        'minggu_4' => 'integer',
        'jumlah' => 'integer',
        'tamat' => 'integer',
        'alfa' => 'integer',
        'tamat_1_2' => 'integer',
        'alfa_1_2' => 'integer',
        'jumlah_1_2' => 'integer',
        'jumlah_3_4' => 'integer',
    ];

    // Auto-calculate before saving
    // Auto-calculation removed - moved to Controller
    // protected static function booted() { ... }
    
    private static function formatTotalString($jumlah, $tamat)
    {
        if ($tamat > 0) {
            return "{$tamat}x tamat + {$jumlah} bait/jajar";
        }
        return "{$jumlah} bait/jajar";
    }

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }
}
