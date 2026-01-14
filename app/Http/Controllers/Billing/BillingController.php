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
    protected $duitkuService;

    public function __construct(
        InvoiceService $invoiceService, 
        SubscriptionService $subscriptionService,
        \App\Services\DuitkuService $duitkuService
    )
    {
        $this->invoiceService = $invoiceService;
        $this->subscriptionService = $subscriptionService;
        $this->duitkuService = $duitkuService;
    }

    // ... index & plans remain same ...

    /**
     * Show Invoice Detail / Checkout.
     */
    public function show($id)
    {
        $invoice = Invoice::where('pesantren_id', Auth::user()->pesantren_id)
            ->findOrFail($id);

        $paymentUrl = null;
        if ($invoice->status === 'pending') {
            try {
                // Santri object mocking for DuitkuService compatibility
                // Or refactor DuitkuService to accept generic data
                // For now, mapping Invoice/User to "Santri-like" object
                $user = Auth::user();
                $pesantren = Pesantren::find($user->pesantren_id);
                
                $payerData = (object) [
                    'pesantren_id' => $pesantren->id,
                    'nis' => 'INV-' . $invoice->id, // Use Invoice ID as NIS replacer
                    'nama_santri' => $pesantren->nama, // Use Pesantren Name
                    'email' => $user->email,
                    'no_hp_ortu_wali' => $pesantren->no_hp ?? '081234567890'
                ];

                $paymentResponse = $this->duitkuService->createPayment($payerData, $invoice->amount, 'VC'); // Default VC or let user choose in View
                
                if (isset($paymentResponse['paymentUrl'])) {
                    $paymentUrl = $paymentResponse['paymentUrl'];
                }

            } catch (\Exception $e) {
                Log::error('Duitku Error: ' . $e->getMessage());
            }
        }

        return view('billing.show', compact('invoice', 'paymentUrl'));
    }

    /**
     * Handle Payment Success (Redirect from Duitku).
     */
    public function pay(Request $request, $id) 
    {
        // This might not be needed if Duitku redirects directly to returnUrl defined in Service
        return redirect()->route('admin.billing.index')->with('info', 'Pembayaran sedang divalidasi. Silakan cek status secara berkala.');
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
    /**
     * Show Public Invoice (UUID based) for Initial Subscription
     */
    public function showPublic($uuid)
    {
        $invoice = Invoice::where('uuid', $uuid)->firstOrFail();
        
        // Ensure invoice is for a subscription (optional security check)
        
        $paymentUrl = null;
        if ($invoice->status === 'pending') {
            try {
                // Get User/Pesantren associated with invoice
                $pesantren = Pesantren::find($invoice->pesantren_id);
                
                if (!$pesantren) {
                    throw new \Exception("Data Pesantren tidak ditemukan untuk Invoice ini.");
                }

                $user = $pesantren->users()->where('role', 'owner')->first(); 

                $payerData = (object) [
                    'pesantren_id' => $pesantren->id,
                    'nis' => 'INV-' . $invoice->id, // Reference
                    'nama_santri' => $pesantren->nama,
                    'email' => $user->email ?? $pesantren->email, // Fallback
                    'no_hp_ortu_wali' => $pesantren->no_hp ?? '081234567890'
                ];

                $paymentResponse = $this->duitkuService->createPayment($payerData, $invoice->amount, 'VC'); 
                
                if (isset($paymentResponse['paymentUrl'])) {
                    $paymentUrl = $paymentResponse['paymentUrl'];
                } else {
                    $paymentError = $paymentResponse['statusMessage'] ?? 'Unknown Error';
                }

            } catch (\Exception $e) {
                Log::error('Duitku Error (Public): ' . $e->getMessage());
                $paymentError = $e->getMessage();
            }
        }

        // Return a view optimized for public invoice without admin layout
        return view('billing.public-show', compact('invoice', 'paymentUrl', 'paymentError'));
    }
}
