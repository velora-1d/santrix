<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MidtransController;

$centralDomains = config('tenancy.central_domains', []);
$mainDomain = $centralDomains[0] ?? 'santrix.my.id'; 

/*
|--------------------------------------------------------------------------
| TENANT DOMAIN ROUTES (MUST BE FIRST!)
|--------------------------------------------------------------------------
| These routes MUST be registered before central/owner routes
| so Laravel checks tenant subdomains first.
|
*/

Route::middleware([\App\Http\Middleware\ResolveTenant::class])->group(function () {

    // Auth Routes (Tenant)
    Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('tenant.login');
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])
        ->middleware('throttle:6,1'); // SECURITY: Rate limit
    Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('tenant.logout');

    Route::get('/', function () {
        if (!Auth::check()) {
            return redirect()->route('tenant.login');
        }
        
        $user = Auth::user();
        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'pendidikan' => redirect()->route('pendidikan.dashboard'),
            'sekretaris' => redirect()->route('sekretaris.dashboard'),
            'bendahara' => redirect()->route('bendahara.dashboard'),
            default => redirect()->route('tenant.login'),
        };
    })->name('tenant.home');

    // Talaran Santri Module Routes
    Route::prefix('talaran')->name('talaran.')->middleware(['auth', 'role:pendidikan'])->group(function () {
        Route::get('/', [App\Http\Controllers\TalaranController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\TalaranController::class, 'store'])->name('store');
        Route::put('/{id}', [App\Http\Controllers\TalaranController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\TalaranController::class, 'destroy'])->name('destroy');
        
        // PDF Routes
        Route::get('/cetak/1-2', [App\Http\Controllers\TalaranController::class, 'cetakOneTwo'])->name('cetak.1-2');
        Route::get('/cetak/3-4', [App\Http\Controllers\TalaranController::class, 'cetakThreeFour'])->name('cetak.3-4');
        Route::get('/cetak/full', [App\Http\Controllers\TalaranController::class, 'cetakFull'])->name('cetak.full');
    });

    // Database Backup Route (PROTECTED - Admin only)
    Route::get('/backup/download', [App\Http\Controllers\BackupController::class, 'download'])
        ->middleware(['auth', 'role:admin'])
        ->name('backup.download');

    // Activity Log Route (Admin only)
    Route::get('/activity-logs', [App\Http\Controllers\ActivityLogController::class, 'index'])
        ->middleware(['auth', 'role:admin'])
        ->name('activity-logs.index');

    // Admin Dashboard Routes
    Route::prefix('admin')->middleware(['auth', 'role:admin', \App\Http\Middleware\EnsureLoginVerified::class])->name('admin.')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
        
        // Settings & User Management
        Route::get('/settings', [App\Http\Controllers\AdminController::class, 'pengaturan'])->name('settings.index');
        Route::put('/settings', [App\Http\Controllers\AdminController::class, 'updateAppSettings'])->name('settings.update');
        
        // User CRUD
        Route::post('/users', [App\Http\Controllers\AdminController::class, 'createUser'])->name('users.store');
        Route::put('/users/{id}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('users.update');

        // Kelas & Asrama Management
        Route::post('/kelas', [App\Http\Controllers\AdminController::class, 'storeKelas'])->name('kelas.store');
        Route::put('/kelas/{id}', [App\Http\Controllers\AdminController::class, 'updateKelas'])->name('kelas.update');
        Route::delete('/kelas/{id}', [App\Http\Controllers\AdminController::class, 'deleteKelas'])->name('kelas.destroy');
        
        Route::post('/asrama', [App\Http\Controllers\AdminController::class, 'storeAsrama'])->name('asrama.store');
        Route::put('/asrama/{id}', [App\Http\Controllers\AdminController::class, 'updateAsrama'])->name('asrama.update');
        Route::delete('/asrama/{id}', [App\Http\Controllers\AdminController::class, 'deleteAsrama'])->name('asrama.destroy');
        
        Route::post('/asrama/{id}/kobong', [App\Http\Controllers\AdminController::class, 'storeKobong'])->name('kobong.store');
        Route::delete('/kobong/{id}', [App\Http\Controllers\AdminController::class, 'deleteKobong'])->name('kobong.destroy');

        // Billing & Subscription
        Route::prefix('billing')->name('billing.')->group(function () {
            Route::get('/', [App\Http\Controllers\Billing\BillingController::class, 'index'])->name('index');
            Route::get('/plans', [App\Http\Controllers\Billing\BillingController::class, 'plans'])->name('plans');
            Route::post('/subscribe', [App\Http\Controllers\Billing\BillingController::class, 'subscribe'])->name('subscribe');
            Route::get('/invoices/{id}', [App\Http\Controllers\Billing\BillingController::class, 'show'])->name('show');
            Route::post('/invoices/{id}/pay', [App\Http\Controllers\Billing\BillingController::class, 'pay'])->name('pay');
        });
    });

    // Pendidikan Dashboard Routes
    Route::prefix('pendidikan')->middleware(['auth', 'role:pendidikan'])->name('pendidikan.')->group(function () {
        Route::get('/', [App\Http\Controllers\PendidikanController::class, 'dashboard'])->name('dashboard');
        
        // Nilai / Rapor Management
        Route::get('/nilai', [App\Http\Controllers\PendidikanController::class, 'nilai'])->name('nilai.index');
        Route::post('/nilai', [App\Http\Controllers\PendidikanController::class, 'storeNilai'])->name('nilai.store');
        Route::post('/nilai/bulk', [App\Http\Controllers\PendidikanController::class, 'storeNilaiBulk'])->name('nilai.store-bulk');
        Route::put('/nilai/{id}', [App\Http\Controllers\PendidikanController::class, 'updateNilai'])->name('nilai.update');
        Route::delete('/nilai/{id}', [App\Http\Controllers\PendidikanController::class, 'destroyNilai'])->name('nilai.destroy');
        Route::get('/nilai/cetak', [App\Http\Controllers\PendidikanController::class, 'cetakNilai'])->name('nilai.cetak');
        
        // Mata Pelajaran Management
        Route::get('/mapel', [App\Http\Controllers\PendidikanController::class, 'mapel'])->name('mapel.index');
        Route::post('/mapel', [App\Http\Controllers\PendidikanController::class, 'storeMapel'])->name('mapel.store');
        Route::put('/mapel/{id}', [App\Http\Controllers\PendidikanController::class, 'updateMapel'])->name('mapel.update');
        Route::delete('/mapel/{id}', [App\Http\Controllers\PendidikanController::class, 'destroyMapel'])->name('mapel.destroy');
        
        // Jadwal Pelajaran
        Route::get('/jadwal', [App\Http\Controllers\PendidikanController::class, 'jadwal'])->name('jadwal.index');
        Route::post('/jadwal', [App\Http\Controllers\PendidikanController::class, 'storeJadwal'])->name('jadwal.store');
        Route::put('/jadwal/{id}', [App\Http\Controllers\PendidikanController::class, 'updateJadwal'])->name('jadwal.update');
        Route::delete('/jadwal/{id}', [App\Http\Controllers\PendidikanController::class, 'destroyJadwal'])->name('jadwal.destroy');
        
        // Kalender Pendidikan
        Route::get('/kalender', [App\Http\Controllers\PendidikanController::class, 'kalender'])->name('kalender');
        Route::post('/kalender', [App\Http\Controllers\PendidikanController::class, 'storeKalender'])->name('kalender.store');
        Route::delete('/kalender/{id}', [App\Http\Controllers\PendidikanController::class, 'destroyKalender'])->name('kalender.destroy');

        // Ijazah
        Route::get('/ijazah', [App\Http\Controllers\PendidikanController::class, 'ijazah'])->name('ijazah');
        Route::get('/ijazah/cetak/{type}/{kelasId}', [App\Http\Controllers\PendidikanController::class, 'cetakIjazah'])->name('ijazah.cetak');
        
        // Absensi
        Route::get('/absensi', [App\Http\Controllers\PendidikanController::class, 'absensi'])->name('absensi');
        Route::post('/absensi', [App\Http\Controllers\PendidikanController::class, 'storeAbsensi'])->name('absensi.store');
    });

    // Sekretaris Dashboard Routes
    Route::prefix('sekretaris')->middleware(['auth', 'role:sekretaris'])->name('sekretaris.')->group(function () {
        Route::get('/', [App\Http\Controllers\SekretarisController::class, 'dashboard'])->name('dashboard');
        
        // Santri Management
        Route::get('/santri', [App\Http\Controllers\SekretarisController::class, 'index'])->name('santri.index');
        Route::get('/santri/create', [App\Http\Controllers\SekretarisController::class, 'create'])->name('santri.create');
        Route::post('/santri', [App\Http\Controllers\SekretarisController::class, 'store'])->name('santri.store');
        Route::get('/santri/{id}', [App\Http\Controllers\SekretarisController::class, 'show'])->name('santri.show');
        Route::get('/santri/{id}/edit', [App\Http\Controllers\SekretarisController::class, 'edit'])->name('santri.edit');
        Route::put('/santri/{id}', [App\Http\Controllers\SekretarisController::class, 'updateSantri'])->name('santri.update');
        Route::delete('/santri/{id}', [App\Http\Controllers\SekretarisController::class, 'destroy'])->name('santri.destroy');
        
        // Mutasi Santri
        Route::get('/mutasi', [App\Http\Controllers\SekretarisController::class, 'mutasiSantri'])->name('mutasi');
        Route::post('/mutasi', [App\Http\Controllers\SekretarisController::class, 'storeMutasi'])->name('mutasi.store');
        
        // Kenaikan Kelas
        Route::get('/kenaikan-kelas', [App\Http\Controllers\SekretarisBulkController::class, 'showKenaikanKelas'])->name('kenaikan-kelas');
        Route::post('/kenaikan-kelas', [App\Http\Controllers\SekretarisBulkController::class, 'processKenaikanKelas'])->name('kenaikan-kelas.process');
    });

    // Bendahara Dashboard Routes
    Route::prefix('bendahara')->middleware(['auth', 'role:bendahara'])->name('bendahara.')->group(function () {
        Route::get('/', [App\Http\Controllers\BendaharaController::class, 'dashboard'])->name('dashboard');
        
        // Keuangan Management
        Route::get('/keuangan', [App\Http\Controllers\BendaharaController::class, 'index'])->name('keuangan.index');
        Route::post('/keuangan', [App\Http\Controllers\BendaharaController::class, 'store'])->name('keuangan.store');
        Route::put('/keuangan/{id}', [App\Http\Controllers\BendaharaController::class, 'update'])->name('keuangan.update');
        Route::delete('/keuangan/{id}', [App\Http\Controllers\BendaharaController::class, 'destroy'])->name('keuangan.destroy');
        
        // Syahriah/SPP
        Route::get('/syahriah', [App\Http\Controllers\BendaharaController::class, 'syahriah'])->name('syahriah.index');
        Route::post('/syahriah/generate', [App\Http\Controllers\BendaharaController::class, 'generateSyahriah'])->name('syahriah.generate');
        Route::post('/syahriah/{id}/pay', [App\Http\Controllers\BendaharaController::class, 'paySyahriah'])->name('syahriah.pay');
        
        // Laporan
        Route::get('/laporan', [App\Http\Controllers\BendaharaController::class, 'laporan'])->name('laporan.index');
        Route::get('/laporan/pdf', [App\Http\Controllers\BendaharaController::class, 'generatePdf'])->name('laporan.pdf');
    });

    // Shared Routes for All Authenticated Tenant Users
    Route::middleware(['auth'])->group(function () {
        // Billing/Blast - ADVANCE ONLY
        Route::middleware(['package:advance', 'role:bendahara'])->group(function () {
            Route::get('/billing/targets', [App\Http\Controllers\BillingController::class, 'getTargets'])->name('billing.targets');
            Route::post('/billing/send', [App\Http\Controllers\BillingController::class, 'sendSingleReminder'])->name('billing.send');
        });

        // Kartu Digital Syahriah - ADVANCE ONLY
        Route::middleware(['package:advance', 'role:sekretaris'])->group(function () {
            Route::get('/kartu-digital', [App\Http\Controllers\KartuDigitalController::class, 'index'])->name('kartu-digital');
            Route::get('/kartu-digital/{id}/download', [App\Http\Controllers\KartuDigitalController::class, 'downloadPdf'])->name('kartu-digital.download');
        });

        // Midtrans VA Generation - ADVANCE ONLY
        Route::middleware(['package:advance'])->group(function () {
            Route::post('/santri/{santri}/generate-va', [App\Http\Controllers\MidtransController::class, 'generateVa'])->name('santri.generate-va');
            Route::post('/santri/generate-va-bulk', [App\Http\Controllers\MidtransController::class, 'generateVaBulk'])->name('santri.generate-va-bulk');
        });
    });

    // Webhooks
    Route::post('/midtrans/webhook', [App\Http\Controllers\MidtransController::class, 'webhook'])->name('midtrans.webhook');
    Route::post('/api/midtrans/callback', [App\Http\Controllers\MidtransController::class, 'webhook'])->name('midtrans.callback');

});

