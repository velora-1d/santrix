<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $pesantren_id
 * @property int|null $tahun_ajaran_id
 * @property int $kelas_id
 * @property int $user_id
 * @property int $mapel_id
 * @property \Illuminate\Support\Carbon $tanggal
 * @property string|null $jam_ke
 * @property string $materi
 * @property string|null $catatan
 * @property string $status
 * @property string|null $foto_awal
 * @property string|null $foto_akhir
 * @property \Illuminate\Support\Carbon|null $jam_mulai
 * @property \Illuminate\Support\Carbon|null $jam_selesai
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AbsensiKbmDetail> $details
 * @property-read int|null $details_count
 * @property-read \App\Models\Kelas $kelas
 * @property-read \App\Models\MataPelajaran $mapel
 * @property-read \App\Models\Pesantren $pesantren
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JurnalKbm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JurnalKbm newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JurnalKbm query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JurnalKbm whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JurnalKbm whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JurnalKbm whereFotoAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JurnalKbm whereFotoAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JurnalKbm whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JurnalKbm whereJamKe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JurnalKbm whereJamMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JurnalKbm whereJamSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JurnalKbm whereKelasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JurnalKbm whereMapelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JurnalKbm whereMateri($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JurnalKbm wherePesantrenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JurnalKbm whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JurnalKbm whereTahunAjaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JurnalKbm whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JurnalKbm whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JurnalKbm whereUserId($value)
 * @mixin \Eloquent
 */
class JurnalKbm extends Model
{
    use HasFactory;
    
    protected $table = 'jurnal_kbm';
    
    protected $fillable = [
        'pesantren_id',
        'tahun_ajaran_id',
        'kelas_id',
        'user_id',
        'mapel_id',
        'tanggal',
        'jam_ke',
        'materi',
        'catatan',
        'status',
        'foto_awal',
        'foto_akhir',
        'jam_mulai',
        'jam_selesai',
    ];
    
    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'datetime',
        'jam_selesai' => 'datetime',
    ];
    
    // Relationships
    public function pesantren()
    {
        return $this->belongsTo(Pesantren::class);
    }
    
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class); // Ustadz
    }
    
    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }
    
    public function details()
    {
        return $this->hasMany(AbsensiKbmDetail::class, 'jurnal_kbm_id');
    }
}
