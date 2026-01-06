@extends('layouts.app')

@section('title', 'Billing & Paket')

@section('sidebar-menu')
    @include('admin.partials.sidebar-menu')
@endsection

@section('content')
<div style="padding: 32px;">
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <h1 style="font-size: 1.875rem; font-weight: 800; color: #1e293b; margin-bottom: 8px;">
            💳 Billing & Paket
        </h1>
        <p style="color: #64748b; font-size: 0.9375rem;">
            Kelola langganan dan paket fitur Santrix Anda
        </p>
    </div>

    <!-- Current Plan Card -->
    <div style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -30px; right: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -40px; left: 30%; width: 100px; height: 100px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
        
        <div style="position: relative; z-index: 1;">
            <p style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 8px;">PAKET SAAT INI</p>
            <h2 style="font-size: 2rem; font-weight: 800; margin-bottom: 16px;">
                {{ ucfirst($pesantren->package ?? 'Basic') }}
            </h2>
            <div style="display: flex; gap: 24px; flex-wrap: wrap;">
                <div>
                    <p style="font-size: 0.75rem; opacity: 0.8;">Status</p>
                    <p style="font-weight: 700;">
                        @if($pesantren->status === 'active' && $pesantren->expired_at && $pesantren->expired_at > now())
                            <span style="background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 20px;">✓ Aktif</span>
                        @else
                            <span style="background: rgba(255,200,100,0.3); padding: 4px 12px; border-radius: 20px;">⚠️ Tidak Aktif</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p style="font-size: 0.75rem; opacity: 0.8;">Berakhir</p>
                    <p style="font-weight: 700;">
                        {{ $pesantren->expired_at ? \Carbon\Carbon::parse($pesantren->expired_at)->format('d M Y') : '-' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Plans -->
    <div style="margin-bottom: 32px;">
        <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin-bottom: 20px;">Pilih Paket</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
            <!-- Basic Plan -->
            <div style="background: white; border-radius: 16px; padding: 24px; border: 2px solid #e5e7eb; transition: all 0.3s;">
                <h4 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin-bottom: 8px;">Basic</h4>
                <p style="color: #64748b; font-size: 0.875rem; margin-bottom: 16px;">Untuk pesantren kecil</p>
                <p style="font-size: 1.5rem; font-weight: 800; color: #6366f1; margin-bottom: 20px;">Rp 150.000<span style="font-size: 0.875rem; font-weight: 400; color: #64748b;">/bulan</span></p>
                <ul style="list-style: none; padding: 0; margin-bottom: 24px;">
                    <li style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; color: #374151;">
                        <svg style="width: 16px; height: 16px; color: #10b981;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        Max 100 Santri
                    </li>
                    <li style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; color: #374151;">
                        <svg style="width: 16px; height: 16px; color: #10b981;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        Modul Dasar
                    </li>
                </ul>
                <button style="width: 100%; padding: 12px; background: #f1f5f9; color: #64748b; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">Paket Saat Ini</button>
            </div>

            <!-- Advance Plan -->
            <div style="background: white; border-radius: 16px; padding: 24px; border: 2px solid #6366f1; position: relative; transition: all 0.3s;">
                <span style="position: absolute; top: -10px; right: 16px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">POPULER</span>
                <h4 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin-bottom: 8px;">Advance</h4>
                <p style="color: #64748b; font-size: 0.875rem; margin-bottom: 16px;">Untuk pesantren menengah</p>
                <p style="font-size: 1.5rem; font-weight: 800; color: #6366f1; margin-bottom: 20px;">Rp 300.000<span style="font-size: 0.875rem; font-weight: 400; color: #64748b;">/bulan</span></p>
                <ul style="list-style: none; padding: 0; margin-bottom: 24px;">
                    <li style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; color: #374151;">
                        <svg style="width: 16px; height: 16px; color: #10b981;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        Max 500 Santri
                    </li>
                    <li style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; color: #374151;">
                        <svg style="width: 16px; height: 16px; color: #10b981;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        Semua Modul
                    </li>
                    <li style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; color: #374151;">
                        <svg style="width: 16px; height: 16px; color: #10b981;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        Payment Gateway
                    </li>
                </ul>
                <button style="width: 100%; padding: 12px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">Upgrade Sekarang</button>
            </div>

            <!-- Enterprise Plan -->
            <div style="background: white; border-radius: 16px; padding: 24px; border: 2px solid #e5e7eb; transition: all 0.3s;">
                <h4 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin-bottom: 8px;">Enterprise</h4>
                <p style="color: #64748b; font-size: 0.875rem; margin-bottom: 16px;">Untuk pesantren besar</p>
                <p style="font-size: 1.5rem; font-weight: 800; color: #6366f1; margin-bottom: 20px;">Rp 500.000<span style="font-size: 0.875rem; font-weight: 400; color: #64748b;">/bulan</span></p>
                <ul style="list-style: none; padding: 0; margin-bottom: 24px;">
                    <li style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; color: #374151;">
                        <svg style="width: 16px; height: 16px; color: #10b981;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        Unlimited Santri
                    </li>
                    <li style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; color: #374151;">
                        <svg style="width: 16px; height: 16px; color: #10b981;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        Priority Support
                    </li>
                    <li style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; color: #374151;">
                        <svg style="width: 16px; height: 16px; color: #10b981;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        Custom Branding
                    </li>
                </ul>
                <button style="width: 100%; padding: 12px; background: #f1f5f9; color: #374151; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">Hubungi Kami</button>
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div style="background: #fffbeb; border: 1px solid #fbbf24; border-radius: 12px; padding: 16px; display: flex; align-items: center; gap: 12px;">
        <svg style="width: 24px; height: 24px; color: #f59e0b; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p style="color: #92400e; font-size: 0.875rem;">
            Untuk upgrade atau perpanjangan paket, silakan hubungi admin Santrix melalui WhatsApp atau email.
        </p>
    </div>
</div>
@endsection
