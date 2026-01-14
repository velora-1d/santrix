<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Pesantren;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    public function index()
    {
        // SUPER ADMIN VIEW: Show ALL withdrawals from ALL pesantrens
        $withdrawals = Withdrawal::with('pesantren')->latest()->paginate(15);
        
        return view('owner.withdrawal.index', compact('withdrawals'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_note' => 'nullable|string'
        ]);

        $withdrawal = Withdrawal::findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        $withdrawal->status = $request->status;
        $withdrawal->admin_note = $request->admin_note;
        
        if ($request->status === 'rejected') {
            // REFUND SALDO
            $pesantren = Pesantren::find($withdrawal->pesantren_id);
            if ($pesantren) {
                $pesantren->increment('saldo_pg', $withdrawal->amount);
            }
        }

        $withdrawal->save();

        return back()->with('success', 'Status penarikan berhasil diperbarui.');
    }
}
