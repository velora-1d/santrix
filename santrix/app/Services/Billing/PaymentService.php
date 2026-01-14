<?php

namespace App\Services\Billing;

use App\Models\Invoice;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentService
{
    public function __construct()
    {
        // Set your Merchant Server Key
        Config::$serverKey = config('services.midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = config('services.midtrans.is_production', false);
        // Set sanitization on (default)
        Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        Config::$is3ds = true;
    }

    public function getSnapToken(Invoice $invoice)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $invoice->invoice_number . '-' . time(), // Unique Order ID
                'gross_amount' => (int) $invoice->amount,
            ],
            'customer_details' => [
                'first_name' => $invoice->pesantren->name,
                'email' => $invoice->pesantren->user->email ?? 'admin@santrix.id',
                'phone' => $invoice->pesantren->phone ?? '08123456789',
            ],
            'item_details' => [
                [
                    'id' => $invoice->package_name,
                    'price' => (int) $invoice->amount,
                    'quantity' => 1,
                    'name' => 'Paket Langganan ' . ucfirst($invoice->package_name),
                ]
            ]
        ];

        return Snap::getSnapToken($params);
    }
}
