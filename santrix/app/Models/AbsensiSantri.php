<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\LogsActivity;
use App\Traits\BelongsToPesantren;

/**
 * @property int $id
 * @property int|null $pesantren_id
 * @property int|null $tahun_ajaran_id
 * @property int $santri_id
 * @property int $kelas_id
 * @property int $tahun
 * @property int $minggu_ke
 * @property \Illuminate\Support\Carbon $tanggal_mulai
 * @property \Illuminate\Support\Carbon $tanggal_selesai
 * @property int $alfa_sorogan
 * @property int $alfa_menghafal_malam
 * @property int $alfa_menghafal_subuh
 * @property int $alfa_tahajud
 * @property string|null $keterangan
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $total_alfa
 * @property-read \App\Models\Kelas $kelas
 * @property-read \App\Models\Pesantren|null $pesantren
 * @property-read \App\Models\Santri $santri
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiSantri newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiSantri newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiSantri query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiSantri whereAlfaMenghafalMalam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiSantri whereAlfaMenghafalSubuh($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiSantri whereAlfaSorogan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiSantri whereAlfaTahajud($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiSantri whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiSantri whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiSantri whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiSantri whereKelasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiSantri whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiSantri whereMingguKe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiSantri wherePesantrenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiSantri whereSantriId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiSantri whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiSantri whereTahunAjaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiSantri whereTanggalMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiSantri whereTanggalSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiSantri whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AbsensiSantri extends Model
{
    use LogsActivity, BelongsToPesantren;
    
    protected $table = 'absensi_santri';
    
    protected $fillable = [
        'pesantren_id',
        'santri_id',
        'tahun_ajaran_id',
        'kelas_id',
        'tahun',
        'minggu_ke',
        'tanggal_mulai',
        'tanggal_selesai',
        'alfa_sorogan',
        'alfa_menghafal_malam',
        'alfa_menghafal_subuh',
        'alfa_tahajud',
        'keterangan',
        'created_by',
    ];
    
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'alfa_sorogan' => 'integer',
        'alfa_menghafal_malam' => 'integer',
        'alfa_menghafal_subuh' => 'integer',
        'alfa_tahajud' => 'integer',
    ];
    
    // Relationships
    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }
    
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }
    
    // Helper methods
    public static function getWeekNumber($date)
    {
        return (int) date('W', strtotime($date));
    }
    
    public static function getMonthName($month)
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $months[$month] ?? '';
    }
    
    // Calculate total alfa
    public function getTotalAlfaAttribute()
    {
        return $this->alfa_sorogan + $this->alfa_menghafal_malam + 
               $this->alfa_menghafal_subuh + $this->alfa_tahajud;
    }
    
    // Get 2-week period dates
    public static function getTwoWeekPeriod($weekNumber, $year)
    {
        // Calculate start date from week number
        $dto = new \DateTime();
        $dto->setISODate($year, $weekNumber);
        $startDate = $dto->format('Y-m-d');
        
        // End date is 13 days later (2 weeks)
        $dto->modify('+13 days');
        $endDate = $dto->format('Y-m-d');
        
        return [
            'start' => $startDate,
            'end' => $endDate,
        ];
    }
}
