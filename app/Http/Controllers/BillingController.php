<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\Syahriah;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BillingController extends Controller
{
    protected $fonnteService;

    public function __construct(FonnteService $fonnteService)
    {
        $this->fonnteService = $fonnteService;
    }

    /**
     * Get list of santri with arrears (targets for billing)
     */
    public function getTargets()
    {
        $santris = Santri::where('is_active', true)
            ->whereNotNull('no_hp_ortu_wali')
            ->where('no_hp_ortu_wali', '!=', '')
            ->get();

        $targets = [];

        foreach ($santris as $santri) {
            // Calculate Arrears
            $unpaidBills = Syahriah::where('santri_id', $santri->id)
                ->where('is_lunas', false)
                ->orderBy('tahun', 'asc')
                ->orderBy('bulan', 'asc')
                ->get();

            if ($unpaidBills->isEmpty()) {
                continue;
            }

            $totalArrears = $unpaidBills->sum('nominal');
            $countMonths = $unpaidBills->count();
            
            // Format message details
            $details = $unpaidBills->take(3)->map(function($bill) {
                $monthName = \Carbon\Carbon::create()->month($bill->bulan)->translatedFormat('F');
                return "$monthName {$bill->tahun}";
            })->implode(', ');

            if ($countMonths > 3) {
                $details .= " dan " . ($countMonths - 3) . " bulan lainnya.";
            }

            $targets[] = [
                'id' => $santri->id,
                'nama' => $santri->nama_santri,
                'phone' => $santri->no_hp_ortu_wali,
                'tunggakan' => $totalArrears,
                'bulan_count' => $countMonths,
                'details' => $details
            ];
        }

        return response()->json([
            'count' => count($targets),
            'targets' => $targets
        ]);
    }

    /**
     * Send reminder to a single target
     */
    public function sendSingleReminder(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'nama' => 'required',
            'santri_id' => 'required|exists:santri,id', // Add santri_id for accurate calculation
        ]);

        $phone = $request->phone;
        $nama = $request->nama;
        $santriId = $request->santri_id;
        
        // FIXED: Fetch actual tunggakan from database instead of hardcoded
        $unpaidBills = Syahriah::where('santri_id', $santriId)
            ->where('is_lunas', false)
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();
        
        $tunggakanCount = $unpaidBills->count();
        $totalTunggakan = $unpaidBills->sum('nominal'); // Use actual nominal from DB
        
        // Format details (first 3 months)
        $details = $unpaidBills->take(3)->map(function($bill) {
            $monthName = \Carbon\Carbon::create()->month($bill->bulan)->translatedFormat('F');
            $amount = number_format($bill->nominal, 0, ',', '.');
            return "$monthName {$bill->tahun} (Rp $amount)";
        })->implode(', ');
        
        if ($tunggakanCount > 3) {
            $details .= " dan " . ($tunggakanCount - 3) . " bulan lainnya";
        }
        
        $formattedTotal = number_format($totalTunggakan, 0, ',', '.');

        $message = "⚠️ *TAGIHAN SYAHRIAH / SPP*\n\n";
        $message .= "Yth. Wali Santri dari *$nama*,\n\n";
        $message .= "Kami informasikan bahwa terdapat tunggakan Syahriah:\n";
        $message .= "📊 Jumlah Bulan: {$tunggakanCount} bulan\n";
        $message .= "💰 Total: *Rp {$formattedTotal}*\n";
        $message .= "📅 Rincian: $details\n\n";
        $message .= "Mohon segera melakukan pembayaran. Abaikan jika sudah membayar.\n";
        $message .= "_Sistem Informasi Riyadlul Huda_";

        $status = $this->fonnteService->sendMessage($phone, $message);

        return response()->json([
            'success' => $status,
            'message' => $status ? 'Terkirim' : 'Gagal Kirim'
        ]);
    }

    /**
     * Show billing overview for tenant admin
     */
    public function index()
    {
        $pesantren = app('tenant');
        
        // Get current subscription
        $subscription = $pesantren->currentSubscription;
        
        // Get invoices (if Invoice model exists)
        $invoices = $pesantren->invoices()->orderBy('created_at', 'desc')->paginate(10);
        
        return view('billing.index', compact('pesantren', 'subscription', 'invoices'));
    }

    /**
     * Show available plans
     */
    public function plans()
    {
        return view('billing.plans');
    }

    /**
     * Show specific invoice/billing detail
     */
    public function show($id)
    {
        $pesantren = app('tenant');
        $invoice = $pesantren->invoices()->findOrFail($id);
        return view('billing.show', compact('invoice', 'pesantren'));
    }

    /**
     * Process payment for billing
     */
    public function pay(Request $request, $id)
    {
        return back()->with('info', 'Sistem pembayaran sedang dalam pemeliharaan.');
    }
}
