<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property numeric $price
 * @property numeric|null $discount_price
 * @property int $duration_months
 * @property string|null $description
 * @property array<array-key, mixed>|null $features
 * @property int|null $max_santri Null means unlimited
 * @property int|null $max_users Null means unlimited
 * @property bool $is_featured
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $formatted_discount_price
 * @property-read mixed $formatted_price
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereDiscountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereDurationMonths($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereFeatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereMaxSantri($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereMaxUsers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
        'max_santri',
        'max_users',
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
