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
        $amount = $request->amount; // Duitku sends amount in callback
        $signature = $request->signature;
        $resultCode = $request->resultCode; // '00' is success
        $reference = $request->reference;

        if (empty($merchantOrderId) || empty($signature)) {
             return response()->json(['message' => 'Invalid Parameter'], 400);
        }

        // Verify Signature
        // MD5(merchantCode + amount + merchantOrderId + apiKey)
        // NOTE: Check valid signature format from Duitku docs. Usually it's this order.
        $calcSignature = md5($merchantCode . $amount . $merchantOrderId . $apiKey);

        if ($signature !== $calcSignature) {
            Log::warning("Duitku Invalid Signature: Expected $calcSignature, Got $signature");
            return response()->json(['message' => 'Invalid Signature'], 400);
        }

        // Process Transaction
        if ($resultCode == '00') {
            Log::info("Duitku Payment Success for Order: $merchantOrderId");
            
            // logic update database
            // e.g. Payment::where('order_id', $merchantOrderId)->update(['status' => 'paid']);
            // Need to find which model stores the transaction.
            
        } else {
            Log::info("Duitku Payment Failed/Pending for Order: $merchantOrderId with Code: $resultCode");
            // update status failed
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
