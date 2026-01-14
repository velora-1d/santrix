<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MidtransService;
use App\Services\TelegramService;
use App\Services\FonnteService;
use App\Models\Santri;
use App\Models\Syahriah;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MidtransController extends Controller
{
    protected $midtransService;
    protected $telegramService;
    protected $fonnteService;

    public function __construct(MidtransService $midtrans, TelegramService $telegram, FonnteService $fonnte)
    {
        $this->midtransService = $midtrans;
        $this->telegramService = $telegram;
        $this->fonnteService = $fonnte;
    }

    /**
     * Generate VA for a Santri
     */
    public function generateVa(Request $request, Santri $santri)
    {
        // Check if already has VA
        if ($santri->virtual_account_number) {
            return back()->with('error', 'Santri already has a Virtual Account.');
        }

        // Call Service
        $response = $this->midtransService->createTransaction($santri);

        if ($response && isset($response['va_numbers'][0]['va_number'])) {
            $vaNumber = $response['va_numbers'][0]['va_number'];
            
            // Update Santri
            $santri->update(['virtual_account_number' => $vaNumber]);
            
            return back()->with('success', 'Virtual Account berhasil di-generate: ' . $vaNumber);
        } else if ($response && isset($response['permata_va_number'])) {
            // Fallback for Permata
            $vaNumber = $response['permata_va_number'];
            $santri->update(['virtual_account_number' => $vaNumber]);
            return back()->with('success', 'Virtual Account berhasil di-generate: ' . $vaNumber);
        }

        return back()->with('error', 'Gagal generate VA dari Midtrans. Cek log atau pulsa/kuota API.');
    }

    /**
     * Reset VA (Clear VA Number) for a Santri
     */
    public function resetVa(Request $request, Santri $santri)
    {
        $santri->update(['virtual_account_number' => null]);
        return back()->with('success', 'Virtual Account berhasil di-reset. Silakan generate ulang untuk transaksi baru.');
    }

    /**
     * Bulk Generate VA for all Santri without VA
     */
    public function generateVaBulk(Request $request)
    {
        // Increase time limit for bulk process
        set_time_limit(300); // 5 minutes

        $santris = Santri::whereNull('virtual_account_number')
                         ->orWhere('virtual_account_number', '')
                         ->get();

        if ($santris->isEmpty()) {
            return back()->with('info', 'Semua santri sudah memiliki Virtual Account.');
        }

        $successCount = 0;
        $failCount = 0;

        foreach ($santris as $santri) {
            try {
                // Determine nominal (using default)
                $response = $this->midtransService->createTransaction($santri);

                if ($response && isset($response['va_numbers'][0]['va_number'])) {
                    $santri->update(['virtual_account_number' => $response['va_numbers'][0]['va_number']]);
                    $successCount++;
                } else if ($response && isset($response['permata_va_number'])) {
                     $santri->update(['virtual_account_number' => $response['permata_va_number']]);
                     $successCount++;
                } else {
                    $failCount++;
                }
                
                // Optional: Sleep to prevent rate rate limit if needed
                // sleep(1); 
            } catch (\Exception $e) {
                Log::error("Bulk VA Error for ID {$santri->id}: " . $e->getMessage());
                $failCount++;
            }
        }

        return back()->with('success', "Proses Selesai. Sukses: $successCount, Gagal: $failCount");
    }

    /**
     * Bulk Reset VA (Clear All VA Numbers)
     */
    public function resetVaBulk(Request $request)
    {
        // Update all santri set va to null
        Santri::query()->update(['virtual_account_number' => null]);
        
        return back()->with('success', 'Semua Virtual Account berhasil di-reset (dihapus). Silakan generate ulang massal jika diperlukan.');
    }

    /**
     * Handle incoming Webhook from Midtrans
     */
    public function webhook(Request $request)
    {
        Log::info('Midtrans Webhook Received:', $request->all());

        $notification = $request->all();
        $orderId = $notification['order_id'];
        $statusCode = $notification['status_code'];
        $grossAmount = $notification['gross_amount'];
        $signatureKey = $notification['signature_key'];
        $transactionStatus = $notification['transaction_status'];
        $type = $notification['payment_type'];
        $fraudStatus = $notification['fraud_status'] ?? null;

        // 1. Verify Signature
        if (!$this->midtransService->isValidSignature($orderId, $statusCode, $grossAmount, $signatureKey)) {
            Log::warning("Midtrans Invalid Signature", [
                'order_id' => $orderId,
                'ip' => $request->ip()
            ]);
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        // 2. SECURITY: Idempotency Check - Atomic Operation
        DB::beginTransaction();
        try {
            $payment = DB::table('payment_gateway')
                ->where('order_id', $orderId)
                ->lockForUpdate() // Row-level lock
                ->first();
            
            // Check if already processed
            if ($payment && $payment->processed_at) {
                Log::info("Duplicate webhook ignored", ['order_id' => $orderId]);
                DB::commit();
                return response()->json(['message' => 'Already processed'], 200);
            }
            
            // Extract Data from Order ID (Format: SPP-PESANTREN_ID-NIS-TIMESTAMP or INV-...)
            $parts = explode('-', $orderId);
            
            $nis = null;
            if (count($parts) >= 4) {
                 // New Format
                 $pesantrenId = $parts[1];
                 $nis = $parts[2];
            } else {
                 // Fallback/Legacy Format (SPP-NIS-TIMESTAMP)
                 $nis = isset($parts[1]) ? $parts[1] : null;
            }

            // 3. Store/Update log with processed_at
            DB::table('payment_gateway')->updateOrInsert(
                ['order_id' => $orderId],
                [
                    'payment_type' => $type,
                    'transaction_status' => $transactionStatus,
                    'gross_amount' => $grossAmount,
                    'json_response' => json_encode($notification),
                    'processed_at' => now(), // SECURITY: Mark as processed
                    'created_at' => $payment ? $payment->created_at : now(),
                    'updated_at' => now(),
                ]
            );

            // 4. Process Status
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    // TODO: Set pending
                } else if ($fraudStatus == 'accept') {
                    $this->handleSuccess($nis, $grossAmount, $pesantrenId);
                }
            } else if ($transactionStatus == 'settlement') {
                $this->handleSuccess($nis, $grossAmount, $pesantrenId);
            } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                // TODO: Handle Failed
            } else if ($transactionStatus == 'pending') {
                // TODO: Handle Pending
            }
            
            DB::commit();
            return response()->json(['message' => 'OK'], 200);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Webhook processing failed", [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            return response()->json(['message' => 'Processing error'], 500);
        }
    }

    private function handleSuccess($nis, $amount, $pesantrenId = null)
    {
        if (!$nis) return;

        // SECURITY PATCH (VULN-001): Scope by Pesantren ID to prevent NIS collision
        $query = Santri::where('nis', $nis);
        
        if ($pesantrenId) {
            $query->where('pesantren_id', $pesantrenId);
        }
        
        $santri = $query->first();
        
        if (!$santri) {
            Log::warning("Santri not found for NIS: $nis (Pesantren: " . ($pesantrenId ?? 'Unknown') . ")");
            return;
        }

        // CRITICAL: Update Saldo Pesantren (Advance Funding Logic)
        if ($santri->pesantren) {
             // Only if package is advance/enterprise? Or update all anyway?
             // Better update all, logic withdrawal restricts based on package.
             $santri->pesantren->increment('saldo_pg', $amount);
             Log::info("Saldo PG Updated for Pesantren {$santri->pesantren_id}: +{$amount}");
        }

        // Common Data
        $adminGroupId = env('FONNTE_ADMIN_GROUP_ID');

        // SMART PAYMENT LOGIC: Find oldest unpaid month
        $unpaidMonth = Syahriah::where('santri_id', $santri->id)
            ->where('is_lunas', false)
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->first();

        if ($unpaidMonth) {
            // Mark as Paid
            $unpaidMonth->update([
                'is_lunas' => true,
                'tanggal_bayar' => now(),
                'keterangan' => 'Lunas via Midtrans (Auto)',
                'nominal' => $amount
            ]);

            $monthName = \Carbon\Carbon::create()->month($unpaidMonth->bulan)->translatedFormat('F');
            $year = $unpaidMonth->tahun;

            // Calculate Remaining Arrears for Notification
            $remainingUnpaidBills = Syahriah::where('santri_id', $santri->id)
                ->where('is_lunas', false)
                ->get();
            
            $remainingArrearsCount = $remainingUnpaidBills->count();
            $totalRemainingArrears = $remainingUnpaidBills->sum('nominal'); // FIXED: Use actual nominal from DB
            
            $arrearsInfo = "";
            if ($remainingArrearsCount > 0) {
                $formattedArrears = number_format($totalRemainingArrears, 0, ',', '.');
                $arrearsInfo = "⚠️ Masih ada tunggakan $remainingArrearsCount bulan lagi (Rp $formattedArrears).";
            } else {
                $arrearsInfo = "✅ Alhamdulillah lunas, tidak ada tunggakan.";
            }

            // 1. Dispatch Event for Notifications (Async/Decoupled)
            \App\Events\PaymentReceived::dispatch($santri, $amount, $monthName, $year, $arrearsInfo, 'regular');
            
            Log::info("Payment Processed for Santri $nis - Month $monthName $year (Event Dispatched)");

        } else {
            // ADVANCE PAYMENT
            $lastBill = Syahriah::where('santri_id', $santri->id)
                ->orderBy('tahun', 'desc')
                ->orderBy('bulan', 'desc')
                ->first();

            $nextMonth = 1;
            $nextYear = date('Y');

            if ($lastBill) {
                $nextMonth = $lastBill->bulan + 1;
                $nextYear = $lastBill->tahun;
                if ($nextMonth > 12) {
                    $nextMonth = 1;
                    $nextYear++;
                }
            } else {
                $nextMonth = date('n');
            }

            // Create Advance Record
            Syahriah::create([
                'santri_id' => $santri->id,
                'bulan' => $nextMonth,
                'tahun' => $nextYear,
                'nominal' => $amount,
                'is_lunas' => true,
                'tanggal_bayar' => now(),
                'keterangan' => 'Lunas via Midtrans (Advance)',
            ]);

            $monthName = \Carbon\Carbon::create()->month($nextMonth)->translatedFormat('F');

            // 1. Dispatch Event for Advance Payment
            \App\Events\PaymentReceived::dispatch($santri, $amount, $monthName, $nextYear, "🌟 Dialokasikan untuk bulan depan (Advance).", 'advance');

            Log::info("Advance Payment for Santri $nis - $monthName $nextYear (Event Dispatched)");
        }
    }
}
