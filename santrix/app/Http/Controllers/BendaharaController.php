<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\Syahriah;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Pegawai;
use App\Models\GajiPegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class BendaharaController extends Controller
{
    // Dashboard
    public function dashboard(Request $request)
    {
        // Get filter values
        $tahun = $request->filled('tahun') ? $request->tahun : now()->year;
        $bulan = $request->filled('bulan') ? $request->bulan : null;
        $kelasId = $request->filled('kelas_id') ? $request->kelas_id : null;
        $asramaId = $request->filled('asrama_id') ? $request->asrama_id : null;
        $kobongId = $request->filled('kobong_id') ? $request->kobong_id : null;
        $gender = $request->filled('gender') ? $request->gender : null;
        $statusLunas = $request->filled('status_lunas') ? $request->status_lunas : null;
        
        $filters = compact('kelasId', 'asramaId', 'kobongId', 'gender');
        
        // Create cache key based on filters
        $cacheKey = "bendahara_dashboard_{$tahun}_{$bulan}_{$kelasId}_{$asramaId}_{$kobongId}_{$gender}_{$statusLunas}";
        
        // 1. Financial Summary
        $financial = $this->getFinancialSummary($cacheKey, $tahun, $bulan);
        
        // 2. Santri Counts
        $santriCounts = $this->getSantriCounts($cacheKey, $filters);
        
        // 3. Arrears (Tunggakan)
        $tunggakan = $this->getTunggakanData($cacheKey);
        
        // 4. Paid Santri Metrics
        $lunasMetrics = $this->getSantriLunasMetrics($cacheKey, $tahun, $bulan);
        
        // 5. Gaji Summary
        $gaji = $this->getGajiSummary($cacheKey, $tahun, $bulan);
        
        // 6. Charts (Cached separately)
        $chartPemasukanPengeluaran = Cache::remember("chart_pemasukan_pengeluaran_{$tahun}", 600, fn() => $this->getChartPemasukanPengeluaran($tahun));
        $chartPerAsrama = Cache::remember('chart_per_asrama', 600, fn() => $this->getChartPerAsrama());
        $chartPerKelas = Cache::remember('chart_per_kelas', 600, fn() => $this->getChartPerKelas());
        
        $chartDistribusiSantri = ['putra' => $santriCounts['putra'], 'putri' => $santriCounts['putri']];
        $chartLunasMenunggak = ['lunas' => $lunasMetrics['putra'] + $lunasMetrics['putri'], 'menunggak' => $tunggakan['total']];
        
        // 7. Lists & Recents
        $lists = $this->getDashboardLists($cacheKey, $tahun, $bulan);

        // 8. Module Summaries
        $totalPegawai = Cache::remember('total_pegawai', 300, fn() => Pegawai::where('is_active', true)->count());
        
        // 9. This Month Summaries
        $bulanIniData = Cache::remember('bulan_ini_data', 120, fn() => [
            'syahriah' => Syahriah::where('tahun', now()->year)->where('bulan', now()->month)->sum('nominal'),
            'pemasukan' => Pemasukan::whereYear('tanggal', now()->year)->whereMonth('tanggal', now()->month)->sum('nominal'),
            'pengeluaran' => Pengeluaran::whereYear('tanggal', now()->year)->whereMonth('tanggal', now()->month)->sum('nominal')
        ]);
        
        // Filter Lists
        $kelasList = Cache::remember('bendahara_kelas_list', 900, fn() => \App\Models\Kelas::all());
        $asramaList = Cache::remember('bendahara_asrama_list', 900, fn() => \App\Models\Asrama::all());
        $kobongList = Cache::remember('bendahara_kobong_list', 900, fn() => \App\Models\Kobong::all());
        
        $saldoPaymentGateway = Auth::user()->pesantren->saldo_pg ?? 0;

        return view('bendahara.dashboard', [
            // Financial
            'saldoDana' => $financial['saldoDana'],
            'totalPemasukan' => $financial['totalPemasukan'],
            'totalPengeluaran' => $financial['totalPengeluaran'],
            'totalSyahriah' => $financial['totalSyahriah'],
            'syahriahBulanIni' => $bulanIniData['syahriah'],
            'pemasukanBulanIni' => $bulanIniData['pemasukan'],
            'pengeluaranBulanIni' => $bulanIniData['pengeluaran'],
            'saldoPaymentGateway' => $saldoPaymentGateway,
            
            // Santri Metrics
            'totalSantriAktif' => $santriCounts['total'],
            'totalSantriPutra' => $santriCounts['putra'],
            'totalSantriPutri' => $santriCounts['putri'],
            'totalSantriPutraLunas' => $lunasMetrics['putra'],
            'totalSantriPutriLunas' => $lunasMetrics['putri'],
            'totalTunggakan' => $tunggakan['total'],
            
            // Gaji
            'totalGajiBulanIni' => $gaji['bulan_ini'],
            'totalGajiTertunda' => $gaji['tertunda'],
            'gajiTertundaCount' => $gaji['count_tertunda'],
            'totalPegawai' => $totalPegawai,
            
            // Charts
            'chartPemasukanPengeluaran' => $chartPemasukanPengeluaran,
            'chartPerAsrama' => $chartPerAsrama,
            'chartPerKelas' => $chartPerKelas,
            'chartDistribusiSantri' => $chartDistribusiSantri,
            'chartLunasMenunggak' => $chartLunasMenunggak,
            
            // Lists
            'santriPutraMenunggak' => $lists['menunggak']['putra'],
            'santriPutriMenunggak' => $lists['menunggak']['putri'],
            'recentSyahriah' => $lists['syahriah'],
            'recentPemasukan' => $lists['pemasukan'],
            'recentPengeluaran' => $lists['pengeluaran'],
            'recentGaji' => $lists['gaji'],

            // Filters
            'kelasList' => $kelasList,
            'asramaList' => $asramaList,
            'kobongList' => $kobongList,
            'tahun' => $tahun, 'bulan' => $bulan, 'kelasId' => $kelasId, 
            'asramaId' => $asramaId, 'kobongId' => $kobongId, 
            'gender' => $gender, 'statusLunas' => $statusLunas
        ]);
    }
    
    private function getChartPemasukanPengeluaran($tahun)
    {
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $data['pemasukan'][] = Pemasukan::whereYear('tanggal', $tahun)->whereMonth('tanggal', $i)->sum('nominal');
            $data['pengeluaran'][] = Pengeluaran::whereYear('tanggal', $tahun)->whereMonth('tanggal', $i)->sum('nominal');
        }
        return $data;
    }
    
    private function getChartPerAsrama()
    {
        return \App\Models\Asrama::withCount('santri')->get()->mapWithKeys(function($asrama) {
            return [$asrama->nama_asrama => $asrama->santri_count];
        })->toArray();
    }
    
    private function getChartPerKelas()
    {
        return \App\Models\Kelas::withCount('santri')->get()->mapWithKeys(function($kelas) {
            return [$kelas->nama_kelas => $kelas->santri_count];
        })->toArray();
    }
    
    // Data Santri (Read-only from master)
    public function dataSantri(Request $request)
    {
        $query = Santri::with(['kelas', 'asrama'])->where('is_active', true);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                  ->orWhere('nama_santri', 'like', "%{$search}%");
            });
        }
        
        $santri = $query->latest()->paginate(35);
        
        return view('bendahara.data-santri', compact('santri'));
    }
    
    // Syahriah - Index
    public function syahriah(Request $request)
    {
        $query = Syahriah::with('santri');
        
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->filled('is_lunas')) {
            $query->where('is_lunas', $request->is_lunas);
        }
        
        $syahriah = $query->latest()->paginate(15);
        $santriList = Santri::where('is_active', true)->with(['asrama', 'kobong'])->get();
        $asramaList = \App\Models\Asrama::all();
        $kobongList = \App\Models\Kobong::with('asrama')->get();
        
        return view('bendahara.syahriah.index', compact('syahriah', 'santriList', 'asramaList', 'kobongList'));
    }
    
    // Syahriah - Store
    public function storeSyahriah(Request $request)
    {
        if ($request->filled('nominal')) {
            $request->merge(['nominal' => str_replace('.', '', $request->nominal)]);
        }

        $validated = $request->validate([
            'santri_id' => [
                'required', 
                \Illuminate\Validation\Rule::exists('santri', 'id')->where('pesantren_id', Auth::user()->pesantren_id)
            ],
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer',
            'nominal' => 'required|numeric|min:0',
            'is_lunas' => 'required|boolean',
            'tanggal_bayar' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);
        
        // Check for duplicate entry
        $existingSyahriah = Syahriah::where('santri_id', $validated['santri_id'])
            ->where('bulan', $validated['bulan'])
            ->where('tahun', $validated['tahun'])
            ->first();
        
        if ($existingSyahriah) {
            $santri = Santri::find($validated['santri_id']);
            $bulanNama = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                          'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $statusLunas = $existingSyahriah->is_lunas ? 'LUNAS' : 'BELUM LUNAS';
            
            return redirect()->route('bendahara.syahriah')
                ->with('warning', "Pembayaran untuk {$santri->nama_santri} bulan {$bulanNama[$validated['bulan']]} {$validated['tahun']} sudah tercatat sebelumnya dengan status: {$statusLunas}. Silakan edit data yang sudah ada jika ingin mengubah.");
        }
        
        $validated['tahun_ajaran_id'] = \App\Helpers\AcademicHelper::activeYearId();
        $syahriah = Syahriah::create($validated);
        
        
        // Send Telegram notification for payment (ASYNC via Event)
        if ($validated['is_lunas']) {
            $santri = Santri::with(['kelas', 'asrama'])->find($validated['santri_id']);
            $bulanNama = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                          'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            // Calculate remaining arrears (sisa tunggakan)
            // Calculate remaining arrears (sisa tunggakan)
            $academicYears = \App\Models\TahunAjaran::select(['id', 'nominal_syahriah', 'tanggal_mulai', 'tanggal_selesai'])
                ->orderBy('tanggal_mulai')
                ->get();
            $defaultFee = $academicYears->where('is_active', true)->value('nominal_syahriah') ?? 500000;

            $startDate = $santri->tanggal_masuk ?? $santri->created_at;
            $endDate = now();
            $sisaTunggakan = 0;
            $unpaidCount = 0;
            
            $current = $startDate->copy()->startOfMonth();
            
            $paidMonths = Syahriah::where('santri_id', $santri->id)
                ->where('is_lunas', true)
                ->get()
                ->map(fn($item) => $item->bulan . '-' . $item->tahun)
                ->toArray();
                
            while ($current <= $endDate) {
                $monthKey = $current->month . '-' . $current->year;
                
                if (!in_array($monthKey, $paidMonths)) {
                    $unpaidCount++;
                    // Find applicable fee
                    $applicableFee = $defaultFee;
                    foreach ($academicYears as $ta) {
                        if ($current->between($ta->tanggal_mulai, $ta->tanggal_selesai)) {
                            $applicableFee = $ta->nominal_syahriah;
                            break;
                        }
                    }
                    $sisaTunggakan += $applicableFee;
                }
                $current->addMonth();
            }
            
            $formattedArrears = number_format($sisaTunggakan, 0, ',', '.');
            $arrearsInfo = $unpaidCount > 0 
                ? "⚠️ Masih ada tunggakan $unpaidCount bulan lagi (Rp $formattedArrears)."
                : "✅ Alhamdulillah lunas, tidak ada tunggakan.";
            
            // Dispatch event (async, non-blocking)
            \App\Events\PaymentReceived::dispatch(
                $santri, 
                $validated['nominal'], 
                $bulanNama[$validated['bulan']], 
                $validated['tahun'], 
                $arrearsInfo, 
                'manual'
            );
        }
        
        return redirect()->route('bendahara.syahriah')
            ->with('success', 'Data syahriah berhasil ditambahkan');
    }
    
    // Syahriah - Update
    public function updateSyahriah(Request $request, $id)
    {
        $syahriah = Syahriah::findOrFail($id);
        
        // Handle nominal formatting (remove dots from thousands separator)
        if ($request->filled('nominal')) {
            $request->merge(['nominal' => str_replace('.', '', $request->nominal)]);
        }
        
        $validated = $request->validate([
            'nominal' => 'required|numeric|min:0', // SECURITY: Allow admin to edit nominal
            'is_lunas' => 'required|boolean',
            'tanggal_bayar' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);
        
        $syahriah->update($validated);
        
        return redirect()->route('bendahara.syahriah')
            ->with('success', 'Data syahriah berhasil diperbarui');
    }
    
    // Syahriah - Delete
    public function destroySyahriah($id)
    {
        $syahriah = Syahriah::findOrFail($id);
        $syahriah->delete();
        
        return redirect()->route('bendahara.syahriah')
            ->with('success', 'Data syahriah berhasil dihapus');
    }
    
    // Pemasukan - Index
    public function pemasukan(Request $request)
    {
        $query = Pemasukan::query();
        
        if ($request->filled('tanggal_mulai')) {
            $query->where('tanggal', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->where('tanggal', '<=', $request->tanggal_selesai);
        }
        
        $pemasukan = $query->latest('tanggal')->paginate(15);
        
        return view('bendahara.pemasukan.index', compact('pemasukan'));
    }
    
    // Pemasukan - Store
    public function storePemasukan(Request $request)
    {
        if ($request->filled('nominal')) {
            $request->merge(['nominal' => str_replace('.', '', $request->nominal)]);
        }

        $validated = $request->validate([
            'sumber_pemasukan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'kategori' => 'required|string',
        ]);
        
        $validated['tahun_ajaran_id'] = \App\Helpers\AcademicHelper::activeYearId();
        $pemasukan = Pemasukan::create($validated);
        
        // Send Telegram notification
        try {
            $telegram = new \App\Services\TelegramService();
            $telegram->notify(
                'PEMASUKAN BARU',
                "💵 Sumber: {$validated['sumber_pemasukan']}\n" .
                "💰 Nominal: Rp " . number_format($validated['nominal'], 0, ',', '.') . "\n" .
                "📝 Kategori: {$validated['kategori']}\n" .
                "📅 Tanggal: " . date('d M Y', strtotime($validated['tanggal'])),
                '📥'
            );
        } catch (\Exception $e) {
            Log::warning('Telegram notification failed: ' . $e->getMessage());
        }

        // WA NOTIFICATION (Admin Group)
        try {
            $adminGroupId = config('services.fonnte.admin_group');
            if ($adminGroupId) {
                $fonnteService = app(\App\Services\FonnteService::class);
                $fonnteService->notifyIncome(
                    $adminGroupId,
                    $validated['sumber_pemasukan'],
                    $request->kategori_lain ?? $validated['kategori'],
                    str_replace('.', '', $request->nominal), // Raw nominal
                    $validated['tanggal'],
                    $validated['keterangan'] ?? '-',
                    Auth::user()->name ?? 'Admin'
                );
            }
        } catch (\Exception $e) {
            Log::warning('WA Notification failed: ' . $e->getMessage());
        }
        
        return redirect()->route('bendahara.pemasukan')
            ->with('success', 'Data pemasukan berhasil ditambahkan');
    }
    
    // Pemasukan - Update
    public function updatePemasukan(Request $request, $id)
    {
        $pemasukan = Pemasukan::findOrFail($id);
        
        if ($request->filled('nominal')) {
            $request->merge(['nominal' => str_replace('.', '', $request->nominal)]);
        }

        $validated = $request->validate([
            'sumber_pemasukan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'kategori' => 'required|string',
        ]);
        
        $pemasukan->update($validated);
        
        return redirect()->route('bendahara.pemasukan')
            ->with('success', 'Data pemasukan berhasil diperbarui');
    }
    
    // Pemasukan - Delete
    public function destroyPemasukan($id)
    {
        $pemasukan = Pemasukan::findOrFail($id);
        $pemasukan->delete();
        
        return redirect()->route('bendahara.pemasukan')
            ->with('success', 'Data pemasukan berhasil dihapus');
    }
    
    // Pengeluaran - Index
    public function pengeluaran(Request $request)
    {
        $query = Pengeluaran::query();
        
        if ($request->filled('tanggal_mulai')) {
            $query->where('tanggal', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->where('tanggal', '<=', $request->tanggal_selesai);
        }
        
        $pengeluaran = $query->latest('tanggal')->paginate(15);
        
        return view('bendahara.pengeluaran.index', compact('pengeluaran'));
    }
    
    // Pengeluaran - Store
    public function storePengeluaran(Request $request)
    {
        if ($request->filled('nominal')) {
            $request->merge(['nominal' => str_replace('.', '', $request->nominal)]);
        }

        $validated = $request->validate([
            'jenis_pengeluaran' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'kategori' => 'required|string',
        ]);
        
        $validated['tahun_ajaran_id'] = \App\Helpers\AcademicHelper::activeYearId();
        $pengeluaran = Pengeluaran::create($validated);
        
        // Send Telegram notification
        try {
            $telegram = new \App\Services\TelegramService();
            $telegram->notify(
                'PENGELUARAN BARU',
                "🏷️ Jenis: {$validated['jenis_pengeluaran']}\n" .
                "💸 Nominal: Rp " . number_format($validated['nominal'], 0, ',', '.') . "\n" .
                "📝 Kategori: {$validated['kategori']}\n" .
                "📅 Tanggal: " . date('d M Y', strtotime($validated['tanggal'])),
                '📤'
            );
        } catch (\Exception $e) {
            Log::warning('Telegram notification failed: ' . $e->getMessage());
        }

        // WA NOTIFICATION (Admin Group)
        try {
            $adminGroupId = config('services.fonnte.admin_group');
            if ($adminGroupId) {
                $fonnteService = app(\App\Services\FonnteService::class);
                $fonnteService->notifyExpense(
                    $adminGroupId,
                    $validated['jenis_pengeluaran'],
                    $request->kategori_lain ?? $validated['kategori'],
                    str_replace('.', '', $request->nominal), // Raw nominal
                    $validated['tanggal'],
                    $validated['keterangan'] ?? '-',
                    Auth::user()->name ?? 'Admin'
                );
            }
        } catch (\Exception $e) {
            Log::warning('WA Notification failed: ' . $e->getMessage());
        }
        
        return redirect()->route('bendahara.pengeluaran')
            ->with('success', 'Data pengeluaran berhasil ditambahkan');
    }
    
    // Pengeluaran - Update
    public function updatePengeluaran(Request $request, $id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        
        if ($request->filled('nominal')) {
            $request->merge(['nominal' => str_replace('.', '', $request->nominal)]);
        }

        $validated = $request->validate([
            'jenis_pengeluaran' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'kategori' => 'required|string',
        ]);
        
        $pengeluaran->update($validated);
        
        return redirect()->route('bendahara.pengeluaran')
            ->with('success', 'Data pengeluaran berhasil diperbarui');
    }
    
    // Pengeluaran - Delete
    public function destroyPengeluaran($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        $pengeluaran->delete();
        
        return redirect()->route('bendahara.pengeluaran')
            ->with('success', 'Data pengeluaran berhasil dihapus');
    }
    
    // Pegawai - Index
    public function pegawai()
    {
        $pegawai = Pegawai::latest()->paginate(15);
        
        return view('bendahara.pegawai.index', compact('pegawai'));
    }
    
    // Pegawai - Store
    public function storePegawai(Request $request)
    {
        $validated = $request->validate([
            'nama_pegawai' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'departemen' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);
        
        Pegawai::create($validated);
        
        return redirect()->route('bendahara.pegawai')
            ->with('success', 'Data pegawai berhasil ditambahkan');
    }
    
    // Pegawai - Update
    public function updatePegawai(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);
        
        $validated = $request->validate([
            'nama_pegawai' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'departemen' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);
        
        $pegawai->update($validated);
        
        return redirect()->route('bendahara.pegawai')
            ->with('success', 'Data pegawai berhasil diperbarui');
    }
    
    // Pegawai - Delete
    public function destroyPegawai($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete();
        
        return redirect()->route('bendahara.pegawai')
            ->with('success', 'Data pegawai berhasil dihapus');
    }
    
    // Gaji - Index
    public function gaji(Request $request)
    {
        $query = GajiPegawai::with('pegawai');
        
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        
        $gaji = $query->latest()->paginate(15);
        $pegawaiList = Pegawai::where('is_active', true)->get();
        
        return view('bendahara.gaji.index', compact('gaji', 'pegawaiList'));
    }
    
    // Gaji - Store
    public function storeGaji(Request $request)
    {
        if ($request->filled('nominal')) {
            $request->merge(['nominal' => str_replace('.', '', $request->nominal)]);
        }

        $validated = $request->validate([
            'pegawai_id' => [
                'required', 
                \Illuminate\Validation\Rule::exists('pegawai', 'id')->where('pesantren_id', Auth::user()->pesantren_id)
            ],
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer',
            'nominal' => 'required|numeric|min:0',
            'is_dibayar' => 'required|boolean',
            'tanggal_bayar' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);
        
        $validated['tahun_ajaran_id'] = \App\Helpers\AcademicHelper::activeYearId();
        $gaji = GajiPegawai::create($validated);
        
        // Send Telegram notification for salary payment
        if ($validated['is_dibayar']) {
            try {
                $telegram = new \App\Services\TelegramService();
                $pegawai = Pegawai::find($validated['pegawai_id']);
                $bulanNama = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                              'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                
                $telegram->notify(
                    'GAJI DIBAYAR',
                    "👤 Pegawai: {$pegawai->nama_pegawai}\n" .
                    "💼 Jabatan: {$pegawai->jabatan}\n" .
                    "💰 Nominal: Rp " . number_format($validated['nominal'], 0, ',', '.') . "\n" .
                    "📅 Periode: {$bulanNama[$validated['bulan']]} {$validated['tahun']}",
                    '💵'
                );
            } catch (\Exception $e) {
                Log::warning('Telegram notification failed: ' . $e->getMessage());
            }
        }
        
        return redirect()->route('bendahara.gaji')
            ->with('success', 'Data gaji berhasil ditambahkan');
    }
    
    // Gaji - Update
    public function updateGaji(Request $request, $id)
    {
        $gaji = GajiPegawai::findOrFail($id);
        
        $validated = $request->validate([
            'is_dibayar' => 'required|boolean',
            'tanggal_bayar' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);
        
        $gaji->update($validated);
        
        return redirect()->route('bendahara.gaji')
            ->with('success', 'Data gaji berhasil diperbarui');
    }
    
    // Gaji - Delete
    public function destroyGaji($id)
    {
        $gaji = GajiPegawai::findOrFail($id);
        $gaji->delete();
        
        return redirect()->route('bendahara.gaji')
            ->with('success', 'Data gaji berhasil dihapus');
    }
    
    // Laporan
    public function laporan()
    {
        return view('bendahara.laporan');
    }
    
    // Export Laporan Syahriah
    public function exportLaporanSyahriah(Request $request)
    {
        $query = Syahriah::with('santri');
        
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        
        $syahriah = $query->latest()->get();
        $totalNominal = $syahriah->sum('nominal');
        $totalLunas = $syahriah->where('is_lunas', true)->sum('nominal');
        $totalBelumLunas = $totalNominal - $totalLunas;
        
        return response()->view('bendahara.exports.laporan-syahriah', compact('syahriah', 'totalNominal', 'totalLunas', 'totalBelumLunas', 'request'))
            ->header('Content-Type', 'text/html');
    }
    
    // Export Laporan Pemasukan
    public function exportLaporanPemasukan(Request $request)
    {
        $query = Pemasukan::query();
        
        if ($request->filled('tanggal_mulai')) {
            $query->where('tanggal', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->where('tanggal', '<=', $request->tanggal_selesai);
        }
        
        $pemasukan = $query->latest('tanggal')->get();
        $totalPemasukan = $pemasukan->sum('nominal');
        
        return response()->view('bendahara.exports.laporan-pemasukan', compact('pemasukan', 'totalPemasukan', 'request'))
            ->header('Content-Type', 'text/html');
    }
    
    // Export Laporan Pengeluaran
    public function exportLaporanPengeluaran(Request $request)
    {
        $query = Pengeluaran::query();
        
        if ($request->filled('tanggal_mulai')) {
            $query->where('tanggal', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->where('tanggal', '<=', $request->tanggal_selesai);
        }
        
        $pengeluaran = $query->latest('tanggal')->get();
        $totalPengeluaran = $pengeluaran->sum('nominal');
        
        return response()->view('bendahara.exports.laporan-pengeluaran', compact('pengeluaran', 'totalPengeluaran', 'request'))
            ->header('Content-Type', 'text/html');
    }
    
    // Export Laporan Kas
    public function exportLaporanKas(Request $request)
    {
        $queryPemasukan = Pemasukan::query();
        $queryPengeluaran = Pengeluaran::query();
        
        if ($request->filled('tanggal_mulai')) {
            $queryPemasukan->where('tanggal', '>=', $request->tanggal_mulai);
            $queryPengeluaran->where('tanggal', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $queryPemasukan->where('tanggal', '<=', $request->tanggal_selesai);
            $queryPengeluaran->where('tanggal', '<=', $request->tanggal_selesai);
        }
        
        $pemasukan = $queryPemasukan->latest('tanggal')->get();
        $pengeluaran = $queryPengeluaran->latest('tanggal')->get();
        
        $totalPemasukan = $pemasukan->sum('nominal');
        $totalPengeluaran = $pengeluaran->sum('nominal');
        $saldoKas = $totalPemasukan - $totalPengeluaran;
        
        return response()->view('bendahara.exports.laporan-kas', compact('pemasukan', 'pengeluaran', 'totalPemasukan', 'totalPengeluaran', 'saldoKas', 'request'))
            ->header('Content-Type', 'text/html');
    }
    
    // Export Laporan Gaji
    public function exportLaporanGaji(Request $request)
    {
        $query = GajiPegawai::with('pegawai');
        
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        
        $gaji = $query->latest()->get();
        $totalGaji = $gaji->sum('nominal');
        $totalDibayar = $gaji->where('is_dibayar', true)->sum('nominal');
        $totalBelumDibayar = $totalGaji - $totalDibayar;
        
        return response()->view('bendahara.exports.laporan-gaji', compact('gaji', 'totalGaji', 'totalDibayar', 'totalBelumDibayar', 'request'))
            ->header('Content-Type', 'text/html');
    }
    
    // Export Laporan Keuangan Lengkap
    public function exportLaporanKeuanganLengkap(Request $request)
    {
        $querySyahriah = Syahriah::with('santri');
        $queryPemasukan = Pemasukan::query();
        $queryPengeluaran = Pengeluaran::query();
        $queryGaji = GajiPegawai::with('pegawai');
        
        if ($request->filled('tahun')) {
            $querySyahriah->where('tahun', $request->tahun);
            $queryGaji->where('tahun', $request->tahun);
            $queryPemasukan->whereYear('tanggal', $request->tahun);
            $queryPengeluaran->whereYear('tanggal', $request->tahun);
        }
        
        $syahriah = $querySyahriah->latest()->get();
        $pemasukan = $queryPemasukan->latest('tanggal')->get();
        $pengeluaran = $queryPengeluaran->latest('tanggal')->get();
        $gaji = $queryGaji->latest()->get();
        
        $totalSyahriah = $syahriah->sum('nominal');
        $totalPemasukan = $pemasukan->sum('nominal');
        $totalPengeluaran = $pengeluaran->sum('nominal');
        $totalGaji = $gaji->sum('nominal');
        $saldoKas = $totalPemasukan - $totalPengeluaran;
        
        return response()->view('bendahara.exports.laporan-keuangan-lengkap', compact(
            'syahriah', 'pemasukan', 'pengeluaran', 'gaji',
            'totalSyahriah', 'totalPemasukan', 'totalPengeluaran', 'totalGaji', 'saldoKas',
            'request'
        ))->header('Content-Type', 'text/html');
    }

    // Cek Tunggakan (New Feature)
    // Cek Tunggakan (New Feature)
    public function cekTunggakan(Request $request)
    {
        $query = Santri::where('is_active', true)->with(['kelas', 'asrama', 'kobong']);

        // Apply Filters
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }
        if ($request->filled('asrama_id')) {
            $query->where('asrama_id', $request->asrama_id);
        }
        if ($request->filled('kobong_id')) {
            $query->where('kobong_id', $request->kobong_id);
        }
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $santriList = $query->orderBy('nama_santri')->get();
        
        // Data for Filters
        $kelasList = \App\Models\Kelas::all();
        $asramaList = \App\Models\Asrama::all();
        $kobongList = \App\Models\Kobong::all();

        // Calculate Arrears for All Santri
        $biayaBulanan = 500000; // Default Rp 500.000
        $endDate = now();
        $santriWithArrears = [];

        foreach ($santriList as $santri) {
            $startDate = $santri->tanggal_masuk ?? $santri->created_at;
            
            // Generate all months from start to now
            $allMonths = [];
            $current = $startDate->copy()->startOfMonth();
            while ($current <= $endDate) {
                $allMonths[] = $current->month . '-' . $current->year;
                $current->addMonth();
            }

            // Get paid months
            $paidMonths = Syahriah::where('santri_id', $santri->id)
                ->where('is_lunas', true)
                ->get()
                ->map(fn($item) => $item->bulan . '-' . $item->tahun)
                ->toArray();

            // Count unpaid months
            $unpaidCount = 0;
            foreach ($allMonths as $monthKey) {
                if (!in_array($monthKey, $paidMonths)) {
                    $unpaidCount++;
                }
            }

            // Only include santri with arrears
            if ($unpaidCount > 0) {
                $santriWithArrears[] = [
                    'santri' => $santri,
                    'unpaid_months' => $unpaidCount,
                    'total_arrears' => $unpaidCount * $biayaBulanan,
                ];
            }
        }

        // Calculate Summary Stats (before pagination)
        $totalSantriMenunggak = count($santriWithArrears);
        $grandTotalBulan = array_sum(array_column($santriWithArrears, 'unpaid_months'));
        $grandTotalRupiah = array_sum(array_column($santriWithArrears, 'total_arrears'));

        // Pagination (manual for array)
        $perPage = 10;
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedItems = array_slice($santriWithArrears, $offset, $perPage);
        $santriWithArrearsPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems,
            $totalSantriMenunggak,
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('bendahara.cek-tunggakan.index', compact(
            'santriList', 'kelasList', 'asramaList', 'kobongList', 
            'santriWithArrearsPaginated', 'biayaBulanan',
            'totalSantriMenunggak', 'grandTotalBulan', 'grandTotalRupiah'
        ));
    }

    public function prosesCekTunggakan(Request $request)
    {
        // Sanitize input (remove dots, 'Rp', spaces, etc. - keep only digits)
        if ($request->has('biaya_bulanan')) {
            $request->merge([
                'biaya_bulanan' => preg_replace('/[^0-9]/', '', $request->biaya_bulanan)
            ]);
        }

        $request->validate([
            'santri_id' => 'required|exists:santri,id',
            'biaya_bulanan' => 'required|numeric|min:0',
        ]);

        $santri = Santri::with(['kelas', 'asrama'])->findOrFail($request->santri_id);
        
        // Determine Start Date (Support Legacy Data)
        // Logic: 
        // 1. If tanggal_masuk exists, use it.
        // 2. If not, use created_at.
        $startDate = $santri->tanggal_masuk ?? $santri->created_at;
        $endDate = now();

        $biayaBulanan = str_replace('.', '', $request->biaya_bulanan);
        
        // Generate List of All Months from Start to End
        $allMonths = [];
        $current = $startDate->copy()->startOfMonth();
        
        while ($current <= $endDate) {
            $allMonths[] = [
                'bulan' => $current->month,
                'tahun' => $current->year,
                'label' => $current->format('F Y'),
                'date_obj' => $current->copy()
            ];
            $current->addMonth();
        }

        // Get All Paid Months for this Santri
        $paidMonths = Syahriah::where('santri_id', $santri->id)
            ->where('is_lunas', true)
            ->get()
            ->map(function ($item) {
                return $item->bulan . '-' . $item->tahun;
            })
            ->toArray();

        // Calculate Arrears (Tunggakan)
        $tunggakanList = [];
        $totalTunggakan = 0;

        foreach ($allMonths as $month) {
            $key = $month['bulan'] . '-' . $month['tahun'];
            
            if (!in_array($key, $paidMonths)) {
                // Check if there is a partial payment or unpaid record
                $record = Syahriah::where('santri_id', $santri->id)
                    ->where('bulan', $month['bulan'])
                    ->where('tahun', $month['tahun'])
                    ->first();
                
                $status = 'Belum Bayar';
                $nominalBayar = 0;
                
                if ($record) {
                    $status = $record->is_lunas ? 'Lunas' : 'Belum Lunas'; // Should be covered by paidMonths check, but safe double check
                    $nominalBayar = $record->nominal;
                }

                $tunggakanList[] = [
                    'bulan' => $month['bulan'],
                    'tahun' => $month['tahun'],
                    'label' => $month['label'],
                    'status' => $status,
                    'tagihan' => $biayaBulanan
                ];
                
                $totalTunggakan += $biayaBulanan;
            }
        }

        return view('bendahara.cek-tunggakan.result', compact('santri', 'tunggakanList', 'totalTunggakan', 'biayaBulanan'));
    }

    public function exportLaporanTunggakan(Request $request)
    {
        $query = Santri::where('is_active', true)->with(['kelas', 'asrama', 'kobong']);

        // Apply Filters
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }
        if ($request->filled('asrama_id')) {
            $query->where('asrama_id', $request->asrama_id);
        }
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $santriList = $query->orderBy('nama_santri')->get();
        
        // Data for Filters (for display)
        $kelasList = \App\Models\Kelas::all();
        $asramaList = \App\Models\Asrama::all();

        // Calculate Arrears for All Santri
        $biayaBulanan = 500000;
        $endDate = now();
        $santriWithArrears = [];

        foreach ($santriList as $santri) {
            $startDate = $santri->tanggal_masuk ?? $santri->created_at;
            
            $allMonths = [];
            $current = $startDate->copy()->startOfMonth();
            while ($current <= $endDate) {
                $allMonths[] = $current->month . '-' . $current->year;
                $current->addMonth();
            }

            $paidMonths = Syahriah::where('santri_id', $santri->id)
                ->where('is_lunas', true)
                ->get()
                ->map(fn($item) => $item->bulan . '-' . $item->tahun)
                ->toArray();

            $unpaidCount = 0;
            foreach ($allMonths as $monthKey) {
                if (!in_array($monthKey, $paidMonths)) {
                    $unpaidCount++;
                }
            }

            if ($unpaidCount > 0) {
                $santriWithArrears[] = [
                    'santri' => $santri,
                    'unpaid_months' => $unpaidCount,
                    'total_arrears' => $unpaidCount * $biayaBulanan,
                ];
            }
        }

        $totalSantriMenunggak = count($santriWithArrears);
        $grandTotalBulan = array_sum(array_column($santriWithArrears, 'unpaid_months'));
        $grandTotalRupiah = array_sum(array_column($santriWithArrears, 'total_arrears'));

        return response()->view('bendahara.exports.laporan-tunggakan', compact(
            'santriWithArrears', 'biayaBulanan', 'kelasList', 'asramaList',
            'totalSantriMenunggak', 'grandTotalBulan', 'grandTotalRupiah'
        ))->header('Content-Type', 'text/html');
    }
    public function getBillingTargets()
    {
        $query = Santri::where('is_active', true)->with(['kelas', 'asrama', 'kobong']);
        $santriList = $query->get();
        
        $biayaBulanan = 500000;
        $endDate = now();
        $targets = [];
        
        foreach ($santriList as $santri) {
            $startDate = $santri->tanggal_masuk ?? $santri->created_at;
            $allMonths = [];
            $current = $startDate->copy()->startOfMonth();
            while ($current <= $endDate) {
                $allMonths[] = $current->month . '-' . $current->year;
                $current->addMonth();
            }

            $paidMonths = Syahriah::where('santri_id', $santri->id)
                ->where('is_lunas', true)
                ->get()
                ->map(fn($item) => $item->bulan . '-' . $item->tahun)
                ->toArray();

            $unpaidCount = 0;
            foreach ($allMonths as $monthKey) {
                if (!in_array($monthKey, $paidMonths)) {
                    $unpaidCount++;
                }
            }

            if ($unpaidCount > 0) {
                $targets[] = [
                    'id' => $santri->id,
                    'nama' => $santri->nama_santri,
                    'phone' => $santri->no_hp_ortu_wali,
                    'unpaid_months' => $unpaidCount,
                    'total_arrears' => $unpaidCount * $biayaBulanan,
                    'kelas' => $santri->kelas->nama_kelas ?? '-'
                ];
            }
        }
        
        return response()->json([
            'count' => count($targets),
            'targets' => $targets
        ]);
    }

    public function sendBillingNotification(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:santri,id',
            'phone' => 'required',
            'unpaid_months' => 'required',
            'total_arrears' => 'required'
        ]);

        $santri = Santri::find($request->id);
        $phone = $request->phone;
        
        // Format Phone (remove 0/62 prefix, etc handled by Service usually, but let's ensure)
        
        $message = "Assalamu'alaikum Wr. Wb.\n\n" .
            "Yth. Wali dari Ananda *{$santri->nama_santri}*\n" .
            "NIS: {$santri->nis}\n" .
            "Kelas: " . ($santri->kelas->nama_kelas ?? '-') . "\n\n" .
            "Kami informasikan bahwa terdapat *tunggakan Syahriah* sebanyak {$request->unpaid_months} bulan.\n\n" .
            "💰 *Total Tunggakan:* Rp " . number_format($request->total_arrears, 0, ',', '.') . "\n\n" .
            "Mohon dapat melunasi melalui Bendahara " . (Auth::user()->pesantren->nama_pesantren ?? 'Pesantren') . ".\n\n" .
            "Jazakumullahu Khairan.\n" .
            "_Bendahara_";

        try {
            $fonnte = app(\App\Services\FonnteService::class);
            $fonnte->sendMessage($phone, $message);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error("WA Blast Error: " . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    // ... (rest of the file)

    /**
     * Get financial summary (Income, Expense, Syahriah)
     */
    private function getFinancialSummary($cacheKey, $tahun, $bulan)
    {
        // Cache KPI queries (5 minutes)
        $totalPemasukan = Cache::remember("{$cacheKey}_pemasukan", 300, function() use ($tahun, $bulan) {
            $query = Pemasukan::whereYear('tanggal', $tahun);
            if ($bulan) $query->whereMonth('tanggal', $bulan);
            return $query->sum('nominal');
        });
        
        $totalPengeluaran = Cache::remember("{$cacheKey}_pengeluaran", 300, function() use ($tahun, $bulan) {
            $query = Pengeluaran::whereYear('tanggal', $tahun);
            if ($bulan) $query->whereMonth('tanggal', $bulan);
            return $query->sum('nominal');
        });
        
        $syahriahData = Cache::remember("{$cacheKey}_syahriah", 300, function() use ($tahun, $bulan) {
            $query = Syahriah::where('tahun', $tahun);
            if ($bulan) $query->where('bulan', $bulan);
            return [
                'total' => $query->sum('nominal'),
                'lunas' => (clone $query)->where('is_lunas', true)->sum('nominal')
            ];
        });

        return [
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'totalSyahriah' => $syahriahData['total'],
            'syahriahLunas' => $syahriahData['lunas'],
            'saldoDana' => ($totalPemasukan + $syahriahData['lunas']) - $totalPengeluaran
        ];
    }

    /**
     * Get Santri counts based on filters
     */
    private function getSantriCounts($cacheKey, $filters)
    {
        return Cache::remember("{$cacheKey}_santri_counts", 300, function() use ($filters) {
            $query = Santri::where('is_active', true);
            if ($filters['kelasId']) $query->where('kelas_id', $filters['kelasId']);
            if ($filters['asramaId']) $query->where('asrama_id', $filters['asramaId']);
            if ($filters['kobongId']) $query->where('kobong_id', $filters['kobongId']);
            if ($filters['gender']) $query->where('gender', $filters['gender']);
            
            return [
                'total' => $query->count(),
                'putra' => (clone $query)->where('gender', 'putra')->count(),
                'putri' => (clone $query)->where('gender', 'putri')->count()
            ];
        });
    }

    /**
     * Calculate Arrears (Tunggakan)
     */
    private function getTunggakanData($cacheKey)
    {
        return Cache::remember("{$cacheKey}_tunggakan", 300, function() {
            $endDate = now();
            $totalTunggakan = 0;
            $totalSantriMenunggak = 0;
            
            // Get all academic years for fee lookup
            $academicYears = \App\Models\TahunAjaran::select(['id', 'nominal_syahriah', 'tanggal_mulai', 'tanggal_selesai'])
                ->orderBy('tanggal_mulai')
                ->get();

            // Default fee if no academic year matches (fallback)
            $defaultFee = $academicYears->where('is_active', true)->value('nominal_syahriah') ?? 500000;
            
            $allSantri = Santri::where('is_active', true)->get();
            foreach ($allSantri as $santri) {
                $startDate = $santri->tanggal_masuk ?? $santri->created_at;
                $current = $startDate->copy()->startOfMonth();
                $santriTunggakan = 0;

                // Get paid months [month-year]
                $paidMonths = Syahriah::where('santri_id', $santri->id)
                    ->where('is_lunas', true)
                    ->get()
                    ->map(fn($item) => $item->bulan . '-' . $item->tahun)
                    ->toArray();

                while ($current <= $endDate) {
                    $monthKey = $current->month . '-' . $current->year;
                    
                    if (!in_array($monthKey, $paidMonths)) {
                        $applicableFee = $defaultFee;
                        foreach ($academicYears as $ta) {
                            if ($current->between($ta->tanggal_mulai, $ta->tanggal_selesai)) {
                                $applicableFee = $ta->nominal_syahriah;
                                break;
                            }
                        }
                        $santriTunggakan += $applicableFee;
                    }
                    $current->addMonth();
                }

                if ($santriTunggakan > 0) {
                    $totalTunggakan += $santriTunggakan;
                    $totalSantriMenunggak++;
                }
            }
            
            return [
                'total' => $totalTunggakan,
                'count' => $totalSantriMenunggak
            ];
        });
    }

    /**
     * Get Paid Santri Metrics
     */
    private function getSantriLunasMetrics($cacheKey, $tahun, $bulan)
    {
        return Cache::remember("{$cacheKey}_santri_lunas", 300, function() use ($tahun, $bulan) {
            $query = Syahriah::where('tahun', $tahun);
            if ($bulan) $query->where('bulan', $bulan);
            $santriIdsLunas = $query->where('is_lunas', true)->pluck('santri_id')->unique();
            
            return [
                'putra' => Santri::whereIn('id', $santriIdsLunas)->where('gender', 'putra')->where('is_active', true)->count(),
                'putri' => Santri::whereIn('id', $santriIdsLunas)->where('gender', 'putri')->where('is_active', true)->count()
            ];
        });
    }

    /**
     * Get Gaji Summary
     */
    private function getGajiSummary($cacheKey, $tahun, $bulan)
    {
        return Cache::remember("{$cacheKey}_gaji", 300, function() use ($tahun, $bulan) {
            $query = GajiPegawai::where('tahun', $tahun);
            if ($bulan) $query->where('bulan', $bulan);
            
            return [
                'bulan_ini' => (clone $query)->where('bulan', now()->month)->sum('nominal'),
                'tertunda' => (clone $query)->where('is_dibayar', false)->sum('nominal'),
                'count_tertunda' => GajiPegawai::where('is_dibayar', false)->count()
            ];
        });
    }

    /**
     * Get Lists for Dashboard (Menunggak, Recent Transactions)
     */
    private function getDashboardLists($cacheKey, $tahun, $bulan)
    {
        // Lists - Santri Menunggak (cached for 2 minutes)
        $santriMenunggak = Cache::remember("{$cacheKey}_santri_menunggak", 120, function() use ($tahun, $bulan) {
            $query = Syahriah::where('tahun', $tahun);
            if ($bulan) $query->where('bulan', $bulan);
            $santriIdsMenunggak = $query->where('is_lunas', false)->pluck('santri_id')->unique();
            
            return [
                'putra' => Santri::whereIn('id', $santriIdsMenunggak)
                    ->where('gender', 'putra')
                    ->where('is_active', true)
                    ->with(['kelas', 'asrama'])
                    ->limit(10)
                    ->get(),
                'putri' => Santri::whereIn('id', $santriIdsMenunggak)
                    ->where('gender', 'putri')
                    ->where('is_active', true)
                    ->with(['kelas', 'asrama'])
                    ->limit(10)
                    ->get()
            ];
        });

        // Recent Transactions (cached for 1 minute)
        // Note: Using arrays in select() to avoid linter warnings
        $recentTransactions = Cache::remember('recent_transactions_all', 60, function() {
            return [
                'syahriah' => Syahriah::with('santri:id,nama_santri')
                    ->select(['id', 'santri_id', 'bulan', 'tahun', 'nominal', 'is_lunas', 'created_at'])
                    ->latest()
                    ->limit(10)
                    ->get(),
                'pemasukan' => Pemasukan::select(['id', 'tanggal', 'kategori', 'nominal', 'keterangan', 'created_at'])
                    ->latest()
                    ->limit(5)
                    ->get(),
                'pengeluaran' => Pengeluaran::select(['id', 'tanggal', 'jenis_pengeluaran', 'nominal', 'keterangan', 'created_at'])
                    ->latest()
                    ->limit(5)
                    ->get(),
                'gaji' => GajiPegawai::with('pegawai:id,nama_pegawai')
                    ->select(['id', 'pegawai_id', 'bulan', 'tahun', 'nominal', 'is_dibayar', 'created_at'])
                    ->latest()
                    ->limit(5)
                    ->get()
            ];
        });

        return array_merge(['menunggak' => $santriMenunggak], $recentTransactions);
    }
}
