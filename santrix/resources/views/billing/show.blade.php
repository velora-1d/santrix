@extends('layouts.app')

@section('title', 'Detail Tagihan - ' . $invoice->invoice_number)
@section('page-title', 'Detail Tagihan')

@section('sidebar-menu')
    @include('admin.partials.sidebar-menu')
@endsection

@section('content')
<div style="padding: 24px; max-w-900px; margin: 0 auto;">
    
    <!-- Back Button -->
    <a href="{{ route('admin.billing.index') }}" style="display: inline-flex; align-items: center; gap: 8px; color: #64748b; text-decoration: none; font-weight: 600; margin-bottom: 20px; transition: color 0.2s;" onmouseover="this.style.color='#1e293b'" onmouseout="this.style.color='#64748b'">
        <i data-feather="arrow-left" style="width: 18px; height: 18px;"></i>
        Kembali ke Billing
    </a>

    <!-- Invoice Card -->
    <div style="background: white; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); overflow: hidden;">
        
        @php
            $statusConfig = match($invoice->status) {
                'paid' => [
                    'gradient' => 'linear-gradient(120deg, #10b981 0%, #059669 100%)',
                    'label' => 'PEMBAYARAN BERHASIL',
                    'icon' => 'check-circle'
                ],
                'pending' => [
                    'gradient' => 'linear-gradient(120deg, #f59e0b 0%, #d97706 100%)',
                    'label' => 'MENUNGGU PEMBAYARAN',
                    'icon' => 'clock'
                ],
                'failed' => [
                    'gradient' => 'linear-gradient(120deg, #ef4444 0%, #dc2626 100%)',
                    'label' => 'PEMBAYARAN GAGAL',
                    'icon' => 'x-circle'
                ],
                default => [
                    'gradient' => 'linear-gradient(120deg, #64748b 0%, #475569 100%)',
                    'label' => 'STATUS TIDAK DIKETAHUI',
                    'icon' => 'help-circle'
                ],
            };
        @endphp

        <!-- Header -->
        <div style="background: {{ $statusConfig['gradient'] }}; padding: 32px; color: white; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="position: absolute; bottom: -30px; left: -30px; width: 140px; height: 140px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            
            <div style="position: relative; z-index: 1;">
                <!-- Status Badge -->
                <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.2); padding: 8px 16px; border-radius: 999px; margin-bottom: 16px; backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.3);">
                    <i data-feather="{{ $statusConfig['icon'] }}" style="width: 16px; height: 16px;"></i>
                    <span style="font-size: 0.875rem; font-weight: 700;">{{ $statusConfig['label'] }}</span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: end; flex-wrap: wrap; gap: 20px;">
                    <div>
                        <h1 style="font-size: 2.5rem; font-weight: 800; margin: 0 0 8px 0; color: white !important;">{{ $invoice->invoice_number }}</h1>
                        <p style="color: rgba(255,255,255,0.9) !important; font-size: 0.875rem; margin: 0; font-weight: 500;">
                            Tanggal: {{ $invoice->created_at->format('d F Y, H:i') }} WIB
                        </p>
                    </div>
                    
                    <div style="text-align: right;">
                        <div style="font-size: 0.75rem; font-weight: 600; color: rgba(255,255,255,0.8); margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Total Tagihan</div>
                        <div style="font-size: 2.5rem; font-weight: 800; color: white !important;">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div style="padding: 32px;">
            
            <!-- Info Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-bottom: 32px;">
                <div>
                    <div style="font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Tagihan Untuk</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-bottom: 4px;">{{ $invoice->pesantren->nama }}</div>
                    <div style="color: #64748b; font-weight: 600;">{{ $invoice->pesantren->subdomain }}.santrix.my.id</div>
                </div>

                <div>
                    <div style="font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Periode Langganan</div>
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                        <i data-feather="calendar" style="width: 18px; height: 18px; color: #6366f1;"></i>
                        <span style="font-weight: 700; color: #1e293b;">{{ $invoice->period_start->format('d M Y') }} - {{ $invoice->period_end->format('d M Y') }}</span>
                    </div>
                    <div style="display: inline-flex; align-items: center; gap: 6px; background: #f5f3ff; color: #7c3aed; padding: 4px 12px; border-radius: 999px; font-size: 0.75rem; font-weight: 700;">
                        <i data-feather="clock" style="width: 12px; height: 12px;"></i>
                        6 Bulan (1 Semester)
                    </div>
                </div>
            </div>

            <!-- Invoice Items Table -->
            <div style="background: #f8fafc; border-radius: 12px; overflow: hidden; margin-bottom: 32px; border: 1px solid #e2e8f0;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f1f5f9;">
                            <th style="padding: 16px 20px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Deskripsi</th>
                            <th style="padding: 16px 20px; text-align: right; font-size: 0.75rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding: 24px 20px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="background: linear-gradient(120deg, #6366f1 0%, #8b5cf6 100%); width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                        <i data-feather="package" style="width: 24px; height: 24px; color: white;"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 1.125rem; font-weight: 700; color: #0f172a;">Paket {{ strtoupper($invoice->subscription->package_name ?? 'Unknown') }}</div>
                                        <div style="font-size: 0.875rem; color: #64748b;">Sistem Manajemen Pesantren SANTRIX</div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 24px 20px; text-align: right;">
                                <div style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</div>
                            </td>
                        </tr>
                        <tr style="background: #e2e8f0;">
                            <td style="padding: 16px 20px; text-align: right; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 0.875rem;">Total Pembayaran</td>
                            <td style="padding: 16px 20px; text-align: right;">
                                <div style="font-size: 2rem; font-weight: 800; color: #6366f1;">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Action Section -->
            @if($invoice->status === 'pending')
            <div style="background: #fef3c7; border: 2px solid #fde68a; border-radius: 12px; padding: 24px;">
                <div style="display: flex; align-items: start; gap: 16px; margin-bottom: 20px;">
                    <div style="background: #f59e0b; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i data-feather="info" style="width: 24px; height: 24px; color: white;"></i>
                    </div>
                    <div>
                        <div style="font-size: 1.125rem; font-weight: 700; color: #78350f; margin-bottom: 8px;">Selesaikan Pembayaran</div>
                        <p style="color: #92400e; margin: 0; line-height: 1.6;">Klik tombol di bawah untuk membayar melalui Virtual Account, GoPay, atau metode lainnya.</p>
                    </div>
                </div>

                @if(isset($paymentUrl))
                <a href="{{ $paymentUrl }}" style="text-decoration: none;">
                    <button id="pay-button" style="width: 100%; background: linear-gradient(120deg, #6366f1 0%, #8b5cf6 100%); color: white; padding: 16px 24px; border: none; border-radius: 12px; font-weight: 700; font-size: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3); transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(99, 102, 241, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(99, 102, 241, 0.3)'">
                        <i data-feather="credit-card" style="width: 20px; height: 20px;"></i>
                        BAYAR DENGAN DUITKU
                        <i data-feather="arrow-right" style="width: 20px; height: 20px;"></i>
                    </button>
                </a>
                <p style="text-align: center; margin-top: 10px; color: #64748b; font-size: 0.875rem;">Anda akan dialihkan ke halaman pembayaran Duitku.</p>
                @else
                <div style="background: #fee2e2; color: #b91c1c; padding: 16px; border-radius: 8px; text-align: center;">
                    Gagal memuat pembayaran. Silakan refresh halaman atau hubungi admin.
                </div>
                @endif
            </div>

            <!-- Duitku Logic is Redirect-based, no custom script needed here -->
            <script>
                // Optional: Auto-redirect if needed
            </script>
            @else
            <div style="background: #ecfdf5; border: 2px solid #a7f3d0; border-radius: 12px; padding: 24px;">
                <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                    <div style="background: #10b981; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i data-feather="check" style="width: 32px; height: 32px; color: white; stroke-width: 3;"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-size: 1.5rem; font-weight: 800; color: #065f46; margin-bottom: 4px;">Pembayaran Berhasil!</div>
                        <p style="color: #047857; font-weight: 600; margin: 0;">Invoice ini telah dibayar pada <strong>{{ $invoice->paid_at?->format('d M Y, H:i') ?? $invoice->updated_at->format('d M Y, H:i') }}</strong></p>
                    </div>
                    <button onclick="window.print()" style="background: white; border: 2px solid #10b981; color: #059669; padding: 10px 20px; border-radius: 8px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s;" onmouseover="this.style.background='#f0fdf4'" onmouseout="this.style.background='white'">
                        <i data-feather="printer" style="width: 18px; height: 18px;"></i>
                        CETAK
                    </button>
                </div>
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div style="background: #1e293b; padding: 20px 32px; text-align: center;">
            <p style="color: #cbd5e1; margin: 0; font-size: 0.875rem;">
                Pertanyaan tentang tagihan? <a href="#" style="color: white; font-weight: 700; text-decoration: underline;">Hubungi Support</a>
            </p>
        </div>
    </div>
</div>

<style>
    @media print {
        .sidebar, nav, button, a, form { display: none !important; }
    }
</style>
@endsection
