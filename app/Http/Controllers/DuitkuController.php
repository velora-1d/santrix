<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DuitkuService;
use App\Models\Pemasukan; // Assuming transactions are stored here or similar
use Illuminate\Support\Facades\Log;

class DuitkuController extends Controller
{
    protected $duitkuService;

    public function __construct(DuitkuService $duitkuService)
    {
        $this->duitkuService = $duitkuService;
    }

    /**
     * Handle Duitku Callback
     * Payload typically contains: merchantCode, amount, merchantOrderId, signature, resultCode, reference etc.
     */
    public function callback(Request $request)
    {
        Log::info('Duitku Callback hit:', $request->all());

        $merchantCode = config('services.duitku.merchant_code'); 
        $apiKey = config('services.duitku.api_key');

        $merchantOrderId = $request->merchantOrderId;
        $amount = $request->amount;
        $signature = $request->signature;
        $resultCode = $request->resultCode;
        $reference = $request->reference;

        if (empty($merchantOrderId) || empty($signature)) {
             return response()->json(['message' => 'Invalid Parameter'], 400);
        }

        $calcSignature = md5($merchantCode . $amount . $merchantOrderId . $apiKey);

        if ($signature !== $calcSignature) {
            Log::warning("Duitku Invalid Signature: Expected $calcSignature, Got $signature");
            return response()->json(['message' => 'Invalid Signature'], 400);
        }

        if ($resultCode == '00') {
            Log::info("Duitku Payment Success for Order: $merchantOrderId");
            
            // Extract Invoice ID from merchantOrderId (Format: SPP-{PESANTREN_ID}-{NIS/INV_ID}-{TIMESTAMP})
            // Since we used 'INV-' . $invoice->id in BillingController, we need to handle that format.
            // DuitkuService creates orderId as: 'SPP-' . $santri->pesantren_id . '-' . $santri->nis . '-' . time();
            // In BillingController: 'nis' => 'INV-' . $invoice->id
            // So orderId is: SPP-{PID}-INV-{IID}-{TIME}
            
            $parts = explode('-', $merchantOrderId);
            // parts[0] = SPP
            // parts[1] = PESANTREN_ID
            // parts[2] = INV
            // parts[3] = INVOICE_ID
            
            if (isset($parts[3])) {
                $invoiceId = $parts[3];
                $invoice = \App\Models\Invoice::find($invoiceId);
                
                if ($invoice && $invoice->status !== 'paid') {
                     // Mark as Paid
                     app(\App\Services\Billing\InvoiceService::class)->markAsPaid(
                         $invoice, 
                         null, 
                         'duitku_vc' // Or map from $request->paymentMethod
                     );
                     Log::info("Invoice #{$invoice->id} marked as paid.");
                }
            }
            
        } else {
            Log::info("Duitku Payment Failed/Pending for Order: $merchantOrderId with Code: $resultCode");
        }

        return response()->json(['message' => 'OK'], 200);
    }
    
    /**
     * Return Page (Success Page)
     */
    public function returnPage(Request $request)
    {
        return view('payment.finish'); // We need to create this view or reuse one
    }
}
