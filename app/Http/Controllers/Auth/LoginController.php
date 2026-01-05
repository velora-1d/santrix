<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        // If already logged in, redirect to appropriate dashboard
        if (Auth::check()) {
            return $this->redirectToDashboard();
        }

        // Check if accessing from Central Domain (No Tenant)
        // Adjust check based on your tenancy setup, but usually !app('CurrentTenant') works
        // or check if request host is in config('tenancy.central_domains')
        
        $isTenant = app()->has('CurrentTenant');

        if (!$isTenant) {
            return view('auth.login-central');
        }
        
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        // 1. Rate Limiting (Prevent Brute Force)
        // Key: ip + email to prevent single-account attack from multiple IPs? 
        // Or simply throttle by IP for DDoS prevention, and throttle by email for account lock.
        // Let's us throttleKey based on email + IP.
        
        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . $seconds . ' detik.',
            ])->onlyInput('email');
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (app()->has('CurrentTenant')) {
            $credentials['pesantren_id'] = app('CurrentTenant')->id;
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            RateLimiter::clear($throttleKey); // Clear hits on success

            $user = Auth::user();

            // Tenant Safety Check
            if (app()->has('CurrentTenant') && $user->pesantren_id !== app('CurrentTenant')->id) {
                Auth::logout();
                return back()->withErrors(['email' => 'User tidak terdaftar di pesantren ini.']);
            }

            // 2. CHECK SECURITY VERIFICATION (Owner & Admin Only)
            if (in_array($user->role, ['owner', 'admin'])) {
                // Check TrustedDevice
                $deviceHash = hash_hmac('sha256', $request->ip() . $request->userAgent(), config('app.key'));
                
                $isTrusted = \App\Models\TrustedDevice::where('user_id', $user->id)
                    ->where('device_hash', $deviceHash)
                    ->where('expires_at', '>', now())
                    ->exists();

                if (!$isTrusted) {
                    // Generate OTP
                    $token = strtoupper(Str::random(6)); // Simple alnum or numeric
                    
                    // For better UX, let's use numeric 6 digit
                    $token = (string) random_int(100000, 999999);

                    \App\Models\LoginVerification::create([
                        'user_id' => $user->id,
                        'token' => $token,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'expires_at' => now()->addMinutes(15),
                    ]);

                    // TODO_MUST: Send Email Notification Here
                    // Mail::to($user->email)->send(new LoginVerificationMail($token));
                    // For now, let's log it only if no mail setup yet, but we should setup mail.
                    \Illuminate\Support\Facades\Log::info("Login OTP for {$user->email}: $token");

                    return redirect()->route('login.verify');
                }
            }

            return $this->redirectToDashboard();
        }

        // Increment failed attempts
        RateLimiter::hit($throttleKey, 60); // 1 minute decay

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Redirect to dashboard based on user role
     */
    protected function redirectToDashboard()
    {
        $user = Auth::user();
        
        return match($user->role) {
            'owner' => redirect('/owner'),
            'admin' => redirect()->route('admin.dashboard'),
            'pendidikan' => redirect()->route('pendidikan.dashboard'),
            'sekretaris' => redirect()->route('sekretaris.dashboard'),
            'bendahara' => redirect()->route('bendahara.dashboard'),
            default => redirect('/login'),
        };
    }
}
