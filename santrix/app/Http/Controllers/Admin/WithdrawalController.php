<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get Current Tenant/Pesantren
        // Because logic is in Admin namespace, accessing via Auth::user()->pesantren is safe
        // Or if using tenancy package, maybe we don't need explicit ID check?
        // But let's be safe.
        $pesantren = Auth::user()->pesantren;
        
        if (!$pesantren) {
             return back()->with('error', 'Data Pesantren tidak ditemukan.');
        }

        $withdrawals = $pesantren->withdrawals()->latest()->paginate(10);
        
        // Return to Admin Dashboard View (we need to create this view later)
        return view('admin.withdrawal.index', compact('pesantren', 'withdrawals'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:50000',
        ]);

        $pesantren = Auth::user()->pesantren;

        if (!$pesantren) {
            return back()->with('error', 'Pesantren not found.');
        }

        // Validate Package
        if ($pesantren->package !== 'advance' && $pesantren->package !== 'enterprise') {
            return back()->with('error', 'Fitur pencairan dana hanya untuk paket Advance/Enterprise.');
        }

        // Validate Bank Details
        if (!$pesantren->bank_name || !$pesantren->account_number) {
            return back()->with('error', 'Silakan lengkapi data rekening di menu Pengaturan Sekolah terlebih dahulu.');
        }

        // Validate Balance
        if ($request->amount > $pesantren->saldo_pg) {
            return back()->with('error', 'Saldo tidak mencukupi untuk penarikan ini.');
        }

        // Check Pending Withdrawals
        $pending = $pesantren->withdrawals()->where('status', 'pending')->exists();
        if ($pending) {
            return back()->with('error', 'Anda masih memiliki permintaan penarikan yang sedang diproses.');
        }

        // Create Withdrawal
        Withdrawal::create([
            'pesantren_id' => $pesantren->id,
            'amount' => $request->amount,
            'status' => 'pending',
            'bank_name' => $pesantren->bank_name,
            'account_number' => $pesantren->account_number,
            'account_name' => $pesantren->account_name,
        ]);

        // Deduct balance immediately to prevent double spending
        $pesantren->decrement('saldo_pg', $request->amount);

        return back()->with('success', 'Permintaan penarikan berhasil dikirim. Harap tunggu persetujuan Admin.');
    }

    /**
     * Update bank account details
     */
    public function updateBank(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_name' => 'required|string|max:255',
        ]);

        $pesantren = Auth::user()->pesantren;

        if (!$pesantren) {
            return back()->with('error', 'Data Pesantren tidak ditemukan.');
        }

        $pesantren->update([
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
        ]);

        return back()->with('success', 'Data rekening berhasil diperbarui!');
    }
}
