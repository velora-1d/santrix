<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MidtransController;

$centralDomains = config('tenancy.central_domains', []);
$mainDomain = $centralDomains[0] ?? 'santrix.my.id'; 

/*
|--------------------------------------------------------------------------
| 1. OWNER SUBDOMAIN ROUTES (owner.santrix.my.id)
|--------------------------------------------------------------------------
| Must be defined FIRST to avoid being captured by wildcard subdomain.
*/
Route::domain('owner.' . $mainDomain)->group(function () {
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
            Route::delete('/pesantren/{id}', [App\Http\Controllers\Owner\PesantrenController::class, 'destroy'])->name('pesantren.destroy');
            
            // Withdrawal
            Route::get('/withdrawal', [App\Http\Controllers\Owner\WithdrawalController::class, 'index'])->name('withdrawal.index');
            Route::put('/withdrawal/{id}', [App\Http\Controllers\Owner\WithdrawalController::class, 'update'])->name('withdrawal.update');
            
            // Packages / Pricing
            Route::resource('packages', \App\Http\Controllers\Owner\PackageController::class);

            // Activity Logs
            Route::get('/logs', [App\Http\Controllers\Owner\ActivityLogController::class, 'index'])->name('logs');
        });
    
    // Redirect root to owner dashboard if logged in, else login
    Route::get('/', function() {
        return Auth::check() ? redirect()->route('owner.dashboard') : redirect()->route('login');
    });
});

/*
|--------------------------------------------------------------------------
| 2. CENTRAL DOMAIN ROUTES (santrix.my.id)
|--------------------------------------------------------------------------
*/
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

/*
|--------------------------------------------------------------------------
| 3. TENANT DOMAIN ROUTES (Wildcard)
|--------------------------------------------------------------------------
| Must be LAST to allow specific domains to match first.
*/

