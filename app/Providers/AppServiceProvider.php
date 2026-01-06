<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS if NOT in local environment (covers production, staging, demo)
        if (config('app.env') !== 'local') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
        
        // Paginator Style
        // Paginator Style
        \Illuminate\Pagination\Paginator::useBootstrap();

        // Manual Event Registration (Safe Mode)
        \Illuminate\Support\Facades\Event::listen(
            \App\Events\PaymentReceived::class,
            \App\Listeners\SendPaymentNotification::class,
        );
    }
}
