<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\{
    Pesantren, ActivityLog, JurnalKbm, AbsensiKbmDetail, AbsensiGuru,
    NilaiSantri, MutasiSantri, UjianMingguan, AbsensiSantri, Syahriah,
    Talaran, RiwayatKelas, Kobong, JadwalPelajaran, GajiPegawai,
    Pegawai, Pemasukan, Pengeluaran, TahunAjaran, KalenderPendidikan,
    IjazahSetting, ReportSettings, Setting, User, LoginVerification,
    TalaranMingguan
};

class PesantrenController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesantren::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('subdomain', 'like', "%{$search}%")
                  ->orWhereHas('admin', function($qa) use ($search) {
                      $qa->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter: Status
        if ($request->filled('status')) {
             $today = now();
             if ($request->status == 'active') {
                 $query->whereDate('expired_at', '>=', $today)->where('status', 'active');
             } elseif ($request->status == 'expired') {
                 $query->whereDate('expired_at', '<', $today);
             } elseif ($request->status == 'suspended') {
                 $query->where('status', 'suspended');
             }
        }

        // Filter: Package
        if ($request->filled('package')) {
            $query->where('package', $request->package);
        }

        $pesantrens = $query->with('admin')->latest()->paginate(20);

        $packages = \App\Models\Package::orderBy('sort_order')->get();
        return view('owner.pesantren.index', compact('pesantrens', 'packages'));
    }

    public function show($id)
    {
        $pesantren = Pesantren::with(['subscriptions', 'invoices', 'admin'])->findOrFail($id);
        return view('owner.pesantren.show', compact('pesantren'));
    }

    public function edit($id)
    {
        $pesantren = Pesantren::findOrFail($id);
        return view('owner.pesantren.edit', compact('pesantren'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'package' => 'required|in:basic,advance,enterprise,trial',
            'expired_at' => 'required|date',
            'status' => 'required|in:active,suspended',
            'bank_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
            'account_name' => 'nullable|string|max:100',
        ];

        // Advance Package Funding Requirement
        if ($request->package === 'advance' || $request->package === 'enterprise') {
            $rules['bank_name'] = 'required';
            $rules['account_number'] = 'required';
            $rules['account_name'] = 'required';
        }

        $request->validate($rules);

        $pesantren = Pesantren::findOrFail($id);
        $pesantren->update([
            'package' => $request->package,
            'expired_at' => $request->expired_at,
            'status' => $request->status,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
        ]);

        ActivityLog::logActivity(
            'Updated subscription for tenant: ' . $pesantren->nama,
            $pesantren,
            ['old' => $pesantren->getOriginal(), 'new' => $pesantren->getAttributes()],
            'updated'
        );

        return redirect()->route('owner.pesantren.show', $id)->with('success', 'Tenant updated successfully.');
    }

    public function suspend($id)
    {
        $pesantren = Pesantren::findOrFail($id);
        
        $newStatus = $pesantren->status === 'suspended' ? 'active' : 'suspended';
        $pesantren->update(['status' => $newStatus]);
        
        $message = $newStatus === 'suspended' ? 'Tenant has been suspended.' : 'Tenant has been reactivated.';

        ActivityLog::logActivity(
            $newStatus === 'suspended' ? 'Suspended tenant: ' . $pesantren->nama : 'Reactivated tenant: ' . $pesantren->nama,
            $pesantren,
            ['status' => $newStatus],
            $newStatus === 'suspended' ? 'suspended' : 'reactivated'
        );

        return back()->withErrors(['message' => $message]);
    }

    public function destroy($id)
    {
        try {
            $pesantren = Pesantren::findOrFail($id);
            
            DB::transaction(function () use ($pesantren) {
                $this->deletePesantrenData($pesantren);
            });

            return redirect()->route('owner.pesantren.index')->with('success', 'Tenant ' . $pesantren->nama . ' and ALL its data have been deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete tenant: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete tenant: ' . $e->getMessage()]);
        }
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:pesantrens,id',
        ]);

        try {
            $count = 0;
            DB::transaction(function () use ($request, &$count) {
                foreach ($request->ids as $id) {
                    $pesantren = Pesantren::find($id);
                    if ($pesantren) {
                        $this->deletePesantrenData($pesantren);
                        $count++;
                    }
                }
            });

            return redirect()->route('owner.pesantren.index')->with('success', $count . ' Tenants and their data have been deleted successfully.');
        } catch (\Exception $e) {
             Log::error('Failed to bulk delete tenants: ' . $e->getMessage());
             return back()->withErrors(['error' => 'Failed to delete tenants: ' . $e->getMessage()]);
        }
    }

    private function deletePesantrenData(Pesantren $pesantren)
    {
        // Log activity before deletion
        ActivityLog::logActivity(
            'Deleted tenant: ' . $pesantren->nama,
            $pesantren,
            ['id' => $pesantren->id, 'nama' => $pesantren->nama, 'subdomain' => $pesantren->subdomain],
            'deleted'
        );

        $this->deleteKbmData($pesantren);
        $this->deleteSantriData($pesantren);
        $this->deleteInfrastructureData($pesantren);
        $this->deleteFinanceData($pesantren);
        $this->deleteSettingsData($pesantren);
        $this->deleteUserData($pesantren);

        // 7. FINALLY DELETE THE TENANT
        $pesantren->delete();
    }

    private function deleteKbmData(Pesantren $pesantren)
    {
        // 1. DELETE KBM DATA (Jurnal, Absensi Guru)
        $jurnalIds = JurnalKbm::where('pesantren_id', $pesantren->id)->pluck('id');
        if ($jurnalIds->count() > 0) {
            AbsensiKbmDetail::whereIn('jurnal_kbm_id', $jurnalIds)->delete();
            JurnalKbm::whereIn('id', $jurnalIds)->delete();
        }
        AbsensiGuru::where('pesantren_id', $pesantren->id)->delete();
    }

    private function deleteSantriData(Pesantren $pesantren)
    {
        // 2. DELETE SANTRI DATA & ACADEMIC RECORDS
        $santriIds = $pesantren->santri()->pluck('id');
        if ($santriIds->count() > 0) {
            // Delete related santri data first
            NilaiSantri::whereIn('santri_id', $santriIds)->delete();
            MutasiSantri::whereIn('santri_id', $santriIds)->delete();
            UjianMingguan::whereIn('santri_id', $santriIds)->delete();
            AbsensiSantri::whereIn('santri_id', $santriIds)->delete();
            Syahriah::whereIn('santri_id', $santriIds)->delete();
            
            // Talaran uses SoftDeletes, force delete
            Talaran::whereIn('santri_id', $santriIds)->forceDelete();
            
            TalaranMingguan::whereIn('santri_id', $santriIds)->delete();
            
            RiwayatKelas::whereIn('santri_id', $santriIds)->delete();
            
            $pesantren->santri()->delete();
        }
    }

    private function deleteInfrastructureData(Pesantren $pesantren)
    {
        // 3. DELETE INFRASTRUCTURE (Asrama, Kelas)
        $asramaIds = $pesantren->asrama()->pluck('id');
        if ($asramaIds->count() > 0) {
            Kobong::whereIn('asrama_id', $asramaIds)->delete();
            $pesantren->asrama()->delete();
        }

        $pesantren->kelas()->delete();

        $mapelIds = $pesantren->mataPelajaran()->pluck('id');
        if ($mapelIds->count() > 0) {
            JadwalPelajaran::whereIn('mapel_id', $mapelIds)->delete();
        }
        $pesantren->mataPelajaran()->delete();
    }

    private function deleteFinanceData(Pesantren $pesantren)
    {
        // 4. DELETE FINANCE & HR DATA
        GajiPegawai::where('pesantren_id', $pesantren->id)->delete();
        Pegawai::where('pesantren_id', $pesantren->id)->delete();
        
        $pesantren->invoices()->delete();
        $pesantren->subscriptions()->delete();
        $pesantren->withdrawals()->delete();

        Pemasukan::where('pesantren_id', $pesantren->id)->delete();
        Pengeluaran::where('pesantren_id', $pesantren->id)->delete();
    }

    private function deleteSettingsData(Pesantren $pesantren)
    {
        // 5. DELETE SETTINGS & CONFIG
        TahunAjaran::where('pesantren_id', $pesantren->id)->delete();
        KalenderPendidikan::where('pesantren_id', $pesantren->id)->delete();
        IjazahSetting::where('pesantren_id', $pesantren->id)->delete();
        ReportSettings::where('pesantren_id', $pesantren->id)->delete();
    }

    private function deleteUserData(Pesantren $pesantren)
    {
        // 6. DELETE USERS & USER DATA
        $userIds = User::where('pesantren_id', $pesantren->id)->pluck('id');
        if ($userIds->count() > 0) {
             // Clean up notifications and logs for these users
             DB::table('notifications')->whereIn('notifiable_id', $userIds)->where('notifiable_type', User::class)->delete();
             ActivityLog::whereIn('user_id', $userIds)->delete();
             LoginVerification::whereIn('user_id', $userIds)->delete();
             
             User::whereIn('id', $userIds)->delete();
        }
    }
}
