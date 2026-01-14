<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Trust ALL proxies (Cloudflare, Ngrok, Load Balancer) to fix HTTPS detection
        $middleware->trustProxies(at: '*');

        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \App\Http\Middleware\ResolveTenant::class,
            \App\Http\Middleware\CheckSubscription::class,
            \App\Http\Middleware\SecurityHeaders::class, // Added Security Headers
        ]);
        $middleware->alias([
            'owner' => \App\Http\Middleware\OwnerMiddleware::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'subscription' => \App\Http\Middleware\CheckSubscription::class,
            'package' => \App\Http\Middleware\RequiresPackage::class, // SECURITY: Package gating
        ]);
        
        // Redirect guests to /login using URL instead of route to avoid central_domain parameter issue
        $middleware->redirectGuestsTo(fn () => url('/login'));
        
        // Exclude webhook routes from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'midtrans/webhook',
            'api/midtrans/callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
