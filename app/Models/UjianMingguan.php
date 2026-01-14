<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
use App\Traits\BelongsToPesantren;

/**
 * @property int $id
 * @property int|null $pesantren_id
 * @property int|null $tahun_ajaran_id
 * @property int $santri_id
 * @property int $mapel_id
 * @property int $kelas_id
 * @property string $tahun_ajaran
 * @property string $semester
 * @property numeric|null $minggu_1
 * @property numeric|null $minggu_2
 * @property numeric|null $minggu_3
 * @property numeric|null $minggu_4
 * @property int $jumlah_keikutsertaan
 * @property string $status
 * @property numeric|null $nilai_hasil_mingguan
 * @property numeric|null $rata_rata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $weekly_scores
 * @property-read \App\Models\MataPelajaran $mataPelajaran
 * @property-read \App\Models\Pesantren|null $pesantren
 * @property-read \App\Models\Santri $santri
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UjianMingguan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UjianMingguan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UjianMingguan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UjianMingguan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UjianMingguan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UjianMingguan whereJumlahKeikutsertaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UjianMingguan whereKelasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UjianMingguan whereMapelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UjianMingguan whereMinggu1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UjianMingguan whereMinggu2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UjianMingguan whereMinggu3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UjianMingguan whereMinggu4($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UjianMingguan whereNilaiHasilMingguan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UjianMingguan wherePesantrenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UjianMingguan whereRataRata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UjianMingguan whereSantriId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UjianMingguan whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UjianMingguan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UjianMingguan whereTahunAjaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UjianMingguan whereTahunAjaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UjianMingguan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UjianMingguan extends Model
{
    use HasFactory, LogsActivity, BelongsToPesantren;

    protected $table = 'ujian_mingguan';

    protected $fillable = [
        'santri_id',
        'mapel_id',
        'tahun_ajaran_id', // Foreign key to tahun_ajaran table
        'tahun_ajaran', // String representation (keep for backward compat or remove if unused)
        'semester',
        'minggu_1',
        'minggu_2',
        'minggu_3',
        'minggu_4',
        'jumlah_keikutsertaan',
        'status',
        'nilai_hasil_mingguan',
    ];

    protected $casts = [
        'minggu_1' => 'decimal:2',
        'minggu_2' => 'decimal:2',
        'minggu_3' => 'decimal:2',
        'minggu_4' => 'decimal:2',
        'nilai_hasil_mingguan' => 'decimal:2',
        'jumlah_keikutsertaan' => 'integer',
    ];

    /**
     * Relationship to Santri
     */
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    /**
     * Relationship to Mata Pelajaran
     */
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

    /**
     * Get all weekly scores as an array
     */
    public function getWeeklyScoresAttribute()
    {
        return [
            $this->minggu_1,
            $this->minggu_2,
            $this->minggu_3,
            $this->minggu_4,
        ];
    }

    /**
     * Calculate and update status and nilai_hasil_mingguan
     */
    public function calculateStatus()
    {
        $weeks = $this->weekly_scores;
        
        // Count attended weeks (non-null values)
        $attended = collect($weeks)->filter(fn($v) => !is_null($v))->count();
        $this->jumlah_keikutsertaan = $attended;
        
        // Determine status based on attendance
        if ($attended >= 3) {
            $this->status = 'SAH';
            // Calculate average of attended weeks
            $attendedScores = collect($weeks)->filter(fn($v) => !is_null($v));
            $this->nilai_hasil_mingguan = $attendedScores->avg();
        } else {
            $this->status = 'TIDAK SAH';
            $this->nilai_hasil_mingguan = null;
        }
        
        return $this;
    }
}
