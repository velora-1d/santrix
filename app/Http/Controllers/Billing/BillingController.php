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
use Illuminate\Support\Facades\Log;

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

        // SECURITY PATCH (VULN-007): Do NOT trust 'transaction_status' param from URL.
        // It can be spoofed. We should just redirect to index.
        // The actual status update happens via Webhook (MidtransController)
        // OR we can optionally ping Midtrans API here for "Instant Update" UX but verifying server-to-server.
        
        // For now, safe default: Redirect with "Processing" message.
        return redirect()->route('admin.billing.index')->with('info', 'Pembayaran sedang divalidasi oleh sistem. Mohon tunggu beberapa saat.');
    }
    
    // ... subscribe remains same ...

    /**
     * Create Invoice for a selected plan.
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'package' => 'required|string|max:100',
        ]);

        $pesantren = Pesantren::find(Auth::user()->pesantren_id);
        
        // Find the selected package from database by slug
        $package = \App\Models\Package::where('slug', $request->package)->first();
        
        if (!$package) {
            return back()->with('error', 'Paket tidak ditemukan.');
        }
        
        $amount = $package->discount_price ?? $package->price;
        $durationMonths = $package->duration_months;
        $packageName = $package->name;
        
        // Calculate trial period or extension dates
        $sub = Subscription::where('pesantren_id', $pesantren->id)
            ->latest('expired_at')
            ->first();
            
        $startDate = ($sub && !$sub->expired_at->isPast()) ? $sub->expired_at : now();
        $endDate = $startDate->copy()->addMonths($durationMonths);

        $invoice = $this->invoiceService->createInvoice($pesantren, $packageName, $amount, $startDate, $endDate);

        return redirect()->route('admin.billing.show', $invoice->id);
    }
}
