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
            ? 'https://sandbox.duitku.com/webapi' // Sandbox URL use webapi base
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
        // Duitku typically requires Integer for Amount (no decimals)
        $intAmount = (int) $nominal;
        
        // Prepare Signature
        // MD5(merchantCode + orderId + amount + apiKey)
        $signature = md5($this->merchantCode . $orderId . $intAmount . $this->apiKey);

        $params = [
            'merchantCode' => $this->merchantCode,
            'paymentAmount' => $intAmount, // Use INT
            'merchantOrderId' => $orderId,
            'productDetails' => 'Pembayaran SPP Santri ' . $santri->nama_santri,
            'additionalParam' => '',
            'merchantUserInfo' => $santri->email ?? 'dummy@santrix.my.id',
            'customerVaName' => $santri->nama_santri,
            'email' => $santri->email ?? 'dummy@santrix.my.id',
            'phoneNumber' => $santri->no_hp_ortu_wali ?? '081234567890',
            // 'itemDetails' => [ ... ], // REMOVED for stability
            // 'customerDetail' => [ ... ], // REMOVED for stability
            'callbackUrl' => 'https://santrix.my.id/callback', 
            'returnUrl' => route('duitku.return'), 
            'signature' => $signature,
            'expiryPeriod' => 60 
        ];

        if (!empty($paymentMethod)) {
            $params['paymentMethod'] = $paymentMethod;
        }

        try {
            // Check Duitku Docs for exact endpoint. Usually "Get Payment Interface"
            
            // NOTE: Duitku has different endpoints for "Pop Up" vs "Direct".
            // Direct API URL:
            // $url = 'https://passport.duitku.com/webapi/api/merchant/v2/inquiry'; // Example
            
            $endpoint = $this->isSandbox ? '/api/merchant/inquiry' : '/api/merchant/v2/inquiry';
            $apiUrl = $this->baseUrl . $endpoint;

            Log::info('Duitku Request:', [
                'url' => $apiUrl,
                'params' => $params,
                'signature_source' => $this->merchantCode . $orderId . $intAmount . $this->apiKey
            ]);
            
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Content-Length' => strlen(json_encode($params))
            ])->post($apiUrl, $params); 
            
            Log::info('Duitku Response:', ['status' => $response->status(), 'body' => $response->json()]);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Duitku Error: ' . $response->body());
                // Throw exception with detailed body for debugging
                throw new \Exception('Failed to connect to Duitku: ' . $response->status() . ' - ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Duitku Exception: ' . $e->getMessage());
            throw $e;
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
