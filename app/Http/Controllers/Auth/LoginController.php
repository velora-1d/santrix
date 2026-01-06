<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\User;

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

            // Block Owner from logging in via Tenant Subdomain
            if (app()->has('CurrentTenant') && $user->role === 'owner') {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Owner harus login melalui portal Owner.']);
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

                    // Send Email Notification
                    \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\LoginVerificationMail($token));
                    
                    \Illuminate\Support\Facades\Log::info("Login OTP sent to {$user->email}");

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
        $mainDomain = config('tenancy.central_domains')[0] ?? 'santrix.my.id';

        // 1. Owner -> Always Central Owner Dashboard
        if ($user->role === 'owner') {
            return redirect()->to('https://owner.' . $mainDomain . '/owner');
        }

        // 2. Tenant User Redirect Logic
        if ($user->pesantren_id) {
            $pesantren = $user->pesantren;
            
            if (!$pesantren) {
                Auth::logout();
                return redirect('/login')->withErrors(['email' => 'Data pesantren tidak ditemukan.']);
            }

            // Construct Tenant URL
            $tenantUrl = 'https://' . $pesantren->subdomain . '.' . $mainDomain;
            
            // Check Cross-Domain: Are we currently OUTSIDE the correct tenant domain?
            // If request host does NOT start with the user's pesantren subdomain
            $currentHost = request()->getHost();
            $expectedHostStart = $pesantren->subdomain . '.';
            
            if (!Str::startsWith($currentHost, $expectedHostStart)) {
                 // Force Absolute Redirect to Tenant Domain to avoid missing 'subdomain' param error
                $path = match($user->role) {
                    'admin' => '/admin',
                    'pendidikan' => '/pendidikan',
                    'sekretaris' => '/sekretaris',
                    'bendahara' => '/bendahara',
                    default => '/',
                };
                
                return redirect()->to($tenantUrl . $path);
            }

            // If already on correct domain, use relative Named Routes
            return match($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'pendidikan' => redirect()->route('pendidikan.dashboard'),
                'sekretaris' => redirect()->route('sekretaris.dashboard'),
                'bendahara' => redirect()->route('bendahara.dashboard'),
                default => redirect('/'),
            };
        }

        Auth::logout();
        return redirect('/login')->withErrors(['email' => 'Role user tidak valid.']);
    }
    /**
     * Handle Demo Auto-Login via Token
     */
    public function demoLogin(Request $request)
    {
        $token = $request->query('token');
        $type = $request->query('type', 'sekretaris');

        if (!$token) {
            return redirect()->route('tenant.login')->with('error', 'Token demo tidak valid.');
        }

        // Verify Token
        $verification = \App\Models\LoginVerification::where('token', $token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            return redirect()->route('tenant.login')->with('error', 'Sesi demo kadaluarsa. Silakan mulai ulang dari halam utama.');
        }

        // Login User
        $user = User::find($verification->user_id);
        
        if (!$user) {
            return redirect()->route('tenant.login');
        }

        Auth::login($user);

        // Delete used token
        $verification->delete();

        // Redirect to specific dashboard
        $path = match ($type) {
            'bendahara' => '/bendahara', 
            'pendidikan' => '/pendidikan', 
            'admin' => '/admin',
            'sekretaris' => '/sekretaris',
            default => '/sekretaris',
        };

        return redirect($path)->with('success', 'Berhasil masuk ke Mode Demo!');
    }
}
