<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\Kelas;
use App\Models\Asrama;
use App\Models\Kobong;
use App\Models\MutasiSantri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SekretarisController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        $pesantrenId = Auth::user()->pesantren_id;

        // Cache all KPI queries for 5 minutes
        $totalSantri = Cache::remember('sekretaris_total_santri_' . $pesantrenId, 300, function() use ($pesantrenId) {
            return Santri::where('pesantren_id', $pesantrenId)->where('is_active', true)->count();
        });
        
        $santriPutra = Cache::remember('sekretaris_santri_putra_' . $pesantrenId, 300, function() use ($pesantrenId) {
            return Santri::where('pesantren_id', $pesantrenId)->where('is_active', true)->where('gender', 'putra')->count();
        });
        
        $santriPutri = Cache::remember('sekretaris_santri_putri_' . $pesantrenId, 300, function() use ($pesantrenId) {
            return Santri::where('pesantren_id', $pesantrenId)->where('is_active', true)->where('gender', 'putri')->count();
        });
        
        $jumlahAsrama = Cache::remember('sekretaris_jumlah_asrama_' . $pesantrenId, 300, function() use ($pesantrenId) {
            return Asrama::where('pesantren_id', $pesantrenId)->count();
        });
        
        $jumlahKelas = Cache::remember('sekretaris_jumlah_kelas_' . $pesantrenId, 300, function() use ($pesantrenId) {
            return Kelas::where('pesantren_id', $pesantrenId)->count();
        });
        
        $jumlahKobong = Cache::remember('sekretaris_jumlah_kobong_' . $pesantrenId, 300, function() use ($pesantrenId) {
            return Kobong::join('asrama', 'kobong.asrama_id', '=', 'asrama.id')
                         ->where('asrama.pesantren_id', $pesantrenId)
                         ->count();
        });
        
        return view('sekretaris.dashboard.index', compact(
            'totalSantri',
            'santriPutra',
            'santriPutri',
            'jumlahAsrama',
            'jumlahKelas',
            'jumlahKobong'
        ));
    }
    
    // Data Santri - Index
    public function dataSantri(Request $request)
    {
        $activeYearId = \App\Helpers\AcademicHelper::activeYearId();
        $selectedYearId = $request->tahun_ajaran_id ?? $activeYearId;
        $isHistory = $selectedYearId && $activeYearId && $selectedYearId != $activeYearId;
        $pesantrenId = Auth::user()->pesantren_id;

        if ($isHistory) {
            // Query History (RiwayatKelas)
            // We need to fetch Santri data but use the Class from Riwayat
            $query = \App\Models\RiwayatKelas::with(['santri', 'kelas', 'santri.asrama', 'santri.kobong'])
                ->where('pesantren_id', $pesantrenId) // SCOPED
                ->where('tahun_ajaran_id', $selectedYearId);

            // Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('santri', function($q) use ($search) {
                    $q->where('nis', 'like', "%{$search}%")
                      ->orWhere('nama_santri', 'like', "%{$search}%");
                });
            }

            // Filters
            if ($request->filled('gender')) {
                $query->whereHas('santri', function($q) use ($request) {
                    $q->where('gender', $request->gender);
                });
            }
            if ($request->filled('kelas_id')) {
                $query->where('kelas_id', $request->kelas_id);
            }
            // Asrama/Kobong is current, history doesn't store Asrama snapshot (usually).
            // Unless we add asrama_id to RiwayatKelas (which we assume we didn't yet, checking schema... no we didn't).
            // So we can only filter by current Asrama? Or ignore Asrama filter in history?
            // Let's allow filtering by CURRENT asrama effectively.
             if ($request->filled('asrama_id')) {
                $query->whereHas('santri', function($q) use ($request) {
                    $q->where('asrama_id', $request->asrama_id);
                });
            }

            $riwayat = $query->latest()->paginate(35);
            
            // Transform to match Santri structure for View
            $santri = $riwayat->map(function($item) {
                $s = $item->santri;
                if ($s) {
                     $s->kelas = $item->kelas; // Override class with history class
                     // Note: Status might be 'promoted' or 'retained' in $item->status
                     // We could attach it: $s->history_status = $item->status;
                }
                return $s;
            })->filter(); // remove nulls
            
            // Pagination is on $riwayat, but we return a Collection mapped.
            // View expects a Paginator.
            // Simple hack: Pass $riwayat to view, view expects $santri items.
            // If we just pass $riwayat, the view loop needs to change: $row->santri
            // BETTER: Modifiy View to handle both, OR just return $riwayat and update View.
            // Updating View is safer.
            $santri = $riwayat; 

        } else {
            // Query Current Active Data
            $query = Santri::with(['kelas', 'asrama', 'kobong'])
                           ->where('pesantren_id', $pesantrenId); // SCOPED
            
            // Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nis', 'like', "%{$search}%")
                      ->orWhere('nama_santri', 'like', "%{$search}%");
                });
            }
            
            // Filters
            if ($request->filled('gender')) {
                $query->where('gender', $request->gender);
            }
            if ($request->filled('kelas_id')) {
                $query->where('kelas_id', $request->kelas_id);
            }
            if ($request->filled('asrama_id')) {
                $query->where('asrama_id', $request->asrama_id);
            }
            if ($request->filled('is_active')) {
                $query->where('is_active', $request->is_active);
            }
            
            $santri = $query->latest()->paginate(35);
        }

        $kelasList = Kelas::where('pesantren_id', $pesantrenId)->get();
        $asramaList = Asrama::where('pesantren_id', $pesantrenId)->get();
        $tahunAjaranList = \App\Models\TahunAjaran::where('pesantren_id', $pesantrenId)->orderBy('nama', 'desc')->get();
        
        return view('sekretaris.data-santri.index', compact('santri', 'kelasList', 'asramaList', 'tahunAjaranList', 'selectedYearId', 'isHistory'));
    }
    
    // Data Santri - Create Form
    public function createSantri()
    {
        $pesantrenId = Auth::user()->pesantren_id;
        $kelasList = Kelas::where('pesantren_id', $pesantrenId)->get();
        $asramaList = Asrama::where('pesantren_id', $pesantrenId)->get();
        
        return view('sekretaris.data-santri.create', compact('kelasList', 'asramaList'));
    }
    
    // Data Santri - Show Detail
    public function showSantri($subdomain, $id)
    {
        $pesantrenId = Auth::user()->pesantren_id;
        $santri = Santri::with(['kelas', 'asrama', 'kobong'])
                        ->where('pesantren_id', $pesantrenId)
                        ->findOrFail($id);
        $mutasiHistory = MutasiSantri::where('santri_id', $id)->orderBy('tanggal_mutasi', 'desc')->get();
        
        return view('sekretaris.data-santri.show', compact('santri', 'mutasiHistory'));
    }
    
    // Data Santri - Store
    public function storeSantri(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|unique:santri,nis',
            'nama_santri' => 'required|string|max:255',
            'negara' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'kota_kabupaten' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'desa_kampung' => 'required|string|max:255',
            'rt_rw' => 'required|string|max:50',
            'nama_ortu_wali' => 'required|string|max:255',
            'no_hp_ortu_wali' => 'required|string|max:20',
            'asrama_id' => 'required|exists:asrama,id',
            'kobong_id' => 'required|exists:kobong,id',
            'kelas_id' => 'required|exists:kelas,id',
            'gender' => 'required|in:putra,putri',
            'tanggal_masuk' => 'required|date',
        ]);
        
        $validated['pesantren_id'] = Auth::user()->pesantren_id; // INJECT SCOPE
        
        // Wrap in transaction for atomicity
        DB::beginTransaction();
        try {
            $santri = Santri::create($validated);
            
            // Create mutasi record (must succeed or rollback both)
            MutasiSantri::create([
                'pesantren_id' => $validated['pesantren_id'], // INJECT SCOPE
                'santri_id' => $santri->id,
                'tahun_ajaran_id' => \App\Helpers\AcademicHelper::activeYearId(),
                'jenis_mutasi' => 'masuk',
                'tanggal_mutasi' => $validated['tanggal_masuk'],
                'keterangan' => 'Santri baru masuk',
            ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan data santri: ' . $e->getMessage())->withInput();
        }
        
        // Send Telegram notification AFTER commit (non-blocking)
        try {
            $telegram = new \App\Services\TelegramService();
            $kelas = Kelas::find($validated['kelas_id']);
            $asrama = Asrama::find($validated['asrama_id']);
            
            $telegram->notifySantriRegistration([
                'nama' => $validated['nama_santri'],
                'jenis_kelamin' => ucfirst($validated['gender']),
                'kelas' => $kelas->nama_kelas ?? '-',
                'asrama' => $asrama->nama_asrama ?? '-',
            ]);
        } catch (\Exception $e) {
            Log::warning('Telegram notification failed: ' . $e->getMessage());
        }
        
        return redirect()->route('sekretaris.data-santri')
            ->with('success', 'Data santri berhasil ditambahkan');
    }
    
    // Data Santri - Edit Form
    public function editSantri($subdomain, $id)
    {
        $pesantrenId = Auth::user()->pesantren_id;
        $santri = Santri::where('pesantren_id', $pesantrenId)->findOrFail($id);
        
        // Prevent editing inactive (mutasi keluar) santri
        if (!$santri->is_active) {
            return redirect()->route('sekretaris.data-santri')
                ->with('error', 'Santri ini sudah tidak aktif (Mutasi Keluar). Data tidak dapat diedit.');
        }
        
        $kelasList = Kelas::where('pesantren_id', $pesantrenId)->get();
        $asramaList = Asrama::where('pesantren_id', $pesantrenId)->get();
        $kobongList = Kobong::whereHas('asrama', function($q) use ($pesantrenId) {
             $q->where('pesantren_id', $pesantrenId);
        })->where('asrama_id', $santri->asrama_id)->get();
        
        return view('sekretaris.data-santri.edit', compact('santri', 'kelasList', 'asramaList', 'kobongList'));
    }
    
    // Data Santri - Update
    public function updateSantri(Request $request, $subdomain, $id)
    {
        $pesantrenId = Auth::user()->pesantren_id;
        $santri = Santri::where('pesantren_id', $pesantrenId)->findOrFail($id);
        
        $validated = $request->validate([
            'nis' => 'required|unique:santri,nis,' . $id,
            'nama_santri' => 'required|string|max:255',
            'negara' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'kota_kabupaten' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'desa_kampung' => 'required|string|max:255',
            'rt_rw' => 'required|string|max:50',
            'nama_ortu_wali' => 'required|string|max:255',
            'no_hp_ortu_wali' => 'required|string|max:20',
            'asrama_id' => 'required|exists:asrama,id',
            'kobong_id' => 'required|exists:kobong,id',
            'kelas_id' => 'required|exists:kelas,id',
            'gender' => 'required|in:putra,putri',
            'tanggal_masuk' => 'required|date',
        ]);
        
        $santri->update($validated);
        
        return redirect()->route('sekretaris.data-santri')
            ->with('success', 'Data santri berhasil diperbarui');
    }
    
    // Data Santri - Deactivate
    public function deactivateSantri($subdomain, $id)
    {
        $pesantrenId = Auth::user()->pesantren_id;
        $santri = Santri::where('pesantren_id', $pesantrenId)->findOrFail($id);
        
        // Wrap in transaction for atomicity
        DB::beginTransaction();
        try {
            $santri->update(['is_active' => false]);
            
            // Create mutasi record (must succeed or rollback both)
            MutasiSantri::create([
                'pesantren_id' => $pesantrenId, // INJECT SCOPE
                'santri_id' => $id,
                'tahun_ajaran_id' => \App\Helpers\AcademicHelper::activeYearId(),
                'jenis_mutasi' => 'keluar',
                'tanggal_mutasi' => now(),
                'keterangan' => 'Santri dinonaktifkan',
            ]);
            
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menonaktifkan santri: ' . $e->getMessage());
        }
        
        return redirect()->route('sekretaris.data-santri')
            ->with('success', 'Santri berhasil dinonaktifkan');
    }
    
    // Generate Virtual Account Bulk (ADVANCE PACKAGE) via Midtrans
    public function generateVaBulk()
    {
        // Get all active santri without VA number
        $santriWithoutVa = Santri::where('is_active', true)
            ->where(function($q) {
                $q->whereNull('virtual_account_number')
                  ->orWhere('virtual_account_number', '');
            })
            ->get();
            
        if ($santriWithoutVa->isEmpty()) {
            return redirect()->route('sekretaris.data-santri')
                ->with('info', 'Semua santri sudah memiliki Virtual Account.');
        }
        
        $midtrans = new \App\Services\MidtransService();
        $generated = 0;
        $failed = 0;
        
        foreach ($santriWithoutVa as $santri) {
            try {
                // Create transaction via Midtrans
                $result = $midtrans->createTransaction($santri, 0); // 0 = open amount
                
                if ($result && isset($result['va_numbers'][0]['va_number'])) {
                    $vaNumber = $result['va_numbers'][0]['va_number'];
                    $santri->update(['virtual_account_number' => $vaNumber]);
                    $generated++;
                } else {
                    $failed++;
                }
            } catch (\Exception $e) {
                Log::error("VA Generate failed for Santri ID {$santri->id}: " . $e->getMessage());
                $failed++;
            }
        }
        
        $message = "Berhasil generate {$generated} Virtual Account via Midtrans.";
        if ($failed > 0) {
            $message .= " Gagal: {$failed} santri.";
        }
        
        return redirect()->route('sekretaris.data-santri')
            ->with('success', $message);
    }
    
    // Reset Virtual Account Bulk (ADVANCE PACKAGE)
    public function resetVaBulk()
    {
        // Reset all VA numbers
        $updated = Santri::where('is_active', true)
            ->whereNotNull('virtual_account_number')
            ->update(['virtual_account_number' => null]);
        
        return redirect()->route('sekretaris.data-santri')
            ->with('success', "Berhasil reset {$updated} Virtual Account. Santri harus generate ulang untuk bisa bayar.");
    }
    
    // Get Kobong by Asrama (AJAX)
    public function getKobongByAsrama($asramaId)
    {
        $kobong = Kobong::where('asrama_id', $asramaId)->get();
        return response()->json($kobong);
    }
    
    // Mutasi Santri
    public function mutasiSantri()
    {
        $pesantrenId = Auth::user()->pesantren_id;
        $santriAktif = Santri::where('pesantren_id', $pesantrenId)->where('is_active', true)->get();
        $mutasi = MutasiSantri::with(['santri' => function($q) use ($pesantrenId) {
            $q->where('pesantren_id', $pesantrenId);
        }])->where('pesantren_id', $pesantrenId)->latest()->paginate(15);
        
        return view('sekretaris.mutasi.index', compact('santriAktif', 'mutasi'));
    }
    
    // Store Mutasi
    public function storeMutasi(Request $request)
    {
        $validated = $request->validate([
            'santri_id' => 'required|exists:santri,id',
            'jenis_mutasi' => 'required|in:masuk,keluar,pindah_kelas,pindah_asrama',
            'tanggal_mutasi' => 'required|date',
            'keterangan' => 'nullable|string',
            'dari' => 'nullable|string',
            'ke' => 'nullable|string',
        ]);
        
        $validated['tahun_ajaran_id'] = \App\Helpers\AcademicHelper::activeYearId();
        $validated['pesantren_id'] = Auth::user()->pesantren_id; // INJECT SCOPE
        $mutasi = MutasiSantri::create($validated);
        
        // Update santri status if keluar
        if ($request->jenis_mutasi === 'keluar') {
            Santri::find($request->santri_id)->update(['is_active' => false]);
        }
        
        // Send Telegram notification
        try {
            $telegram = new \App\Services\TelegramService();
            $santri = Santri::find($validated['santri_id']);
            $jenisMutasi = [
                'masuk' => '📥 Masuk',
                'keluar' => '📤 Keluar',
                'pindah_kelas' => '🔄 Pindah Kelas',
                'pindah_asrama' => '🏠 Pindah Asrama'
            ];
            
            $icon = $validated['jenis_mutasi'] === 'keluar' ? '⚠️' : '📋';
            $telegram->notify(
                'MUTASI SANTRI',
                "👤 Nama: {$santri->nama_santri}\n" .
                "📊 Jenis: {$jenisMutasi[$validated['jenis_mutasi']]}\n" .
                ($validated['dari'] ? "⬅️ Dari: {$validated['dari']}\n" : "") .
                ($validated['ke'] ? "➡️ Ke: {$validated['ke']}\n" : "") .
                "📅 Tanggal: " . date('d M Y', strtotime($validated['tanggal_mutasi'])),
                $icon
            );
        } catch (\Exception $e) {
            Log::warning('Telegram notification failed: ' . $e->getMessage());
        }
        
        return redirect()->route('sekretaris.mutasi-santri')
            ->with('success', 'Mutasi santri berhasil dicatat');
    }
    
    // Update Mutasi
    public function updateMutasi(Request $request, $id)
    {
        $mutasi = MutasiSantri::findOrFail($id);
        
        $validated = $request->validate([
            'santri_id' => 'required|exists:santri,id',
            'jenis_mutasi' => 'required|in:masuk,keluar,pindah_kelas,pindah_asrama',
            'tanggal_mutasi' => 'required|date',
            'keterangan' => 'nullable|string',
            'dari' => 'nullable|string',
            'ke' => 'nullable|string',
        ]);
        
        $mutasi->update($validated);
        
        return redirect()->route('sekretaris.mutasi-santri')
            ->with('success', 'Data mutasi berhasil diperbarui');
    }
    
    // Delete Mutasi
    public function destroyMutasi($id)
    {
        $mutasi = MutasiSantri::findOrFail($id);
        $mutasi->delete();
        
        return redirect()->route('sekretaris.mutasi-santri')
            ->with('success', 'Data mutasi berhasil dihapus');
    }
    
    // Laporan
    public function laporan()
    {
        return view('sekretaris.laporan.index');
    }
    
    // Export Laporan Data Santri (PDF)
    public function exportLaporanSantri(Request $request)
    {
        $pesantrenId = Auth::user()->pesantren_id;
        $santri = Santri::with(['kelas', 'asrama', 'kobong'])
            ->where('pesantren_id', $pesantrenId) // SCOPED
            ->where('is_active', true)
            ->get();
        
        $pdf = Pdf::loadView('sekretaris.laporan.laporan-santri-pdf', compact('santri'));
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('laporan-santri.pdf');
    }
    
    // Export Statistik Santri per Kelas
    public function exportStatistikKelas()
    {
        $pesantrenId = Auth::user()->pesantren_id;
        $kelasList = Kelas::where('pesantren_id', $pesantrenId)->get();
        $statistik = [];
        
        foreach ($kelasList as $kelas) {
            $totalPutra = Santri::where('kelas_id', $kelas->id)
                ->where('pesantren_id', $pesantrenId) // SCOPED
                ->where('gender', 'putra')
                ->where('is_active', true)
                ->count();
            
            $totalPutri = Santri::where('kelas_id', $kelas->id)
                ->where('gender', 'putri')
                ->where('is_active', true)
                ->count();
            
            $statistik[] = [
                'kelas' => $kelas->nama_kelas,
                'tingkat' => $kelas->tingkat,
                'putra' => $totalPutra,
                'putri' => $totalPutri,
                'total' => $totalPutra + $totalPutri,
            ];
        }
        
        $pdf = Pdf::loadView('sekretaris.laporan.statistik-kelas-pdf', compact('statistik'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('statistik-santri-per-kelas.pdf');
    }
    
    // Export Statistik Santri per Asrama
    public function exportStatistikAsrama()
    {
        $pesantrenId = Auth::user()->pesantren_id;
        $asramaList = Asrama::where('pesantren_id', $pesantrenId)->with('kobong')->get();
        $statistik = [];
        
        foreach ($asramaList as $asrama) {
            $totalSantri = Santri::where('asrama_id', $asrama->id)
                ->where('is_active', true)
                ->count();
            
            $kobongData = [];
            foreach ($asrama->kobong as $kobong) {
                $jumlahSantri = Santri::where('kobong_id', $kobong->id)
                    ->where('is_active', true)
                    ->count();
                
                $kobongData[] = [
                    'nomor' => $kobong->nomor_kobong,
                    'jumlah_santri' => $jumlahSantri,
                    'kapasitas' => 20,
                    'sisa_kapasitas' => 20 - $jumlahSantri,
                ];
            }
            
            $statistik[] = [
                'asrama' => $asrama->nama_asrama,
                'gender' => $asrama->gender,
                'total_santri' => $totalSantri,
                'total_kobong' => $asrama->kobong->count(),
                'kobong_detail' => $kobongData,
            ];
        }
        
        $pdf = Pdf::loadView('sekretaris.laporan.statistik-asrama-pdf', compact('statistik'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('statistik-santri-per-asrama.pdf');
    }
    
    // Export Laporan Mutasi Santri
    public function exportLaporanMutasi(Request $request)
    {
        $pesantrenId = Auth::user()->pesantren_id;
        $query = MutasiSantri::with('santri')->where('pesantren_id', $pesantrenId); // SCOPED
        
        // Filter by date range
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_mutasi', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_mutasi', '<=', $request->tanggal_selesai);
        }
        
        // Filter by jenis mutasi
        if ($request->filled('jenis_mutasi')) {
            $query->where('jenis_mutasi', $request->jenis_mutasi);
        }
        
        $mutasi = $query->orderBy('tanggal_mutasi', 'desc')->get();
        
        $tanggalMulai = $request->tanggal_mulai ?? 'Awal';
        $tanggalSelesai = $request->tanggal_selesai ?? 'Sekarang';
        
        $pdf = Pdf::loadView('sekretaris.laporan.mutasi-pdf', compact('mutasi', 'tanggalMulai', 'tanggalSelesai'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('laporan-mutasi-santri.pdf');
    }
    
    // Download Template Excel
    public function downloadTemplateExcel()
    {
        $pesantrenId = Auth::user()->pesantren_id;
        $kelasList = Kelas::where('pesantren_id', $pesantrenId)->get();
        $asramaList = Asrama::where('pesantren_id', $pesantrenId)->get();
        
        $html = view('sekretaris.template-import-excel', compact('kelasList', 'asramaList'))->render();
        
        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="template-import-santri.xls"');
    }
    
    // Download Template CSV
    public function downloadTemplateCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template-import-santri.csv"',
        ];
        
        $columns = [
            'NIS',
            'Nama Santri',
            'Gender (putra/putri)',
            'Negara',
            'Provinsi',
            'Kota/Kabupaten',
            'Kecamatan',
            'Desa/Kampung',
            'RT/RW',
            'Nama Ortu/Wali',
            'No HP Ortu/Wali',
            'Kelas ID',
            'Asrama ID',
            'Kobong ID',
        ];
        
        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Add example row
            fputcsv($file, [
                '2025001',
                'Ahmad Santoso',
                'putra',
                'Indonesia',
                'Jawa Timur',
                'Surabaya',
                'Gubeng',
                'Airlangga',
                '001/002',
                'Bapak Ahmad',
                '081234567890',
                '1',
                '1',
                '1',
            ]);
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    // Import Santri from Excel/CSV
    public function importSantri(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xls,xlsx|max:2048',
        ]);
        
        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        
        $imported = 0;
        $errors = [];
        
        try {
            if ($extension === 'csv') {
                $handle = fopen($file->getRealPath(), 'r');
                $header = fgetcsv($handle); // Skip header
                
                while (($row = fgetcsv($handle)) !== false) {
                    if (count($row) < 14) continue; // Skip incomplete rows
                    
                    // SECURITY FIX (VULN-005): Validate Foreign Keys belong to THIS tenant
                    $kelasId = $row[11];
                    $asramaId = $row[12];
                    $kobongId = $row[13];
                    
                    if (!$this->validateForeignKeys($kelasId, $asramaId, $kobongId)) {
                        $errors[] = "Baris NIS {$row[0]}: ID Kelas/Asrama/Kobong tidak valid atau milik tenant lain.";
                        continue;
                    }
                    
                    try {
                        $santri = Santri::create([
                            'pesantren_id' => Auth::user()->pesantren_id, // INJECT SCOPE
                            'nis' => $this->sanitizeInput($row[0]),
                            'nama_santri' => $this->sanitizeInput($row[1]),
                            'gender' => $this->sanitizeInput($row[2]),
                            'negara' => $this->sanitizeInput($row[3]),
                            'provinsi' => $this->sanitizeInput($row[4]),
                            'kota_kabupaten' => $this->sanitizeInput($row[5]),
                            'kecamatan' => $this->sanitizeInput($row[6]),
                            'desa_kampung' => $this->sanitizeInput($row[7]),
                            'rt_rw' => $this->sanitizeInput($row[8]),
                            'nama_ortu_wali' => $this->sanitizeInput($row[9]),
                            'no_hp_ortu_wali' => $this->sanitizeInput($row[10]),
                            'kelas_id' => $row[11],
                            'asrama_id' => $row[12],
                            'kobong_id' => $row[13],
                            'is_active' => true,
                        ]);
                        
                        // Create mutasi record
                        MutasiSantri::create([
                            'pesantren_id' => Auth::user()->pesantren_id, // INJECT SCOPE
                            'santri_id' => $santri->id,
                            'jenis_mutasi' => 'masuk',
                            'tanggal_mutasi' => now(),
                            'keterangan' => 'Import data santri',
                        ]);
                        
                        $imported++;
                    } catch (\Exception $e) {
                        $errors[] = "Baris NIS {$row[0]}: " . $e->getMessage();
                    }
                }
                
                fclose($handle);
            } else {
                // Handle Excel files (simple HTML table parsing)
                $content = file_get_contents($file->getRealPath());
                return redirect()->route('sekretaris.data-santri')
                    ->with('error', 'Format Excel belum didukung. Gunakan CSV untuk saat ini.');
            }
            
            $message = "Berhasil import {$imported} santri.";
            if (count($errors) > 0) {
                $message .= " Gagal: " . count($errors) . " baris.";
            }
            
            return redirect()->route('sekretaris.data-santri')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            return redirect()->route('sekretaris.data-santri')
                ->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    /**
     * Security Helper: Sanitize Input for CSV Injection (VULN-006)
     */
    private function sanitizeInput($input)
    {
        if (is_string($input)) {
            $firstChar = substr($input, 0, 1);
            if (in_array($firstChar, ['=', '+', '-', '@'])) {
                return "'" . $input;
            }
        }
        return $input;
    }

    /**
     * Security Helper: Validate Relations (VULN-005)
     */
    private function validateForeignKeys($kelasId, $asramaId, $kobongId)
    {
        $pesantrenId = Auth::user()->pesantren_id;
        
        $kelasExists = Kelas::where('id', $kelasId)->where('pesantren_id', $pesantrenId)->exists();
        if (!$kelasExists) return false;
        
        $asramaExists = Asrama::where('id', $asramaId)->where('pesantren_id', $pesantrenId)->exists();
        if (!$asramaExists) return false;

        $kobongExists = Kobong::where('id', $kobongId)->whereHas('asrama', function($q) use ($pesantrenId) {
             $q->where('pesantren_id', $pesantrenId);
        })->exists();
        if (!$kobongExists) return false;

        return true;
    }
}
