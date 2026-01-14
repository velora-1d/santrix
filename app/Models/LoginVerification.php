<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property \Illuminate\Support\Carbon $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginVerification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginVerification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginVerification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginVerification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginVerification whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginVerification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginVerification whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginVerification whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginVerification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginVerification whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginVerification whereUserId($value)
 * @mixin \Eloquent
 */
class LoginVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token',
        'ip_address',
        'user_agent',
        'expires_at',
        'verified_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
