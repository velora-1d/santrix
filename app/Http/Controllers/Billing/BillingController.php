<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Pesantren;
use App\Models\Subscription;
use App\Services\Billing\InvoiceService;
use App\Services\Billing\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    protected $invoiceService;
    protected $subscriptionService;
    protected $paymentService;

    public function __construct(
        InvoiceService $invoiceService, 
        SubscriptionService $subscriptionService,
        \App\Services\Billing\PaymentService $paymentService
    )
    {
        $this->invoiceService = $invoiceService;
        $this->subscriptionService = $subscriptionService;
        $this->paymentService = $paymentService;
    }

    // ... index & plans remain same ...

    /**
     * Show Invoice Detail / Checkout.
     */
    public function show($id)
    {
        $invoice = Invoice::where('pesantren_id', Auth::user()->pesantren_id)
            ->findOrFail($id);

        $snapToken = null;
        if ($invoice->status === 'pending') {
            try {
                $snapToken = $this->paymentService->getSnapToken($invoice);
            } catch (\Exception $e) {
                Log::error('Midtrans Error: ' . $e->getMessage());
                // Fallback or show error in view if needed
            }
        }

        return view('billing.show', compact('invoice', 'snapToken'));
    }

    /**
     * Handle Payment Success (Redirect from Midtrans).
     */
    public function pay(Request $request, $id) // Re-purposed as "Finish" handler
    {
        $invoice = Invoice::where('pesantren_id', Auth::user()->pesantren_id)
            ->findOrFail($id);

        // In real world, we rely on Webhook (NotificationHandler).
        // But for redirect, we can check status via API or just trust if user lands here with 'transaction_status=settlement'
        
        $status = $request->get('transaction_status');
        
        if ($status == 'settlement' || $status == 'capture') {
             $this->invoiceService->markAsPaid($invoice, Auth::user());
             return redirect()->route('admin.billing.index')->with('success', 'Pembayaran berhasil! Paket Anda telah diperbarui.');
        }

        return redirect()->route('admin.billing.index')->with('info', 'Status pembayaran sedang diproses.');
    }
    
    // ... subscribe remains same ...

    /**
     * Create Invoice for a selected plan.
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'package' => 'required|in:basic,advance',
        ]);

        $pesantren = Pesantren::find(Auth::user()->pesantren_id);
        $package = $request->package;
        $amount = ($package === 'advance') ? 3000000 : 1500000;
        
        // Calculate trial period or extension dates
        $sub = Subscription::where('pesantren_id', $pesantren->id)
            ->latest('expired_at')
            ->first();
            
        $startDate = ($sub && !$sub->expired_at->isPast()) ? $sub->expired_at : now();
        $endDate = $startDate->copy()->addMonths(6);

        $invoice = $this->invoiceService->createInvoice($pesantren, $package, $amount, $startDate, $endDate);

        return redirect()->route('admin.billing.show', $invoice->id);
    }
}
