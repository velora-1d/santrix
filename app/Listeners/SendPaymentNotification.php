<?php

namespace App\Listeners;

use App\Events\PaymentReceived;
use App\Services\FonnteService;
use App\Services\TelegramService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendPaymentNotification implements ShouldQueue
{
    use InteractsWithQueue; // Allows access to job attempts/fail logic

    protected $fonnteService;
    protected $telegramService;

    /**
     * Create the event listener.
     */
    public function __construct(FonnteService $fonnte, TelegramService $telegram)
    {
        $this->fonnteService = $fonnte;
        $this->telegramService = $telegram;
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentReceived $event): void
    {
        try {
            $santri = $event->santri;
            $amount = $event->amount;
            $monthName = $event->monthName;
            $year = $event->year;
            $arrearsInfo = $event->arrearsInfo;
            $adminGroupId = env('FONNTE_ADMIN_GROUP_ID');

            // 1. TELEGRAM NOTIFICATION
            try {
                if ($event->type === 'regular') {
                    $telegramMsg = "✅ **PEMBAYARAN DITERIMA**\n\n";
                    $telegramMsg .= "Santri: **{$santri->nama_santri}**\n";
                    $telegramMsg .= "Bulan: **{$monthName} {$year}**\n";
                    $telegramMsg .= "Nominal: Rp " . number_format($amount, 0, ',', '.') . "\n";
                } else {
                    $telegramMsg = "🌟 **PEMBAYARAN DEPOSIT (ADVANCE)**\n\n";
                    $telegramMsg .= "Santri: **{$santri->nama_santri}**\n";
                    $telegramMsg .= "Alokasi: **{$monthName} {$year}**\n";
                    $telegramMsg .= "Nominal: Rp " . number_format($amount, 0, ',', '.') . "\n";
                }
                
                $this->telegramService->sendMessage($telegramMsg);

            } catch (\Exception $e) {
                Log::error("Telegram Notification Failed: " . $e->getMessage());
                // Don't rethrow, strictly non-blocking for other channels if one fails
            }

            // 2. WHATSAPP (Parent - Japri)
            try {
                if ($santri->no_hp_ortu_wali) {
                    $this->fonnteService->notifyPaymentSuccess(
                        $santri->no_hp_ortu_wali, 
                        $santri->nama_santri, 
                        $amount, 
                        $monthName, 
                        $year,
                        $arrearsInfo
                    );
                }
            } catch (\Exception $e) {
                Log::error("WhatsApp Parent Notification Failed: " . $e->getMessage());
            }

            // 3. WHATSAPP (Admin Group - Report)
            try {
                if ($adminGroupId) {
                    $statusLabel = ($event->type === 'regular') ? 'LUNAS' : 'ADVANCE / DEPOSIT';
                    $this->fonnteService->notifyAdminReport(
                        $adminGroupId,
                        $santri->nama_santri,
                        $amount,
                        $monthName,
                        $year,
                        $statusLabel
                    );
                }
            } catch (\Exception $e) {
                Log::error("WhatsApp Admin Notification Failed: " . $e->getMessage());
            }

            Log::info("Notifications sent for Payment Event: {$santri->nis} - {$monthName} {$year}");

        } catch (\Exception $e) {
            // Catch-all to ensure the Job doesn't crash the worker thread if something wildly unexpected happens
            Log::error("SendPaymentNotification Job Fatal Error: " . $e->getMessage());
        }
    }
}