Route::domain('{subdomain}.' . $mainDomain)->middleware([\App\Http\Middleware\ResolveTenant::class])->group(function () {

    // Auth Routes (Tenant)
    Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('tenant.login');
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])
        ->middleware('throttle:6,1'); // SECURITY: Rate limit
    Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('tenant.logout');

    // Verification Routes (Tenant)
    Route::middleware(['auth'])->group(function () {
        Route::get('/verify-login', [App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('tenant.verification.notice');
        Route::post('/verify-login', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])->name('tenant.verification.verify');
    });

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
        
        // Settings & User Management - Uses AdminController which has the comprehensive pengaturan view
        Route::get('/settings', [App\Http\Controllers\AdminController::class, 'pengaturan'])->name('pengaturan');
        Route::post('/settings', [App\Http\Controllers\Admin\PesantrenSettingsController::class, 'update'])->name('pengaturan.update');
        
        // Branding (Custom Theme/Logo)
        Route::get('/branding', [App\Http\Controllers\Admin\PesantrenSettingsController::class, 'branding'])->name('branding');
        Route::post('/branding', [App\Http\Controllers\Admin\PesantrenSettingsController::class, 'updateBranding'])->name('branding.update');

        // Settings File Uploads (Logo, Signature, Logo Pendidikan)
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::post('/logo', [App\Http\Controllers\Admin\PesantrenSettingsController::class, 'uploadLogo'])->name('logo');
            Route::delete('/logo', [App\Http\Controllers\Admin\PesantrenSettingsController::class, 'deleteLogo'])->name('logo.delete');
            Route::post('/signature', [App\Http\Controllers\Admin\PesantrenSettingsController::class, 'uploadSignature'])->name('signature');
            Route::post('/logo-pendidikan', [App\Http\Controllers\Admin\PesantrenSettingsController::class, 'uploadLogoPendidikan'])->name('logo-pendidikan');
            Route::delete('/logo-pendidikan', [App\Http\Controllers\Admin\PesantrenSettingsController::class, 'deleteLogoPendidikan'])->name('logo-pendidikan.delete');
        });

        // Pengaturan sub-routes (Kelas, Asrama, User CRUD, App settings)
        Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
            // Kelas CRUD
            Route::post('/kelas', [App\Http\Controllers\AdminController::class, 'storeKelas'])->name('kelas.store');
            Route::put('/kelas/{id}', [App\Http\Controllers\AdminController::class, 'updateKelas'])->name('kelas.update');
            Route::delete('/kelas/{id}', [App\Http\Controllers\AdminController::class, 'deleteKelas'])->name('kelas.delete');
            // Asrama CRUD
            Route::post('/asrama', [App\Http\Controllers\AdminController::class, 'storeAsrama'])->name('asrama.store');
            Route::put('/asrama/{id}', [App\Http\Controllers\AdminController::class, 'updateAsrama'])->name('asrama.update');
            Route::delete('/asrama/{id}', [App\Http\Controllers\AdminController::class, 'deleteAsrama'])->name('asrama.delete');
            // User CRUD
            Route::post('/user', [App\Http\Controllers\AdminController::class, 'createUser'])->name('user.create');
            Route::put('/user/{id}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('user.update');
            Route::delete('/user/{id}', [App\Http\Controllers\BackupController::class, 'destroyUser'])->name('user.delete');
            // App settings
            Route::post('/app', [App\Http\Controllers\AdminController::class, 'updateAppSettings'])->name('app');
        });

        // Activity Logs
        Route::get('/activity-log', [App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-log');

        // User CRUD
        Route::post('/users', [App\Http\Controllers\AdminController::class, 'createUser'])->name('users.store');
        Route::put('/users/{id}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('users.update');

        // Kelas & Asrama Management (API endpoints called by Settings page)
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
            Route::get('/', [App\Http\Controllers\BillingController::class, 'index'])->name('index'); // Changed controller to tenant BillingController
            Route::get('/plans', [App\Http\Controllers\BillingController::class, 'plans'])->name('plans');
            Route::post('/subscribe', [App\Http\Controllers\BillingController::class, 'subscribe'])->name('subscribe');
            Route::get('/invoices/{id}', [App\Http\Controllers\BillingController::class, 'show'])->name('show');
            Route::post('/invoices/{id}/pay', [App\Http\Controllers\BillingController::class, 'pay'])->name('pay');
        });

        // Withdrawal Saldo (Tenant withdrawal request)
        Route::prefix('withdrawal')->name('withdrawal.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\WithdrawalController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\Admin\WithdrawalController::class, 'store'])->name('store');
            Route::post('/update-bank', [App\Http\Controllers\Admin\WithdrawalController::class, 'updateBank'])->name('update-bank');
        });
    });

    // Pendidikan Dashboard Routes
    Route::prefix('pendidikan')->middleware(['auth', 'role:pendidikan'])->name('pendidikan.')->group(function () {
        Route::get('/', [App\Http\Controllers\PendidikanController::class, 'dashboard'])->name('dashboard');
        
        // Nilai / Rapor Management
        Route::get('/nilai', [App\Http\Controllers\PendidikanController::class, 'nilai'])->name('nilai.index'); // Renamed from 'nilai'
        Route::post('/nilai', [App\Http\Controllers\PendidikanController::class, 'storeNilai'])->name('nilai.store');
        Route::post('/nilai/bulk', [App\Http\Controllers\PendidikanController::class, 'storeNilaiBulk'])->name('nilai.store-bulk');
        Route::put('/nilai/{id}', [App\Http\Controllers\PendidikanController::class, 'updateNilai'])->name('nilai.update');
        Route::delete('/nilai/{id}', [App\Http\Controllers\PendidikanController::class, 'destroyNilai'])->name('nilai.destroy');
        Route::get('/nilai/cetak', [App\Http\Controllers\PendidikanController::class, 'cetakNilai'])->name('nilai.cetak');
        
        // Mata Pelajaran Management
        Route::get('/mapel', [App\Http\Controllers\PendidikanController::class, 'mapel'])->name('mapel.index'); // Renamed from 'mapel'
        Route::post('/mapel', [App\Http\Controllers\PendidikanController::class, 'storeMapel'])->name('mapel.store');
        Route::put('/mapel/{id}', [App\Http\Controllers\PendidikanController::class, 'updateMapel'])->name('mapel.update');
        Route::delete('/mapel/{id}', [App\Http\Controllers\PendidikanController::class, 'destroyMapel'])->name('mapel.destroy');
        
        // Jadwal Pelajaran
        Route::get('/jadwal', [App\Http\Controllers\PendidikanController::class, 'jadwal'])->name('jadwal.index'); // Renamed from 'jadwal'
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
        
        // Laporan (E-Rapor)
        Route::get('/laporan', [App\Http\Controllers\PendidikanController::class, 'laporan'])->name('laporan');

        // Ujian Mingguan
        Route::get('/ujian-mingguan', [App\Http\Controllers\UjianMingguanController::class, 'index'])->name('ujian-mingguan');
        Route::post('/ujian-mingguan', [App\Http\Controllers\UjianMingguanController::class, 'store'])->name('ujian-mingguan.store');
        Route::put('/ujian-mingguan/{id}', [App\Http\Controllers\UjianMingguanController::class, 'update'])->name('ujian-mingguan.update');
        Route::delete('/ujian-mingguan/{id}', [App\Http\Controllers\UjianMingguanController::class, 'destroy'])->name('ujian-mingguan.destroy');

        // Sistem Talaran
        Route::get('/talaran', [App\Http\Controllers\TalaranController::class, 'index'])->name('talaran.index');
        Route::post('/talaran', [App\Http\Controllers\TalaranController::class, 'store'])->name('talaran.store');
        Route::put('/talaran/{id}', [App\Http\Controllers\TalaranController::class, 'update'])->name('talaran.update');
        Route::delete('/talaran/{id}', [App\Http\Controllers\TalaranController::class, 'destroy'])->name('talaran.destroy');
        Route::get('/talaran/cetak-1-2', [App\Http\Controllers\TalaranController::class, 'cetakOneTwo'])->name('talaran.cetak-1-2');
        Route::get('/talaran/cetak-3-4', [App\Http\Controllers\TalaranController::class, 'cetakThreeFour'])->name('talaran.cetak-3-4');
        Route::get('/talaran/cetak-full', [App\Http\Controllers\TalaranController::class, 'cetakFull'])->name('talaran.cetak-full');
    });

    // Sekretaris Dashboard Routes
    Route::prefix('sekretaris')->middleware(['auth', 'role:sekretaris'])->name('sekretaris.')->group(function () {
        Route::get('/', [App\Http\Controllers\SekretarisController::class, 'dashboard'])->name('dashboard');
        
        // Santri Management
        Route::get('/data-santri', [App\Http\Controllers\SekretarisController::class, 'dataSantri'])->name('data-santri');
        Route::get('/data-santri/create', [App\Http\Controllers\SekretarisController::class, 'createSantri'])->name('data-santri.create');
        Route::post('/data-santri', [App\Http\Controllers\SekretarisController::class, 'storeSantri'])->name('data-santri.store');
        Route::get('/data-santri/{id}', [App\Http\Controllers\SekretarisController::class, 'showSantri'])->name('data-santri.show');
        Route::get('/data-santri/{id}/edit', [App\Http\Controllers\SekretarisController::class, 'editSantri'])->name('data-santri.edit');
        Route::put('/data-santri/{id}', [App\Http\Controllers\SekretarisController::class, 'updateSantri'])->name('data-santri.update');
        Route::delete('/data-santri/{id}', [App\Http\Controllers\SekretarisController::class, 'deactivateSantri'])->name('data-santri.destroy');
        Route::delete('/data-santri/{id}/deactivate', [App\Http\Controllers\SekretarisController::class, 'deactivateSantri'])->name('data-santri.deactivate');
        
        // Santri Template & Import
        Route::get('/data-santri/template-excel', [App\Http\Controllers\SekretarisController::class, 'downloadTemplateExcel'])->name('data-santri.template-excel');
        Route::get('/data-santri/template-csv', [App\Http\Controllers\SekretarisController::class, 'downloadTemplateCsv'])->name('data-santri.template-csv');
        Route::post('/data-santri/import', [App\Http\Controllers\SekretarisController::class, 'importSantri'])->name('data-santri.import');
        
        // VA Bulk Operations (ADVANCE PACKAGE ONLY)
        Route::post('/data-santri/generate-va-bulk', [App\Http\Controllers\SekretarisController::class, 'generateVaBulk'])->name('data-santri.generate-va-bulk');
        Route::post('/data-santri/reset-va-bulk', [App\Http\Controllers\SekretarisController::class, 'resetVaBulk'])->name('data-santri.reset-va-bulk');
        
        // Kartu Digital (NEW)
        Route::get('/kartu-digital', [App\Http\Controllers\KartuDigitalController::class, 'index'])->name('kartu-digital');

        // Mutasi Santri
        Route::get('/mutasi-santri', [App\Http\Controllers\SekretarisController::class, 'mutasiSantri'])->name('mutasi-santri');
        Route::post('/mutasi-santri', [App\Http\Controllers\SekretarisController::class, 'storeMutasi'])->name('mutasi-santri.store');
        
        // Kenaikan Kelas
        Route::get('/kenaikan-kelas', [App\Http\Controllers\SekretarisBulkController::class, 'showKenaikanKelas'])->name('kenaikan-kelas');
        Route::post('/kenaikan-kelas', [App\Http\Controllers\SekretarisBulkController::class, 'processKenaikanKelas'])->name('kenaikan-kelas.process');
        
        // Perpindahan
        Route::get('/perpindahan', [App\Http\Controllers\SekretarisController::class, 'mutasiSantri'])->name('perpindahan');

        // Laporan
        Route::get('/laporan', [App\Http\Controllers\SekretarisController::class, 'laporan'])->name('laporan');
    });

    // Bendahara Dashboard Routes
    Route::prefix('bendahara')->middleware(['auth', 'role:bendahara'])->name('bendahara.')->group(function () {
        Route::get('/', [App\Http\Controllers\BendaharaController::class, 'dashboard'])->name('dashboard');
        
        // Syahriah/SPP
        Route::get('/syahriah', [App\Http\Controllers\BendaharaController::class, 'syahriah'])->name('syahriah');
        Route::get('/tunggakan', [App\Http\Controllers\BendaharaController::class, 'syahriah'])->name('cek-tunggakan'); // Use correct name 'cek-tunggakan' as requested
        Route::post('/syahriah/generate', [App\Http\Controllers\BendaharaController::class, 'generateSyahriah'])->name('syahriah.generate');
        Route::post('/syahriah/{id}/pay', [App\Http\Controllers\BendaharaController::class, 'paySyahriah'])->name('syahriah.pay');
        Route::post('/syahriah', [App\Http\Controllers\BendaharaController::class, 'storeSyahriah'])->name('syahriah.store');
        Route::put('/syahriah/{id}', [App\Http\Controllers\BendaharaController::class, 'updateSyahriah'])->name('syahriah.update');
        Route::delete('/syahriah/{id}', [App\Http\Controllers\BendaharaController::class, 'destroySyahriah'])->name('syahriah.destroy');        

        // Pemasukan
        Route::get('/pemasukan', [App\Http\Controllers\BendaharaController::class, 'pemasukan'])->name('pemasukan');
        Route::post('/pemasukan', [App\Http\Controllers\BendaharaController::class, 'storePemasukan'])->name('pemasukan.store');
        Route::put('/pemasukan/{id}', [App\Http\Controllers\BendaharaController::class, 'updatePemasukan'])->name('pemasukan.update');
        Route::delete('/pemasukan/{id}', [App\Http\Controllers\BendaharaController::class, 'destroyPemasukan'])->name('pemasukan.destroy');

        // Pengeluaran
        Route::get('/pengeluaran', [App\Http\Controllers\BendaharaController::class, 'pengeluaran'])->name('pengeluaran');
        Route::post('/pengeluaran', [App\Http\Controllers\BendaharaController::class, 'storePengeluaran'])->name('pengeluaran.store');
        Route::put('/pengeluaran/{id}', [App\Http\Controllers\BendaharaController::class, 'updatePengeluaran'])->name('pengeluaran.update');
        Route::delete('/pengeluaran/{id}', [App\Http\Controllers\BendaharaController::class, 'destroyPengeluaran'])->name('pengeluaran.destroy');

        // Pegawai
        Route::get('/pegawai', [App\Http\Controllers\BendaharaController::class, 'pegawai'])->name('pegawai');
        Route::post('/pegawai', [App\Http\Controllers\BendaharaController::class, 'storePegawai'])->name('pegawai.store');
        Route::put('/pegawai/{id}', [App\Http\Controllers\BendaharaController::class, 'updatePegawai'])->name('pegawai.update');
        Route::delete('/pegawai/{id}', [App\Http\Controllers\BendaharaController::class, 'destroyPegawai'])->name('pegawai.destroy');

        // Gaji
        Route::get('/gaji', [App\Http\Controllers\BendaharaController::class, 'gaji'])->name('gaji');
        Route::post('/gaji', [App\Http\Controllers\BendaharaController::class, 'storeGaji'])->name('gaji.store');
        Route::put('/gaji/{id}', [App\Http\Controllers\BendaharaController::class, 'updateGaji'])->name('gaji.update');

        // Laporan
        Route::get('/laporan', [App\Http\Controllers\BendaharaController::class, 'laporan'])->name('laporan');
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

    // Redirect /owner to central owner dashboard
    Route::get('/owner', function() {
        return redirect()->to('https://owner.' . config('tenancy.central_domains')[0] . '/owner');
    })->name('tenant.owner.redirect');

});