/*
|--------------------------------------------------------------------------
| CENTRAL DOMAIN ROUTES (LANDING & OWNER)
|--------------------------------------------------------------------------
| These routes are only accessible from the central domains (e.g. santrix.my.id)
|
*/ 

// 1. OWNER SUBDOMAIN (owner.santrix.my.id)
Route::domain('owner.' . $mainDomain)->group(function () {
    // Auth Routes
    // Auth Routes
    Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])
        ->middleware('throttle:6,1') // SECURITY: Rate limit - 6 attempts per minute
        ->name('login.post');
    Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

    // Security Verification Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/verify-login', [App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('login.verify');
        Route::post('/verify-login', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])->name('login.verify.check');
    });

    // Owner Dashboard Routes
    Route::middleware(['auth', 'owner', \App\Http\Middleware\EnsureLoginVerified::class])
        ->prefix('owner') // Optional prefix, but good for clarity
        ->name('owner.')
        ->group(function () {
            Route::get('/', [App\Http\Controllers\Owner\DashboardController::class, 'index'])->name('dashboard');
            Route::get('/pesantren', [App\Http\Controllers\Owner\PesantrenController::class, 'index'])->name('pesantren.index');
            Route::get('/pesantren/{id}', [App\Http\Controllers\Owner\PesantrenController::class, 'show'])->name('pesantren.show');
            Route::get('/pesantren/{id}/edit', [App\Http\Controllers\Owner\PesantrenController::class, 'edit'])->name('pesantren.edit');
            Route::put('/pesantren/{id}', [App\Http\Controllers\Owner\PesantrenController::class, 'update'])->name('pesantren.update');
            Route::post('/pesantren/{id}/suspend', [App\Http\Controllers\Owner\PesantrenController::class, 'suspend'])->name('pesantren.suspend');
            
            // Withdrawal
            Route::get('/withdrawal', [App\Http\Controllers\Owner\WithdrawalController::class, 'index'])->name('withdrawal.index');
            Route::put('/withdrawal/{id}', [App\Http\Controllers\Owner\WithdrawalController::class, 'update'])->name('withdrawal.update');
            
            // Activity Logs
            Route::get('/logs', [App\Http\Controllers\Owner\ActivityLogController::class, 'index'])->name('logs');
        });
    
    // Redirect root to owner dashboard if logged in, else login
    Route::get('/', function() {
        return Auth::check() ? redirect()->route('owner.dashboard') : redirect()->route('login');
    });
});

// 2. MAIN LANDING DOMAIN (santrix.my.id)
Route::domain($mainDomain)->group(function () use ($mainDomain) {
    // Landing Page (Public) - Dynamic Data
    Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('landing');

    // Registration Routes (Central)
    Route::get('/register-pesantren', [App\Http\Controllers\Auth\RegisterTenantController::class, 'showRegistrationForm'])->name('register.tenant');
    Route::post('/register-pesantren', [App\Http\Controllers\Auth\RegisterTenantController::class, 'register'])
        ->middleware('throttle:3,10') // SECURITY: Rate limit - 3 attempts per 10 minutes
        ->name('register.tenant.store');
    
    // Redirect Login to Owner Domain
    Route::get('/login', function() use ($mainDomain) {
        return redirect()->to('https://owner.' . $mainDomain . '/login');
    })->name('login.redirect');
    
    // Auth Routes for Central (Logout Only)
    Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout.redirect');
});
