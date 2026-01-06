<?php

namespace App\Http\Controllers;

use App\Models\Pesantren;
use App\Models\User;
use App\Models\Subscription;
use Database\Seeders\DemoSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class DemoController extends Controller
{
    public function start(Request $request, $type = 'sekretaris')
    {
        // 1. Generate Unique ID for this Demo Session
        $demoId = strtolower(Str::random(6));
        $subdomain = 'demo-' . $demoId;
        
        // 2. Create Ephemeral Tenant
        try {
            DB::beginTransaction();

            $pesantren = Pesantren::create([
                'nama' => 'Pesantren Demo ' . strtoupper($demoId),
                'subdomain' => $subdomain,
                'domain' => $subdomain . '.' . config('app.url_base', 'santrix.my.id'),
                'status' => 'active', // Demo is always active
                'package' => 'demo',
                'expired_at' => now()->addHours(2), // Expire in 2 hours
                'trial_ends_at' => now()->addHours(2),
                'telepon' => '081234567890',
                'alamat' => 'Jl. Demo Virtual No. 1, Cloud City',
                'is_demo' => true, // Flag for cleanup
            ]);

            // 3. Create Admin User
            $user = User::create([
                'name' => 'Admin Demo',
                'email' => 'admin@' . $subdomain . '.test',
                'password' => Hash::make('password'), // Standard password
                'role' => 'admin',
                'pesantren_id' => $pesantren->id,
            ]);

            // 4. Create Dummy Subscription (so middleware doesn't block)
            Subscription::create([
                'pesantren_id' => $pesantren->id,
                'package_name' => 'Demo Enterprise',
                'price' => 0,
                'started_at' => now(),
                'expired_at' => now()->addHours(2),
                'status' => 'active',
            ]);

            // 5. Seed Dummy Data
            // We call a specific Seeder passing the pesantren_id
            $seeder = new DemoSeeder();
            $seeder->run($pesantren);

            DB::commit();

            // 6. Create Demo Login Token
            $token = Str::random(40);
            \App\Models\LoginVerification::create([
                'user_id' => $user->id,
                'token' => $token,
                'ip_address' => $request->ip(),
                'expires_at' => now()->addMinutes(5), // Token valid for 5 mins
            ]);

            // Fix Cross-Domain Redirect for Demo
            // Because Demo Controller runs on Central Domain, but Dashboard is on Tenant Subdomain
            $mainDomain = config('tenancy.central_domains')[0] ?? 'santrix.my.id';
            $tenantUrl = 'https://' . $subdomain . '.' . $mainDomain;

            // Redirect to Tenant Login Handler with Token
            return redirect()->to($tenantUrl . '/demo-login?token=' . $token . '&type=' . $type);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memulai demo: ' . $e->getMessage());
        }
    }
}
