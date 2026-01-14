<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $pesantren_id
 * @property string $nama_yayasan
 * @property string $nama_pondok
 * @property string|null $alamat
 * @property string|null $telepon
 * @property string $kota_terbit
 * @property string $pimpinan_nama
 * @property string $pimpinan_jabatan
 * @property string|null $pimpinan_ttd_path
 * @property string|null $logo_pondok_path
 * @property string|null $logo_pendidikan_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportSettings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportSettings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportSettings query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportSettings whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportSettings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportSettings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportSettings whereKotaTerbit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportSettings whereLogoPendidikanPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportSettings whereLogoPondokPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportSettings whereNamaPondok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportSettings whereNamaYayasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportSettings wherePesantrenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportSettings wherePimpinanJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportSettings wherePimpinanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportSettings wherePimpinanTtdPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportSettings whereTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportSettings whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ReportSettings extends Model
{
    protected $table = 'report_settings';
    
    protected $fillable = [
        'nama_yayasan',
        'nama_pondok',
        'alamat',
        'telepon',
        'kota_terbit',
        'pimpinan_nama',
        'pimpinan_jabatan',
        'pimpinan_ttd_path',
        'logo_pondok_path',
        'logo_pendidikan_path',
    ];

    /**
     * Get the singleton settings record
     */
    public static function getSettings()
    {
        return self::first() ?? new self([
            'nama_yayasan' => 'Yayasan Riyadlul Huda',
            'nama_pondok' => 'Pondok Pesantren Riyadlul Huda',
            'alamat' => 'Jombang, Jawa Timur',
            'kota_terbit' => 'Jombang',
        ]);
    }
}
