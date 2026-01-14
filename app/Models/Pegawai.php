<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
use App\Traits\BelongsToPesantren;

/**
 * @property int $id
 * @property int|null $pesantren_id
 * @property string $nama_pegawai
 * @property string $jabatan
 * @property string|null $departemen
 * @property string|null $no_hp
 * @property string|null $alamat
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GajiPegawai> $gaji
 * @property-read int|null $gaji_count
 * @property-read \App\Models\Pesantren|null $pesantren
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereDepartemen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereNamaPegawai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereNoHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai wherePesantrenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Pegawai extends Model
{
    use LogsActivity, BelongsToPesantren;
    
    protected $table = 'pegawai';
    
    protected $fillable = [
        'pesantren_id',
        'nama_pegawai',
        'jabatan',
        'departemen',
        'no_hp',
        'alamat',
        'is_active',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    public function gaji()
    {
        return $this->hasMany(GajiPegawai::class);
    }
}
