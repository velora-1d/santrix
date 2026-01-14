<?php

namespace App\Services\Billing;

use App\Models\Invoice;
use App\Models\Pesantren;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class InvoiceService
{
    /**
     * Create a new pending invoice for a pesantren.
     */
    public function createInvoice(Pesantren $pesantren, string $package, float $amount, Carbon $periodStart, Carbon $periodEnd): Invoice
    {
        // Generate idempotency key to prevent duplicate invoices
        $idempotencyKey = hash('sha256', implode('|', [
            $pesantren->id,
            $package,
            $amount,
            $periodStart->timestamp,
            $periodEnd->timestamp
        ]));

        // Check if invoice with same idempotency key already exists
        $existingInvoice = Invoice::where('idempotency_key', $idempotencyKey)->first();
        if ($existingInvoice) {
            return $existingInvoice; // Return existing invoice (idempotency)
        }

        // Generate Unique Invoice Number: INV-{YYYYMM}-{PESANTREN_ID}-{SEQ}
        $prefix = 'INV-' . now()->format('Ym') . '-' . $pesantren->id . '-';
        $latestInvoice = Invoice::where('invoice_number', 'like', $prefix . '%')
            ->orderByRaw('LENGTH(invoice_number) DESC') // Handle sequence length differences
            ->orderBy('invoice_number', 'desc')
            ->first();

        $sequence = 1;
        if ($latestInvoice) {
            $parts = explode('-', $latestInvoice->invoice_number);
            $sequence = (int) end($parts) + 1;
        }
        
        $invoiceNumber = $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);

        return Invoice::create([
            'pesantren_id' => $pesantren->id,
            'invoice_number' => $invoiceNumber,
            'amount' => $amount,
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'status' => 'pending',
            'idempotency_key' => $idempotencyKey, // Add idempotency key
            // subscription_id set later after payment/activation
        ]);
    }

    /**
     * Mark invoice as paid and trigger details update.
     */
    public function markAsPaid(Invoice $invoice, ?User $payer = null, string $method = 'manual'): Invoice
    {
        if ($invoice->status === 'paid') {
            return $invoice; // Idempotency check
        }

        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
            'paid_by_user_id' => $payer?->id,
            'payment_method' => $method,
        ]);

        // Integrate with SubscriptionService to activate/extend
        app(SubscriptionService::class)->handlePaidInvoice($invoice);

        return $invoice;
    }
}
