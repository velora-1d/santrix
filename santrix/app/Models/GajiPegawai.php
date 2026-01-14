<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
use App\Traits\BelongsToPesantren;

/**
 * @property int $id
 * @property int|null $tahun_ajaran_id
 * @property int|null $pesantren_id
 * @property int $pegawai_id
 * @property int $bulan
 * @property int $tahun
 * @property numeric $nominal
 * @property bool $is_dibayar
 * @property \Illuminate\Support\Carbon|null $tanggal_bayar
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pegawai $pegawai
 * @property-read \App\Models\Pesantren|null $pesantren
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GajiPegawai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GajiPegawai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GajiPegawai query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GajiPegawai whereBulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GajiPegawai whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GajiPegawai whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GajiPegawai whereIsDibayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GajiPegawai whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GajiPegawai whereNominal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GajiPegawai wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GajiPegawai wherePesantrenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GajiPegawai whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GajiPegawai whereTahunAjaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GajiPegawai whereTanggalBayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GajiPegawai whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GajiPegawai extends Model
{
    use LogsActivity, BelongsToPesantren;
    
    protected $table = 'gaji_pegawai';
    
    protected $fillable = [
        'pesantren_id',
        'pegawai_id',
        'tahun_ajaran_id',
        'bulan',
        'tahun',
        'nominal',
        'is_dibayar',
        'tanggal_bayar',
        'keterangan',
    ];
    
    protected $casts = [
        'is_dibayar' => 'boolean',
        'tanggal_bayar' => 'date',
        'nominal' => 'decimal:2',
    ];
    
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
