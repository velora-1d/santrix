<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
