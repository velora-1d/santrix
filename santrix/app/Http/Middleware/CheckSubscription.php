<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  $requiredPackage
     */
    public function handle(Request $request, Closure $next, ?string $requiredPackage = null): Response
    {
        $user = Auth::user();
        
        // Skip for platform owner (central level, no pesantren_id) or if not logged in
        if (!$user || ($user->role === 'owner' && !$user->pesantren_id)) {
            return $next($request);
        }

        // Fix: Do not run subscription checks on Central/Owner domains
        if (!app()->has('CurrentTenant')) {
             return $next($request);
        }

        $pesantren = $request->get('pesantren'); // Assuming pesantren is injected by tenant resolver or available in request
        
        // If pesantren data not in request, try to get from user
        if (!$pesantren && $user->pesantren_id) {
            $pesantren = \App\Models\Pesantren::find($user->pesantren_id);
        }

        if (!$pesantren) {
            return $next($request);
        }

        // 1. Allow access to billing routes always (so they can pay)
        if ($request->is('admin/billing*') || $request->routeIs('admin.billing.*')) {
            return $next($request);
        }

        // 2. Check if still in trial period
        if ($pesantren->trial_ends_at && Carbon::parse($pesantren->trial_ends_at)->isFuture()) {
            // Still in trial, allow access
            return $next($request);
        }

        // 3. Real-time Expiration Check
        // BYPASS for Demo Package to prevent timezone/session issues
        if ($pesantren->package === 'demo') {
            return $next($request);
        }

        $isExpired = !$pesantren->expired_at || Carbon::parse($pesantren->expired_at)->isPast();
        
        if ($isExpired) {
            return redirect()->route('admin.billing.index')->with('warning', 'Masa aktif langganan Anda telah habis. Silakan lakukan perpanjangan.');
        }

        // 4. Package Gating (check if advance features required)
        if ($requiredPackage === 'advance') {
            // Check if user has any advance package (advance-3 or advance-6)
            if (!str_starts_with($pesantren->package, 'advance')) {
                return redirect()->route('admin.billing.plans')->with('error', 'Fitur ini hanya tersedia pada paket ADVANCE. Silakan upgrade paket Anda.');
            }
        }

        return $next($request);
    }
}
