<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $jurnal_kbm_id
 * @property int $santri_id
 * @property string $status
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\JurnalKbm $jurnal
 * @property-read \App\Models\Santri $santri
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiKbmDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiKbmDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiKbmDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiKbmDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiKbmDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiKbmDetail whereJurnalKbmId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiKbmDetail whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiKbmDetail whereSantriId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiKbmDetail whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiKbmDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AbsensiKbmDetail extends Model
{
    use HasFactory;
    
    protected $table = 'absensi_kbm_detail';
    
    protected $fillable = [
        'jurnal_kbm_id',
        'santri_id',
        'status', // hadir, sakit, izin, alfa
        'keterangan'
    ];
    
    // Relationships
    public function jurnal()
    {
        return $this->belongsTo(JurnalKbm::class, 'jurnal_kbm_id');
    }
    
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}
