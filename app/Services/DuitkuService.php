<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DuitkuService
{
    protected $merchantCode;
    protected $apiKey;
    protected $isSandbox;
    protected $baseUrl;

    public function __construct()
    {
        $this->merchantCode = config('services.duitku.merchant_code');
        $this->apiKey = config('services.duitku.api_key');
        $this->isSandbox = config('services.duitku.sandbox', true);

        $this->baseUrl = $this->isSandbox
            ? 'https://sandbox.duitku.com/webapi' // Sandbox URL V2 (Often without webapi, but let's try keep it if docs say so. actually 404 suggests otherwise)
            // Let's try https://sandbox.duitku.com/api/merchant/v2/inquiry
            // BaseUrl = https://sandbox.duitku.com
            // Concatenation = /api/merchant/v2/inquiry
            // So BaseUrl should be just 'https://sandbox.duitku.com'???
            // Production is 'https://passport.duitku.com/webapi'
            
            // Revert: The previous change made it 'https://sandbox.duitku.com/webapi'.
            // I will change it to 'https://sandbox.duitku.com' AND adjust the concatenation if needed.
            // But concatenation adds '/api/merchant...'. 
            // If I change baseUrl to 'https://sandbox.duitku.com', result: 'https://sandbox.duitku.com/api/merchant...'
            // If I keep '/webapi', result: 'https://sandbox.duitku.com/webapi/api/merchant...'
            
            ? 'https://sandbox.duitku.com' // Sandbox URL (Retry without /webapi)
            : 'https://passport.duitku.com/webapi'; // Production URL
    }

    /**
     * Create a Payment Request (Get Payment URL or VA)
     */
    public function createPayment($santri, $nominal = 50000, $paymentMethod = 'VC')
    {
        // Unique Order ID
        $orderId = 'SPP-' . $santri->pesantren_id . '-' . $santri->nis . '-' . time();
        
        // Prepare Signature
        // MD5(merchantCode + orderId + amount + apiKey)
        $signature = md5($this->merchantCode . $orderId . $nominal . $this->apiKey);

        $params = [
            'merchantCode' => $this->merchantCode,
            'paymentAmount' => $nominal,
            'paymentMethod' => $paymentMethod, // VC = Credit Card, VA = Virtual Account (Specific codes needed)
            'merchantOrderId' => $orderId,
            'productDetails' => 'Pembayaran SPP Santri ' . $santri->nama_santri,
            'additionalParam' => '',
            'merchantUserInfo' => $santri->email ?? 'dummy@santrix.my.id',
            'customerVaName' => $santri->nama_santri,
            'email' => $santri->email ?? 'dummy@santrix.my.id',
            'phoneNumber' => $santri->no_hp_ortu_wali ?? '081234567890',
            'itemDetails' => [
                [
                    'name' => 'SPP Bulanan',
                    'price' => $nominal,
                    'quantity' => 1
                ]
            ],
            'customerDetail' => [
                'firstName' => $santri->nama_santri,
                'lastName' => '',
                'email' => $santri->email ?? 'dummy@santrix.my.id',
                'phoneNumber' => $santri->no_hp_ortu_wali ?? '',
            ],
            'callbackUrl' => 'https://santrix.my.id/callback', // Fixed callback URL
            'returnUrl' => route('duitku.return'), // Where to redirect
            'signature' => $signature,
            'expiryPeriod' => 60 // 60 minutes
        ];

        try {
            // Check Duitku Docs for exact endpoint. Usually "Get Payment Interface"
            
            // NOTE: Duitku has different endpoints for "Pop Up" vs "Direct".
            // Direct API URL:
            $url = 'https://passport.duitku.com/webapi/api/merchant/v2/inquiry'; // Example
            
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                // 'Content-Length' => strlen(json_encode($params))
            ])->post($this->baseUrl . '/api/merchant/v2/inquiry', $params); // Verify exact endpoint
            
            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Duitku Error: ' . $response->body());
            return ['statusMessage' => 'Failed to connect to Duitku', 'statusCode' => 500];

        } catch (\Exception $e) {
            Log::error('Duitku Exception: ' . $e->getMessage());
            return ['statusMessage' => $e->getMessage(), 'statusCode' => 500];
        }
    }

    /**
     * Generate Signature for Verification
     */
    public function verifySignature($merchantCode, $amount, $orderId, $signature) 
    {
         $calcSignature = md5($merchantCode . $orderId . $amount . $this->apiKey);
         return $calcSignature === $signature;
    }
}
