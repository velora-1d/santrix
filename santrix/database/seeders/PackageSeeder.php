<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = config('subscription.plans', []);

        if (empty($plans)) {
            // Fallback if config is empty
            $plans = [
                [
                    'id' => 'basic-3',
                    'name' => 'Basic',
                    'duration_months' => 3,
                    'price' => 750000,
                    'discount_price' => 1000000, // Example strike-through
                    'description' => 'Fitur lengkap manajemen pesantren tanpa integrasi payment gateway.',
                    'features' => Package::defaultFeatures(),
                    'is_featured' => false,
                ]
            ];
        }

        foreach ($plans as $plan) {
            Package::updateOrCreate(
                ['slug' => $plan['id']],
                [
                    'name' => $plan['name'],
                    'price' => $plan['price'],
                    'discount_price' => isset($plan['discount_price']) ? $plan['discount_price'] : null,
                    'duration_months' => $plan['duration_months'],
                    'description' => $plan['description'] ?? '',
                    'features' => $plan['features'] ?? [],
                    'is_featured' => $plan['is_featured'] ?? false,
                    'sort_order' => $plan['sort_order'] ?? 0,
                    'max_santri' => $plan['max_santri'] ?? null,
                    'max_users' => $plan['max_users'] ?? null,
                ]
            );
        }
    }
}
