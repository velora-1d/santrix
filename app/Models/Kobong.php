<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPesantren;

/**
 * @property int $id
 * @property int|null $pesantren_id
 * @property int $asrama_id
 * @property int $nomor_kobong
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Asrama $asrama
 * @property-read \App\Models\Pesantren|null $pesantren
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Santri> $santri
 * @property-read int|null $santri_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kobong newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kobong newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kobong query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kobong whereAsramaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kobong whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kobong whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kobong whereNomorKobong($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kobong wherePesantrenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kobong whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Kobong extends Model
{
    use BelongsToPesantren;
    protected $table = 'kobong';
    
    protected $fillable = [
        'pesantren_id',
        'asrama_id',
        'nama_kobong',
        'nomor_kobong',
    ];
    
    public function asrama()
    {
        return $this->belongsTo(Asrama::class);
    }
    
    public function santri()
    {
        return $this->hasMany(Santri::class);
    }
}
