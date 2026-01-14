@extends('layouts.app')

@section('title', 'Billing & Subscription')
@section('page-title', 'Billing & Langganan')

@section('sidebar-menu')
    @include('admin.partials.sidebar-menu')
@endsection

@section('content')
<div style="padding: 24px; max-width: 1400px; margin: 0 auto;">
    
    <!-- Success/Warning Alerts -->
    @if(session('success'))
        <div style="background: #ecfdf5; color: #047857; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; border: 1px solid #a7f3d0;">
            <i data-feather="check-circle" style="width: 24px; height: 24px;"></i>
            <span style="font-weight: 600;">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('warning'))
        <div style="background: #fef3c7; color: #92400e; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; border: 1px solid #fde68a;">
            <i data-feather="alert-triangle" style="width: 24px; height: 24px;"></i>
            <span style="font-weight: 600;">{{ session('warning') }}</span>
        </div>
    @endif

    <!-- Header Subscription Status -->
    <div style="background: linear-gradient(120deg, #6366f1 0%, #8b5cf6 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);">
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -30px; left: -30px; width: 140px; height: 140px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        
        @php
            $status = $subscription->status ?? 'expired';
            $isExpired = $subscription ? $subscription->expired_at->isPast() : true;
        @endphp

        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px; position: relative; z-index: 1;">
            <div>
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                    <div style="background: rgba(255,255,255,0.2); padding: 8px; border-radius: 12px; backdrop-filter: blur(4px);">
                        <i data-feather="package" style="width: 28px; height: 28px; color: white;"></i>
                    </div>
                    <h1 style="font-size: 1.8rem; font-weight: 800; margin: 0; color: white !important;">PAKET {{ strtoupper($subscription->package_name ?? 'PENDING') }}</h1>
                    @if(!$isExpired)
                    <div style="background: rgba(255,255,255,0.2); padding: 6px 16px; border-radius: 999px; font-size: 0.75rem; font-weight: 700; border: 1px solid rgba(255,255,255,0.3);">
                        <i data-feather="check" style="width: 12px; height: 12px; display: inline;"></i> AKTIF
                    </div>
                    @else
                    <div style="background: rgba(239, 68, 68, 0.2); padding: 6px 16px; border-radius: 999px; font-size: 0.75rem; font-weight: 700; border: 1px solid rgba(239, 68, 68, 0.3);">
                        <i data-feather="x-circle" style="width: 12px; height: 12px; display: inline;"></i> KADALUARSA
                    </div>
                    @endif
                </div>
                <p style="color: rgba(255,255,255,0.95) !important; font-size: 1rem; margin: 0; font-weight: 500;">
                    @if($subscription && !$isExpired)
                        Berlaku hingga {{ $subscription->expired_at->format('d F Y') }}
                    @else
                        Langganan tidak aktif atau sudah berakhir
                    @endif
                </p>
                
                <!-- Progress Bar -->
                @if($subscription && !$isExpired)
                    @php
                        $daysLeft = now()->diffInDays($subscription->expired_at);
                        $daysTotal = $subscription->started_at->diffInDays($subscription->expired_at);
                        $progressPercent = ($daysLeft / $daysTotal) * 100;
                    @endphp
                    <div style="margin-top: 16px; max-width: 400px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="font-size: 0.75rem; font-weight: 600; color: rgba(255,255,255,0.8);">Sisa Waktu</span>
                            <span style="font-size: 0.75rem; font-weight: 700; color: white;">{{ $daysLeft }} hari lagi</span>
                        </div>
                        <div style="background: rgba(255,255,255,0.2); height: 8px; border-radius: 999px; overflow: hidden;">
                            <div style="background: white; height: 100%; border-radius: 999px; width: {{ $progressPercent }}%; transition: width 1s ease;"></div>
                        </div>
                    </div>
                @endif
            </div>
            <div>
                <a href="{{ route('admin.billing.plans') }}" style="background: white; color: #6366f1; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i data-feather="zap" style="width: 18px; height: 18px;"></i>
                    {{ $subscription && $subscription->package_name === 'advance' ? 'Perpanjang Langganan' : 'Upgrade Sekarang' }}
                </a>
            </div>
        </div>
    </div>

    <!-- Invoice History Table -->
    <div style="background: white; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); overflow: hidden;">
        <!-- Table Header -->
        <div style="padding: 24px 28px; border-bottom: 2px solid #f1f5f9;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0 0 4px 0;">Riwayat Transaksi</h2>
                    <p style="font-size: 0.875rem; color: #64748b; margin: 0;">Semua invoice pembayaran langganan</p>
                </div>
                <div style="background: #f5f3ff; color: #7c3aed; padding: 6px 16px; border-radius: 999px; font-size: 0.875rem; font-weight: 700;">
                    {{ $invoices->total() }} Invoice
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 14px 24px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">No. Invoice</th>
                        <th style="padding: 14px 24px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal</th>
                        <th style="padding: 14px 24px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Nominal</th>
                        <th style="padding: 14px 24px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                        <th style="padding: 14px 24px; text-align: right; font-size: 0.75rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 18px 24px;">
                            <span style="font-family: 'Courier New', monospace; font-size: 0.875rem; font-weight: 600; color: #334155;">{{ $invoice->invoice_number }}</span>
                        </td>
                        <td style="padding: 18px 24px;">
                            <div style="font-size: 0.875rem; font-weight: 600; color: #1e293b;">{{ $invoice->created_at->format('d M Y') }}</div>
                            <div style="font-size: 0.75rem; color: #94a3b8;">{{ $invoice->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td style="padding: 18px 24px;">
                            <span style="font-size: 1rem; font-weight: 700; color: #0f172a;">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                        </td>
                        <td style="padding: 18px 24px;">
                            @php
                                $statusConfig = match($invoice->status) {
                                    'paid' => ['bg' => '#ecfdf5', 'color' => '#047857', 'text' => 'LUNAS'],
                                    'pending' => ['bg' => '#fef3c7', 'color' => '#92400e', 'text' => 'PENDING'],
                                    'failed' => ['bg' => '#fef2f2', 'color' => '#b91c1c', 'text' => 'GAGAL'],
                                    default => ['bg' => '#f1f5f9', 'color' => '#475569', 'text' => 'UNKNOWN'],
                                };
                            @endphp
                            <div style="display: inline-block; background: {{ $statusConfig['bg'] }}; color: {{ $statusConfig['color'] }}; padding: 4px 12px; border-radius: 999px; font-size: 0.75rem; font-weight: 700;">
                                {{ $statusConfig['text'] }}
                            </div>
                        </td>
                        <td style="padding: 18px 24px; text-align: right;">
                            <a href="{{ route('admin.billing.show', $invoice->id) }}" style="background: #6366f1; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;" onmouseover="this.style.background='#4f46e5'" onmouseout="this.style.background='#6366f1'">
                                Detail
                                <i data-feather="arrow-right" style="width: 14px; height: 14px;"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 60px 24px; text-align: center;">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 12px; opacity: 0.5;">
                                <div style="background: #f1f5f9; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i data-feather="inbox" style="width: 36px; height: 36px; color: #94a3b8;"></i>
                                </div>
                                <div>
                                    <div style="font-size: 1rem; font-weight: 700; color: #64748b; margin-bottom: 4px;">Belum Ada Transaksi</div>
                                    <p style="font-size: 0.875rem; color: #94a3b8; margin: 0;">Mulai dengan memilih paket langganan Anda</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($invoices->hasPages())
        <div style="padding: 16px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0;">
            {{ $invoices->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
