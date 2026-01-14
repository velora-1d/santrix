<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $pesantren_id
 * @property \Illuminate\Support\Carbon|null $tanggal_ijazah
 * @property int $last_nomor_ibtida
 * @property int $last_nomor_tsanawi
 * @property string|null $tahun_ajaran
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IjazahSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IjazahSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IjazahSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IjazahSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IjazahSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IjazahSetting whereLastNomorIbtida($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IjazahSetting whereLastNomorTsanawi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IjazahSetting wherePesantrenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IjazahSetting whereTahunAjaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IjazahSetting whereTanggalIjazah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IjazahSetting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class IjazahSetting extends Model
{
    protected $fillable = [
        'tanggal_ijazah',
        'last_nomor_ibtida',
        'last_nomor_tsanawi',
        'tahun_ajaran',
    ];

    protected $casts = [
        'tanggal_ijazah' => 'date',
    ];

    /**
     * Get the singleton settings record
     */
    public static function getSettings()
    {
        return self::first() ?? self::create([
            'tanggal_ijazah' => now(),
            'last_nomor_ibtida' => 0,
            'last_nomor_tsanawi' => 0,
            'tahun_ajaran' => date('Y') . '/' . (date('Y') + 1),
        ]);
    }

    /**
     * Generate next nomor ijazah for Ibtida
     */
    public function generateNomorIbtida()
    {
        $this->increment('last_nomor_ibtida');
        $nomor = str_pad($this->last_nomor_ibtida, 3, '0', STR_PAD_LEFT);
        $tahun = date('Y');
        return "{$nomor}/IJZ-IBT/{$tahun}";
    }

    /**
     * Generate next nomor ijazah for Tsanawi
     */
    public function generateNomorTsanawi()
    {
        $this->increment('last_nomor_tsanawi');
        $nomor = str_pad($this->last_nomor_tsanawi, 3, '0', STR_PAD_LEFT);
        $tahun = date('Y');
        return "{$nomor}/IJZ-TSN/{$tahun}";
    }

    /**
     * Generate auto NIP
     */
    public static function generateNIP($prefix = 'GTY')
    {
        $random = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        return "{$prefix}.{$random}";
    }
}
