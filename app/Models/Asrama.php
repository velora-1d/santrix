<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPesantren;

/**
 * @property int $id
 * @property int|null $pesantren_id
 * @property string $nama_asrama
 * @property string $gender
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Kobong> $kobong
 * @property-read int|null $kobong_count
 * @property-read \App\Models\Pesantren|null $pesantren
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Santri> $santri
 * @property-read int|null $santri_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asrama newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asrama newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asrama query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asrama whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asrama whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asrama whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asrama whereNamaAsrama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asrama wherePesantrenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Asrama whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Asrama extends Model
{
    use BelongsToPesantren;
    protected $table = 'asrama';
    
    protected $fillable = [
        'pesantren_id',
        'nama_asrama',
        'gender',
    ];
    
    public function santri()
    {
        return $this->hasMany(Santri::class);
    }
    
    public function kobong()
    {
        return $this->hasMany(Kobong::class);
    }
}
