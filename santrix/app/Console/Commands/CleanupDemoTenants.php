<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pesantren;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CleanupDemoTenants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired demo tenants (older than 2 hours)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of demo tenants...');

        // Find demo tenants created more than 2 hours ago
        // Assumes we have an 'is_demo' column or identify by subdomain 'demo-%'
        $expiredTenants = Pesantren::where('subdomain', 'like', 'demo-%')
            ->where('created_at', '<', Carbon::now()->subHours(1)) // 1 Hour lifespan
            ->get();

        $count = 0;

        foreach ($expiredTenants as $tenant) {
            try {
                // Delete related data first if cascade is not set in DB
                // For now assuming cascade delete or model events handle it, 
                // but for safety we trigger delete on model.
                
                $tenantName = $tenant->nama;
                $tenant->delete();
                
                $this->info("Deleted demo tenant: {$tenantName}");
                Log::info("Cleanup: Deleted demo tenant {$tenantName} ({$tenant->subdomain})");
                $count++;
            } catch (\Exception $e) {
                $this->error("Failed to delete {$tenant->nama}: " . $e->getMessage());
                Log::error("Cleanup: Failed to delete {$tenant->nama}: " . $e->getMessage());
            }
        }

        $this->info("Cleanup complete. Deleted {$count} tenants.");
    }
}
