<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TrustedDevice;
use Symfony\Component\HttpFoundation\Response;

class EnsureLoginVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // BYPASS: If Pesantren is Demo, skip verification
        if ($user->pesantren && ($user->pesantren->is_demo || $user->pesantren->package === 'demo')) {
            return $next($request);
        }

        // Only enforce for Owner and Admin
        if (!in_array($user->role, ['owner', 'admin'])) {
            return $next($request);
        }
        
        // JIKA sudah verified di session, lanjut
        if ($request->session()->has('auth.verified')) {
            return $next($request);
        }
        
        // JIKA Device Trusted & Valid
        $deviceHash = hash_hmac('sha256', $request->ip() . $request->userAgent(), config('app.key'));
        
        $trustedDevice = TrustedDevice::where('user_id', $user->id)
            ->where('device_hash', $deviceHash)
            ->where('expires_at', '>', now())
            ->first();

        if ($trustedDevice) {
            // Update last used
            $trustedDevice->update(['last_used_at' => now()]);
            // Set session verified
            $request->session()->put('auth.verified', true);
            return $next($request);
        }

        // Jika tidak trusted, redirect ke verifikasi
        if (app()->has('CurrentTenant')) {
             return redirect()->route('tenant.verification.notice');
        }
        return redirect()->route('login.verify');
    }
}
