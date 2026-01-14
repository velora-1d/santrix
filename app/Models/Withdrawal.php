<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $pesantren_id
 * @property numeric $amount
 * @property string $status
 * @property string $bank_name
 * @property string $account_number
 * @property string $account_name
 * @property string|null $proof_path
 * @property string|null $admin_note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pesantren $pesantren
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Withdrawal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Withdrawal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Withdrawal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Withdrawal whereAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Withdrawal whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Withdrawal whereAdminNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Withdrawal whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Withdrawal whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Withdrawal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Withdrawal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Withdrawal wherePesantrenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Withdrawal whereProofPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Withdrawal whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Withdrawal whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesantren_id',
        'amount',
        'status', // pending, approved, rejected
        'bank_name',
        'account_number',
        'account_name',
        'proof_path',
        'admin_note'
    ];

    public function pesantren()
    {
        return $this->belongsTo(Pesantren::class);
    }
}
