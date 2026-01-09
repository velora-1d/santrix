<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| LOCALHOST DEVELOPMENT ROUTES (Path-based)
|--------------------------------------------------------------------------
| These routes mirror the domain-based routes in web.php but use path
| prefixes instead of domains to work on localhost without hosts file setup.
|
| Production: owner.santrix.my.id/dashboard
| Localhost:  localhost:8000/owner/dashboard
*/

/*
|--------------------------------------------------------------------------
| 1. OWNER ROUTES (/owner/*)
|--------------------------------------------------------------------------
*/
Route::prefix('owner')->name('owner.')->group(function () {
    // Auth Routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:6,1')
        ->name('login.post');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Security Verification Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/verify-login', [App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('login.verify');
        Route::post('/verify-login', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])->name('login.verify.check');
    });

    // Owner Dashboard Routes
    Route::middleware(['auth', 'owner', \App\Http\Middleware\EnsureLoginVerified::class])->group(function () {
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
        
        // Settings
        Route::get('/settings/landing-page', [App\Http\Controllers\Owner\OwnerSettingsController::class, 'landingPage'])->name('settings.landing');
        Route::post('/settings/landing-page', [App\Http\Controllers\Owner\OwnerSettingsController::class, 'updateLandingStats'])->name('settings.landing.update');
    });
});

/*
|--------------------------------------------------------------------------
| 2. CENTRAL/LANDING ROUTES (Root)
|--------------------------------------------------------------------------
*/
// Demo Routes
Route::get('/demo-start/{type?}', [App\Http\Controllers\DemoController::class, 'start'])->name('demo.start');

// Landing Page
Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('landing');

// Registration Routes
Route::get('/register-pesantren', [App\Http\Controllers\Auth\RegisterTenantController::class, 'showRegistrationForm'])->name('register.tenant');
Route::post('/register-pesantren', [App\Http\Controllers\Auth\RegisterTenantController::class, 'register'])
    ->middleware('throttle:3,10')
    ->name('register.tenant.store');

// Central Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])
    ->middleware('throttle:6,1')
    ->name('login.post');

// Central Login Verification
Route::middleware(['auth'])->group(function () {
    Route::get('/verify-login', [App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('login.verify');
    Route::post('/verify-login', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])->name('login.verify.check');
});

Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout.redirect');

/*
|--------------------------------------------------------------------------
| 3. TENANT ROUTES (/tenant/*)
|--------------------------------------------------------------------------
| For localhost testing, tenant routes are prefixed with /tenant
| In production, these would be on subdomain.santrix.my.id
*/
Route::prefix('tenant')->group(function () {
    require __DIR__ . '/tenant.php';
});
