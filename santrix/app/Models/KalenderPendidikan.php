<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

/**
 * @property int $id
 * @property int|null $pesantren_id
 * @property string $judul
 * @property string|null $deskripsi
 * @property \Illuminate\Support\Carbon $tanggal_mulai
 * @property \Illuminate\Support\Carbon|null $tanggal_selesai
 * @property string $kategori
 * @property string|null $warna
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KalenderPendidikan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KalenderPendidikan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KalenderPendidikan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KalenderPendidikan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KalenderPendidikan whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KalenderPendidikan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KalenderPendidikan whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KalenderPendidikan whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KalenderPendidikan wherePesantrenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KalenderPendidikan whereTanggalMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KalenderPendidikan whereTanggalSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KalenderPendidikan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KalenderPendidikan whereWarna($value)
 * @mixin \Eloquent
 */
class KalenderPendidikan extends Model
{
    use HasFactory, LogsActivity;
    
    protected $table = 'kalender_pendidikan';
    
    protected $fillable = [
        'judul',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'kategori',
        'warna'
    ];
    
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];
    
    // Helper to get color based on category if not set
    public static function getCategoryColor($category)
    {
        return match($category) {
            'Libur' => '#ef4444', // Red
            'Ujian' => '#f59e0b', // Amber
            'Kegiatan' => '#3b82f6', // Blue
            'Rapat' => '#8b5cf6', // Purple
            'Lainnya' => '#6b7280', // Gray
            default => '#3b82f6',
        };
    }
}
