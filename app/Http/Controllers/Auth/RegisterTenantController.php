<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pesantren;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Services\Billing\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RegisterTenantController extends Controller
{
    protected $invoiceService;
    protected $telegramService;

    public function __construct(InvoiceService $invoiceService, \App\Services\TelegramService $telegramService)
    {
        $this->invoiceService = $invoiceService;
        $this->telegramService = $telegramService;
    }

    public function showRegistrationForm(Request $request)
    {
        // Validate package parameter
        $packageSlug = $request->query('package');
        
        if (!$packageSlug) {
             // Fallback: Default to the first available package (usually Basic/Starter)
             // or redirect to pricing section
             $defaultPlan = \App\Models\Package::orderBy('price', 'asc')->first();
             if ($defaultPlan) {
                 return redirect()->route('register.tenant', ['package' => $defaultPlan->slug]);
             }
             return redirect('/#pricing');
        }

        $selectedPlan = \App\Models\Package::where('slug', $packageSlug)->first();

        if (!$selectedPlan) {
            // If slug invalid, redirect to pricing
            return redirect('/#pricing')->with('error', 'Silakan pilih paket yang valid.');
        }

        $package = $packageSlug; // Keep variable name consistent for view

        return view('auth.register-tenant', compact('package', 'selectedPlan'));
    }

    public function register(Request $request)
    {
        // Validate package
        $packageSlug = $request->input('package');
        $packageConfig = \App\Models\Package::where('slug', $packageSlug)->first();
        
        if (!$packageConfig) {
            return back()->with('error', 'Paket tidak valid.')->withInput();
        }

        $package = $packageSlug; // Define $package for downstream logic

        $validationRules = [
            'package' => 'required|exists:packages,slug',
            'nama_pesantren' => 'required|string|max:255',
            'subdomain' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-z0-9-]+$/',
                'unique:pesantrens,subdomain',
                'not_in:www,admin,panel,dashboard,api,owner'
            ],
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/', // SECURITY: Strong password
            ],
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|in:trial,transfer', // SECURITY FIX (VULN-002)
        ];

        // Add bank account validation for advance packages
        if (str_starts_with($package, 'advance')) {
            $validationRules['bank_name'] = 'required|string|max:100';
            $validationRules['bank_account_number'] = 'required|string|max:50';
            $validationRules['bank_account_name'] = 'required|string|max:255';
        }

        $request->validate($validationRules, [
            'subdomain.regex' => 'Subdomain hanya boleh berisi huruf kecil, angka, dan tanda hubung (-).',
            'subdomain.unique' => 'Subdomain ini sudah digunakan, silakan pilih yang lain.',
            'subdomain.not_in' => 'Subdomain ini tidak diizinkan.',
            'password.regex' => 'Password harus mengandung minimal 1 huruf besar, 1 huruf kecil, 1 angka, dan 1 simbol (@$!%*?&).',
        ]);

        try {
            DB::beginTransaction();

            // 1. Determine Status based on Payment Method
            $paymentMethod = $request->input('payment_method', 'trial');
            $isDirectPayment = ($paymentMethod === 'transfer');

            // Trial Logic
            $durationMonths = $packageConfig->duration_months;
            $trialDays = ($durationMonths == 3) ? 2 : 4; 
            
            if ($isDirectPayment) {
                // If direct payment, no trial period (active immediately after payment)
                // But we set expired_at to NOW so they are forced to pay to access? 
                // Or we give them minimal grace period (e.g. 1 day) to pay?
                // Let's set trial_ends_at to NOW.
                $trialEndsAt = now(); 
                $status = 'pending_payment';
            } else {
                // Normal Trial
                $trialEndsAt = now()->addDays($trialDays);
                $status = 'trial';
            }
            
            $graceDays = 3;
            $deleteAt = $trialEndsAt->copy()->addDays($graceDays);

            $pesantrenData = [
                'nama' => $request->nama_pesantren,
                'subdomain' => $request->subdomain,
                'domain' => $request->subdomain . '.' . config('app.url_base', 'santrix.my.id'),
                'status' => $status,
                'package' => $package,
                'expired_at' => $trialEndsAt,
                'trial_ends_at' => $trialEndsAt,
                'delete_at' => $deleteAt,
                'telepon' => $request->phone,
            ];

            // ... (Bank details logic) ...
            if (str_starts_with($package, 'advance')) {
                $pesantrenData['bank_name'] = $request->bank_name;
                $pesantrenData['bank_account_number'] = $request->bank_account_number;
                $pesantrenData['bank_account_name'] = $request->bank_account_name;
            }

            $pesantren = Pesantren::create($pesantrenData);

            // 2. Create User
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = 'admin'; 
            $user->pesantren_id = $pesantren->id;
            $user->save();

            // 3. Create Subscription
            $packageName = $packageConfig->name . ' ' . $packageConfig->duration_months . ' Bulan';
            $subscription = Subscription::create([
                'pesantren_id' => $pesantren->id,
                'package_name' => $packageName . ($isDirectPayment ? '' : ' (Trial)'),
                'price' => $isDirectPayment ? $packageConfig->price : 0, // 0 for trial record? Or just log the potential price?
                // Actually Subscription table usually tracks *active* subscription. 
                // For direct payment, we create a "pending" subscription logic in Invoice loop.
                // Let's keep it consistent: always create an initial record.
                'started_at' => now(),
                'expired_at' => $trialEndsAt,
                'status' => $status,
            ]);

            // 4. Create Invoice
            $amount = (float) $packageConfig->price;
            $invoice = $this->invoiceService->createInvoice(
                $pesantren,
                $package,
                $amount,
                $trialEndsAt, // Due date
                $trialEndsAt->copy()->addMonths($durationMonths) // Period end
            );

            DB::commit();

            // Notify
            try {
                $this->telegramService->notifyNewTenantRegistration($pesantren, $user);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Telegram notification failed: ' . $e->getMessage());
            }

            // 5. Redirect based on choice
            if ($isDirectPayment) {
                 // If direct payment, maybe redirect to Invoice Payment Page directly?
                 // Or customized success page.
                 return view('auth.register-success-payment', [
                    'subdomain' => $pesantren->subdomain,
                    'invoiceUrl' => route('invoice.show', ['uuid' => $invoice->uuid]), // Assuming public invoice view exists or tenant needs login
                    'amount' => number_format($amount, 0, ',', '.'),
                    'bank' => 'BCA 1234567890 a.n PT Santrix', // Hardcoded or config
                ]);
            }

            return view('auth.register-success', [
                'subdomain' => $pesantren->subdomain,
                'email' => $user->email,
                'trialDays' => $trialDays,
                'trialEndsAt' => $trialEndsAt->format('d M Y'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat pendaftaran: ' . $e->getMessage())->withInput();
        }
    }
}
