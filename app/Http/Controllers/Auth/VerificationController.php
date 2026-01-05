<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginVerification;
use App\Models\TrustedDevice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    public function show()
    {
        return view('auth.verify-login');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'token' => 'required|string|size:6',
        ]);

        $user = Auth::user();

        // Cari token valid terakhir
        $verification = LoginVerification::where('user_id', $user->id)
            ->where('token', $request->token)
            ->where('expires_at', '>', Carbon::now())
            ->whereNull('verified_at')
            ->first();

        if (!$verification) {
            return back()->withErrors(['token' => 'Kode verifikasi salah atau sudah kadaluarsa.']);
        }

        // Tandai verified
        $verification->update(['verified_at' => Carbon::now()]);

        // Buat Device Hash
        $deviceHash = hash_hmac('sha256', $request->ip() . $request->userAgent(), config('app.key'));

        // Simpan sebagai Trusted Device (30 hari)
        TrustedDevice::create([
            'user_id' => $user->id,
            'device_hash' => $deviceHash,
            'last_used_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addDays(30),
        ]);

        // Set session variable untuk bypass middleware
        $request->session()->put('auth.verified', true);

        return redirect()->intended('/dashboard'); // Ganti dengan route home yang sesuai
    }
}
