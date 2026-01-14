<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pesantren;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteExpiredTrialAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trial:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete pesantren accounts that are unpaid 4 days after trial expires';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Checking for expired trial accounts...');

        // Find pesantrens where delete_at has passed AND no active subscription
        $expiredAccounts = Pesantren::where('delete_at', '<=', now())
            ->whereDoesntHave('subscriptions', function ($query) {
                $query->where('status', 'active')
                      ->where('expired_at', '>', now());
            })
            ->get();

        if ($expiredAccounts->isEmpty()) {
            $this->info('✅ No expired accounts to delete.');
            return 0;
        }

        $count = 0;
        foreach ($expiredAccounts as $pesantren) {
            DB::beginTransaction();
            
            try {
                $this->warn("⚠️  Deleting: {$pesantren->nama} (subdomain: {$pesantren->subdomain})");

                // Delete related data
                User::where('pesantren_id', $pesantren->id)->delete();
                Subscription::where('pesantren_id', $pesantren->id)->delete();
                Invoice::where('pesantren_id', $pesantren->id)->delete();

                // Delete pesantren itself
                $pesantren->delete();

                DB::commit();
                $count++;

                Log::info("Trial account auto-deleted: {$pesantren->nama} (ID: {$pesantren->id})");
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("❌ Failed to delete {$pesantren->nama}: " . $e->getMessage());
                Log::error("Failed to delete trial account: " . $e->getMessage());
            }
        }

        $this->info("🗑️  Deleted {$count} expired trial account(s).");
        return 0;
    }
}
