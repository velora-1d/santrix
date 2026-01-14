<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $pesantren_id
 * @property string $package_name
 * @property numeric $price
 * @property \Illuminate\Support\Carbon $started_at
 * @property \Illuminate\Support\Carbon $expired_at
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Invoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read \App\Models\Pesantren $pesantren
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription wherePackageName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription wherePesantrenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Subscription extends Model
{
    protected $fillable = [
        'pesantren_id',
        'package_name', // Keeping package_name as per existing, mapped to package in migration
        'price',
        'started_at',
        'expired_at',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'started_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function pesantren()
    {
        return $this->belongsTo(Pesantren::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where('expired_at', '>=', now());
    }
}
