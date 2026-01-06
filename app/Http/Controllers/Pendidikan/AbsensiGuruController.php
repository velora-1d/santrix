<?php

namespace App\Http\Controllers\Pendidikan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AbsensiGuru;
use Illuminate\Support\Facades\Auth;

class AbsensiGuruController extends Controller
{
    /**
     * List of Teacher Attendance
     */
    public function index(Request $request)
    {
        $today = now()->toDateString();
        $date = $request->get('tanggal', $today);
        
        $absensis = AbsensiGuru::with('user')
            ->where('pesantren_id', Auth::user()->pesantren_id)
            ->whereDate('tanggal', $date)
            ->orderBy('jam_masuk', 'asc')
            ->get();
            
        // Stats
        $total_hadir = $absensis->where('status', 'hadir')->count();
        $total_telat = $absensis->where('status', 'telat')->count();
        $total_izin = $absensis->where('status', 'izin')->count();
        $total_sakit = $absensis->where('status', 'sakit')->count();

        return view('pendidikan.absensi-guru.index', compact('absensis', 'date', 'total_hadir', 'total_telat', 'total_izin', 'total_sakit'));
    }
}
