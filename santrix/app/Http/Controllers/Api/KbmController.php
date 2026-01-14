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
     * Store Jurnal with Photos
     */
    public function storeJurnal(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'materi' => 'required|string',
            'absensi' => 'required|array',
            'absensi.*.santri_id' => 'required|exists:santri,id', // Fixed table name
            'absensi.*.status' => 'required|in:hadir,sakit,izin,alfa',
            // Photos (Base64 or multipart) - Using multipart is standard but Base64 is easier for some React Native libs. 
            // We assume multipart/form-data here.
            'foto_awal' => 'nullable|image|max:2048', 
            'foto_akhir' => 'nullable|image|max:2048',
        ]);

        $user = $request->user();

        DB::beginTransaction();
        try {
            // Upload Photos
            $pathAwal = $request->file('foto_awal') ? $request->file('foto_awal')->store('jurnal/awal', 'public') : null;
            $pathAkhir = $request->file('foto_akhir') ? $request->file('foto_akhir')->store('jurnal/akhir', 'public') : null;

            // 1. Create Header (Jurnal)
            $jurnal = JurnalKbm::create([
                'pesantren_id' => $user->pesantren_id,
                'tahun_ajaran_id' => \App\Helpers\AcademicHelper::activeYearId(),
                'kelas_id' => $request->kelas_id,
                'user_id' => $user->id,
                'mapel_id' => $request->mapel_id,
                'tanggal' => now()->toDateString(),
                'jam_ke' => $request->jam_ke, 
                'materi' => $request->materi,
                'catatan' => $request->catatan,
                'status' => 'completed',
                'foto_awal' => $pathAwal,
                'foto_akhir' => $pathAkhir,
                'jam_mulai' => $request->jam_mulai, // Optional: sent from client
                'jam_selesai' => now(), // Assume submitted at end of class
            ]);

            // 2. Create Details (Absensi)
            foreach ($request->absensi as $absen) {
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

    /**
     * Daily Check-In (Absensi Guru)
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'foto_masuk' => 'required|image|max:2048',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
        ]);

        $user = $request->user();
        $today = now()->toDateString();
        
        // Check if already checked in
        $existing = \App\Models\AbsensiGuru::where('user_id', $user->id)
                    ->where('tanggal', $today)
                    ->first();

        if ($existing) {
             return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan Check-in hari ini'
            ], 400);
        }

        $path = $request->file('foto_masuk')->store('absensi_guru/' . $today, 'public');

        // Determine status based on time (Example: Late if after 07:30)
        $jamMasuk = now()->format('H:i:s');
        $status = 'hadir';
        if ($jamMasuk > '07:30:00') {
            $status = 'telat';
        }

        $absensi = \App\Models\AbsensiGuru::create([
            'pesantren_id' => $user->pesantren_id,
            'tahun_ajaran_id' => \App\Helpers\AcademicHelper::activeYearId(),
            'user_id' => $user->id,
            'tanggal' => $today,
            'jam_masuk' => $jamMasuk,
            'status' => $status,
            'foto_masuk' => $path,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil',
            'data' => $absensi
        ]);
    }

    /**
     * Daily Check-Out (Absensi Guru)
     */
    public function checkOut(Request $request)
    {
        $request->validate([
            'foto_pulang' => 'nullable|image|max:2048',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
        ]);

        $user = $request->user();
        $today = now()->toDateString();

        $absensi = \App\Models\AbsensiGuru::where('user_id', $user->id)
                    ->where('tanggal', $today)
                    ->first();

        if (!$absensi) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum melakukan Check-in hari ini'
            ], 400);
        }

        if ($absensi->jam_pulang) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan Check-out hari ini'
            ], 400);
        }

        $path = $request->file('foto_pulang') ? $request->file('foto_pulang')->store('absensi_guru/' . $today, 'public') : null;

        $absensi->update([
            'jam_pulang' => now()->format('H:i:s'),
            'foto_pulang' => $path,
            // Update location if needed for checkout tracking, or ignore
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-out berhasil',
            'data' => $absensi
        ]);
    }
}
