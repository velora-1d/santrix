<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $nama
 * @property string $subdomain
 * @property string|null $logo_path
 * @property string|null $logo_pendidikan_path
 * @property string|null $domain
 * @property string $kategori
 * @property string $package
 * @property string $status
 * @property int $is_demo
 * @property \Illuminate\Support\Carbon|null $expired_at
 * @property string|null $trial_ends_at
 * @property string|null $delete_at Auto-delete account if unpaid after grace period
 * @property string|null $bank_name
 * @property string|null $account_number
 * @property string|null $account_name
 * @property numeric $saldo_pg
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $alamat
 * @property string|null $telepon
 * @property string|null $kota
 * @property string|null $pimpinan_nama
 * @property string $pimpinan_jabatan
 * @property string|null $pimpinan_ttd_path
 * @property-read \App\Models\User|null $admin
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Asrama> $asrama
 * @property-read int|null $asrama_count
 * @property-read \App\Models\Subscription|null $currentSubscription
 * @property-read mixed $logo_pendidikan_url
 * @property-read mixed $logo_url
 * @property-read int|null $santri_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Invoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Kelas> $kelas
 * @property-read int|null $kelas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MataPelajaran> $mataPelajaran
 * @property-read int|null $mata_pelajaran_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Santri> $santri
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subscription> $subscriptions
 * @property-read int|null $subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Withdrawal> $withdrawals
 * @property-read int|null $withdrawals_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren whereAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren whereDeleteAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren whereIsDemo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren whereKota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren whereLogoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren whereLogoPendidikanPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren wherePackage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren wherePimpinanJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren wherePimpinanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren wherePimpinanTtdPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren whereSaldoPg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren whereSubdomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren whereTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren whereTrialEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesantren whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Pesantren extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'subdomain',
        'domain',
        'package',
        'status',
        'is_demo',
        'expired_at',
        'logo_path',
        'logo_pendidikan_path',
        'alamat',
        'telepon',
        'kota',
        'pimpinan_nama',
        'pimpinan_jabatan',
        'pimpinan_ttd_path',
        
        // Advance Funding
        'bank_name',
        'account_number',
        'account_name',
        'saldo_pg',
    ];

    protected $casts = [
        'expired_at' => 'date',
        'saldo_pg' => 'decimal:2',
    ];

    protected $appends = ['logo_url', 'logo_pendidikan_url'];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function currentSubscription()
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    public function invoices()
    {
        return $this->hasManyThrough(Invoice::class, Subscription::class);
    }

    public function santri()
    {
        return $this->hasMany(Santri::class);
    }

    public function asrama()
    {
        return $this->hasMany(Asrama::class);
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }

    public function mataPelajaran()
    {
        return $this->hasMany(MataPelajaran::class);
    }

    // Helper to get connected users/santri count
    public function getSantriCountAttribute()
    {
        // scalable approach: count from santris table where pesantren_id fits
        return \App\Models\Santri::where('pesantren_id', $this->id)->count();
    }

    // Get all users belonging to this pesantren
    public function users()
    {
        return $this->hasMany(\App\Models\User::class);
    }

    // Get the primary admin user for this pesantren
    public function admin()
    {
        return $this->hasOne(\App\Models\User::class)->where('role', 'admin')->oldest();
    }

    // Get logo URL with fallback to default
    public function getLogoUrlAttribute()
    {
        if ($this->logo_path && Storage::disk('public')->exists($this->logo_path)) {
            return Storage::url($this->logo_path);
        }
        return asset('images/default-logo.png');
    }

    // Get logo pendidikan URL (optional, no fallback)
    public function getLogoPendidikanUrlAttribute()
    {
        if ($this->logo_pendidikan_path && Storage::disk('public')->exists($this->logo_pendidikan_path)) {
            return Storage::url($this->logo_pendidikan_path);
        }
        return null;
    }
}
