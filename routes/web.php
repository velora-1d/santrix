<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\Auth\LoginController;

$centralDomains = config('tenancy.central_domains', []);
$mainDomain = $centralDomains[0] ?? 'santrix.my.id';

// Detect localhost environment
$isLocalhost = in_array(request()->getHost(), ['localhost', '127.0.0.1']);

/*
|--------------------------------------------------------------------------
| LOCALHOST DEVELOPMENT ROUTES (Path-based)
|--------------------------------------------------------------------------
| When running on localhost, use path-based routes instead of domain-based
| to avoid needing to configure hosts file.
*/
if ($isLocalhost) {
    // Include localhost-specific routes
    require __DIR__ . '/web-localhost.php';
    return; // Skip domain-based routes
} 

/*
|--------------------------------------------------------------------------
| 1. OWNER SUBDOMAIN ROUTES (owner.santrix.my.id)
|--------------------------------------------------------------------------
| Must be defined FIRST to avoid being captured by wildcard subdomain.
*/
Route::domain('owner.' . $mainDomain)->group(function () {
    // Auth Routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('owner.login');
    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:6,1') // SECURITY: Rate limit - 6 attempts per minute
        ->name('owner.login.post');
    


    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Security Verification Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/verify-login', [App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('login.verify');
        Route::post('/verify-login', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])->name('login.verify.check');
    });

    // Owner Dashboard Routes
    Route::middleware(['auth', 'owner'])
        ->prefix('owner') // Optional prefix, but good for clarity
        ->name('owner.')
        ->group(function () {
            Route::get('/', [App\Http\Controllers\Owner\DashboardController::class, 'index'])->name('dashboard');
            Route::get('/pesantren', [App\Http\Controllers\Owner\PesantrenController::class, 'index'])->name('pesantren.index');
            Route::get('/pesantren/{id}', [App\Http\Controllers\Owner\PesantrenController::class, 'show'])->name('pesantren.show');
            Route::get('/pesantren/{id}/edit', [App\Http\Controllers\Owner\PesantrenController::class, 'edit'])->name('pesantren.edit');
            Route::put('/pesantren/{id}', [App\Http\Controllers\Owner\PesantrenController::class, 'update'])->name('pesantren.update');
            Route::post('/pesantren/{id}/suspend', [App\Http\Controllers\Owner\PesantrenController::class, 'suspend'])->name('pesantren.suspend');
            Route::post('/pesantrens/bulk-destroy', [App\Http\Controllers\Owner\PesantrenController::class, 'bulkDestroy'])->name('pesantren.bulk-destroy');
            Route::delete('/pesantren/{id}', [App\Http\Controllers\Owner\PesantrenController::class, 'destroy'])->name('pesantren.destroy');
            
            // Withdrawal
            Route::get('/withdrawal', [App\Http\Controllers\Owner\WithdrawalController::class, 'index'])->name('withdrawal.index');
            Route::put('/withdrawal/{id}', [App\Http\Controllers\Owner\WithdrawalController::class, 'update'])->name('withdrawal.update');
            
            
            // Packages / Pricing
            Route::resource('packages', \App\Http\Controllers\Owner\PackageController::class);

            // Activity Logs
            Route::get('/logs', [App\Http\Controllers\Owner\ActivityLogController::class, 'index'])->name('logs');
            
            // Settings
            Route::get('/settings/landing-page', [App\Http\Controllers\Owner\OwnerSettingsController::class, 'landingPage'])->name('settings.landing');
            Route::post('/settings/landing-page', [App\Http\Controllers\Owner\OwnerSettingsController::class, 'updateLandingStats'])->name('settings.landing.update');
        });
    
    // Redirect root to owner dashboard if logged in, else login
    Route::get('/', function() {
        return Auth::check() ? redirect()->route('owner.dashboard') : redirect()->route('owner.login');
    });
});

/*
|--------------------------------------------------------------------------
| 2. CENTRAL DOMAIN ROUTES (santrix.my.id)
|--------------------------------------------------------------------------
*/
Route::domain($mainDomain)->group(function () use ($mainDomain) {
    // Demo Routes (Ephemeral Sandbox)
    Route::get('/demo-start/{type?}', [App\Http\Controllers\DemoController::class, 'start'])->name('demo.start');

    // Landing Page (Public) - Dynamic Data
    Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('landing');

    // Registration Routes (Central)
    Route::get('/register-pesantren', [App\Http\Controllers\Auth\RegisterTenantController::class, 'showRegistrationForm'])->name('register.tenant');
    Route::post('/register-pesantren', [App\Http\Controllers\Auth\RegisterTenantController::class, 'register'])
        ->middleware('throttle:3,10') // SECURITY: Rate limit - 3 attempts per 10 minutes
        ->name('register.tenant.store');
    
    // Central Login (Portal Selection)
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:6,1')
        ->name('login.post');
    
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
    require __DIR__ . '/tenant.php';
});
