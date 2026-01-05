<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'discount_price',
        'duration_months',
        'description',
        'features',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
    ];

    /**
     * Get the formatted price.
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format((float) $this->price, 0, ',', '.');
    }

    /**
     * Get the formatted discount price.
     */
    public function getFormattedDiscountPriceAttribute()
    {
        if (!$this->discount_price) {
            return null;
        }
        return 'Rp ' . number_format((float) $this->discount_price, 0, ',', '.');
    }

    /**
     * Get default features if none set.
     */
    public static function defaultFeatures()
    {
        return [
            ['name' => 'Data Santri (Unlimited)', 'included' => true],
            ['name' => 'Keuangan SPP & Tabungan', 'included' => true],
            ['name' => 'Laporan Keuangan', 'included' => true],
            ['name' => 'Akademik & Rapot', 'included' => true],
            ['name' => 'Kalender & Agenda', 'included' => true],
            ['name' => 'Notifikasi WhatsApp Manual', 'included' => true],
            ['name' => 'Invoice WhatsApp Otomatis', 'included' => false],
            ['name' => 'Broadcast Tagihan Bulanan (WA)', 'included' => false],
            ['name' => 'Syahriah Payment Gateway (VA)', 'included' => false],
            ['name' => 'Kartu Syahriah Digital (VA)', 'included' => false],
            ['name' => 'Pencairan Saldo Otomatis', 'included' => false],
            ['name' => 'Prioritas Support', 'included' => false],
        ];
    }
}
