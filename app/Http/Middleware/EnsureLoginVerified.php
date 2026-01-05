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

        // Hanya cek untuk role Owner/Admin (Sesuaikan dengan logic role di project Anda)
        // Misal: if ($user->role !== 'admin' && $user->role !== 'owner') return $next($request);
        
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
        return redirect()->route('login.verify');
    }
}
