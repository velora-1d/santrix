<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $pesantren_id
 * @property int|null $tahun_ajaran_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $tanggal
 * @property string $jam_masuk
 * @property string|null $jam_pulang
 * @property string $status
 * @property string|null $foto_masuk
 * @property string|null $foto_pulang
 * @property string|null $latitude
 * @property string|null $longitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pesantren $pesantren
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiGuru newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiGuru newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiGuru query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiGuru whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiGuru whereFotoMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiGuru whereFotoPulang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiGuru whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiGuru whereJamMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiGuru whereJamPulang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiGuru whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiGuru whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiGuru wherePesantrenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiGuru whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiGuru whereTahunAjaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiGuru whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiGuru whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiGuru whereUserId($value)
 * @mixin \Eloquent
 */
class AbsensiGuru extends Model
{
    use HasFactory;
    
    protected $table = 'absensi_guru';
    
    protected $fillable = [
        'pesantren_id',
        'tahun_ajaran_id',
        'user_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status',
        'foto_masuk',
        'foto_pulang',
        'latitude',
        'longitude',
    ];
    
    protected $casts = [
        'tanggal' => 'date',
    ];
    
    public function pesantren()
    {
        return $this->belongsTo(Pesantren::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
