<?php

namespace App\Services\Billing;

use App\Models\Invoice;
use App\Models\Pesantren;
use App\Models\Subscription;
use Carbon\Carbon;

class SubscriptionService
{
    /**
     * Handle the activation or extension of a subscription after an invoice is paid.
     */
    public function handlePaidInvoice(Invoice $invoice): Subscription
    {
        $pesantren = $invoice->pesantren;
        
        // Find current or latest subscription
        $subscription = Subscription::where('pesantren_id', $pesantren->id)
            ->latest('expired_at')
            ->first();

        $now = now();
        $isUpgrade = false;
        
        // 1. DYNAMIC PACKAGE LOOKUP (P1 Fix)
        // Try to find matching package by price
        $matchedPackage = \App\Models\Package::where('price', $invoice->amount)
            ->orWhere('discount_price', $invoice->amount)
            ->first();

        if ($matchedPackage) {
            $packageSlug = $matchedPackage->slug; // e.g., 'basic-6-bulan' or just 'basic'
            // We might need to normalize slug if system expects just 'basic' or 'advance'. 
            // RegisterTenantController uses full slug. 
            // For now, let's assume slug contains the base type or we map it.
            // Actually, existing logic used 'advance' : 'basic'. 
            // Let's assume the slug IS 'advance' or 'basic' or starts with it.
            
            // Simplification for backward compatibility if slugs are complex:
            $packageName = str_contains($packageSlug, 'advance') ? 'advance' : 'basic';
            $durationMonths = $matchedPackage->duration_months;
        } else {
             // Fallback Logic (Legacy/Manual Amounts)
             $packageName = ($invoice->amount >= 3000000) ? 'advance' : 'basic';
             $durationMonths = 6; // Default fallback
             \Illuminate\Support\Facades\Log::warning("Invoice {$invoice->id} paid with amount {$invoice->amount} but no matching Package found. Using fallback logic.");
        }

        if (!$subscription) {
            // Create new subscription if none exists
            $subscription = Subscription::create([
                'pesantren_id' => $pesantren->id,
                'package_name' => $packageName,
                'price' => $invoice->amount,
                'started_at' => $now,
                'expired_at' => $now->copy()->addMonths($durationMonths),
                'status' => 'active',
            ]);
        } else {
            // Logic for existing subscription
            $isUpgrade = ($packageName === 'advance' && $subscription->package_name === 'basic');
            
            if ($subscription->expired_at->isPast()) {
                // If already expired, start fresh from today
                $subscription->update([
                    'package_name' => $packageName,
                    'price' => $invoice->amount,
                    'started_at' => $now,
                    'expired_at' => $now->copy()->addMonths($durationMonths),
                    'status' => 'active',
                ]);
            } else {
                // If still active
                if ($isUpgrade) {
                    // Upgrade: Update package and extend
                    // Logic: Reset expiry to Now + Duration? Or Add? 
                    // Usually upgrade restarts the cycle.
                    $newExpiry = $now->copy()->addMonths($durationMonths);
                    
                    $subscription->update([
                        'package_name' => $packageName,
                        'expired_at' => $newExpiry,
                        'status' => 'active',
                    ]);
                } else {
                    // Extend: Add duration to current expiry
                    $subscription->update([
                        'expired_at' => $subscription->expired_at->addMonths($durationMonths),
                        'status' => 'active',
                    ]);
                }
            }
        }

        // Link invoice to subscription
        $invoice->update(['subscription_id' => $subscription->id]);

        // Sync to Pesantren table (Denormalized Cache)
        $this->syncToPesantren($pesantren, $subscription);

        return $subscription;
    }

    /**
     * Sync subscription status to the Pesantren model for fast access.
     */
    public function syncToPesantren(Pesantren $pesantren, Subscription $subscription): void
    {
        $pesantren->update([
            'package' => $subscription->package_name,
            'status' => $subscription->status,
            'expired_at' => $subscription->expired_at,
        ]);
    }

    /**
     * Real-time check if subscription is expired for middleware gating.
     */
    public function isExpired(Pesantren $pesantren): bool
    {
        return empty($pesantren->expired_at) || Carbon::parse($pesantren->expired_at)->isPast();
    }
}
