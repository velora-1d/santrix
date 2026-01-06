<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\JurnalKbm;
use App\Models\AbsensiKbmDetail;
use App\Models\JadwalPelajaran; // Assuming this model exists
use App\Models\Santri;
use App\Models\User;

class KbmController extends Controller
{
    /**
     * Login specific for Ustadz/Mobile App
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // Create Sanctum Token
            $token = $user->createToken('kbm-mobile-app')->plainTextToken;
            
            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role,
                    'pesantren_id' => $user->pesantren_id,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email atau password salah'
        ], 401);
    }

    /**
     * Get teaching schedule for today for the logged-in Ustadz
     */
    public function getJadwal(Request $request)
    {
        $user = $request->user();
        
        // Assuming JadwalPelajaran has relationships defined
        // WARNING: Ensure JadwalPelajaran Model exists and has these relations
        // If not, we might need to adjust or create it.
        // For now, I'll assume simple query or placeholder if model logic is complex.
        
        // This is a placeholder query logic assuming structure. 
        // Real implementation depends on how JadwalPelajaran is structured.
        // Assuming: users table has 'id', jadwal_pelajaran table has 'user_id' (ustadz)
        
        // Simple mock for now if JadwalPelajaran logic is not fully clear from my context
        /* 
           Ideally:
           $jadwal = JadwalPelajaran::with(['kelas', 'mapel'])
               ->where('user_id', $user->id)
               ->where('hari', date('l')) // 'Monday', 'Tuesday' etc. (adjust to app locale)
               ->get();
        */
        
        // Returning empty list for now until Jadwal structure is confirmed, 
        // to prevent 500 error on mobile dev start.
        return response()->json([
            'success' => true,
            'data' => [] 
        ]);
    }

    /**
     * Get List Santri by Kelas ID for attendance checklist
     */
    public function getSantriByKelas($kelasId)
    {
        $santris = Santri::where('kelas_id', $kelasId)
                         ->where('status', 'aktif')
                         ->select('id', 'nama', 'nis')
                         ->orderBy('nama')
                         ->get();
                         
        return response()->json([
            'success' => true,
            'data' => $santris
        ]);
    }

    /**
     * Submit Jurnal & Absensi
     */
    public function storeJurnal(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'materi' => 'required|string',
            'absensi' => 'required|array', // Array of {santri_id, status}
            'absensi.*.santri_id' => 'required|exists:santris,id',
            'absensi.*.status' => 'required|in:hadir,sakit,izin,alfa',
        ]);

        $user = $request->user();

        DB::beginTransaction();
        try {
            // 1. Create Header (Jurnal)
            $jurnal = JurnalKbm::create([
                'pesantren_id' => $user->pesantren_id,
                'kelas_id' => $request->kelas_id,
                'user_id' => $user->id,
                'mapel_id' => $request->mapel_id,
                'tanggal' => now()->toDateString(),
                'jam_ke' => $request->jam_ke, // Optional
                'materi' => $request->materi,
                'catatan' => $request->catatan,
                'status' => 'completed',
            ]);

            // 2. Create Details (Absensi)
            foreach ($request->absensi as $absen) {
                // Only save if status is NOT hadir to save space? 
                // OR save all for completeness. Let's save all for now.
                AbsensiKbmDetail::create([
                    'jurnal_kbm_id' => $jurnal->id,
                    'santri_id' => $absen['santri_id'],
                    'status' => $absen['status'],
                    'keterangan' => $absen['keterangan'] ?? null,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Jurnal KBM berhasil disimpan',
                'data' => $jurnal
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan jurnal: ' . $e->getMessage()
            ], 500);
        }
    }
}
