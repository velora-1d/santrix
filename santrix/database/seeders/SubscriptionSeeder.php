<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pesantren;
use App\Models\Subscription;
use App\Models\Invoice; // Changed from SubscriptionInvoice
use Carbon\Carbon;

class SubscriptionSeeder extends Seeder
{
    public function run()
    {
        $pesantrens = Pesantren::all();

        foreach ($pesantrens as $pesantren) {
            // 1. Create an old expired subscription (Basic)
            $oldSub = Subscription::create([
                'pesantren_id' => $pesantren->id,
                'package_name' => 'basic', // mapped to package
                'price' => 1500000,
                'started_at' => Carbon::now()->subMonths(7),
                'expired_at' => Carbon::now()->subMonths(1),
                'status' => 'expired',
            ]);

            Invoice::create([
                'pesantren_id' => $pesantren->id,
                'subscription_id' => $oldSub->id,
                'invoice_number' => 'INV-' . Carbon::now()->subMonths(7)->format('Ym') . '-' . $pesantren->id . '-001',
                'amount' => 1500000,
                'period_start' => Carbon::now()->subMonths(7),
                'period_end' => Carbon::now()->subMonths(1),
                'status' => 'paid',
                'paid_at' => Carbon::now()->subMonths(7),
                'payment_method' => 'manual_transfer',
            ]);

            // 2. Create a current active subscription (Advance)
            $currentSub = Subscription::create([
                'pesantren_id' => $pesantren->id,
                'package_name' => 'advance', 
                'price' => 3000000,
                'started_at' => Carbon::now()->subDays(5),
                'expired_at' => Carbon::now()->addMonths(5)->addDays(20),
                'status' => 'active',
            ]);

            // Paid invoice for current period
            Invoice::create([
                'pesantren_id' => $pesantren->id,
                'subscription_id' => $currentSub->id,
                'invoice_number' => 'INV-' . Carbon::now()->format('Ym') . '-' . $pesantren->id . '-002',
                'amount' => 3000000,
                'period_start' => Carbon::now()->subDays(5),
                'period_end' => Carbon::now()->addMonths(6)->subDays(5),
                'status' => 'paid',
                'paid_at' => Carbon::now(), // Paid today
                'payment_method' => 'virtual_account',
            ]);
        }
    }
}
