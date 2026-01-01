@extends('layouts.app')

@section('title', 'Pilih Paket')

@section('sidebar-menu')
    @include('admin.partials.sidebar-menu')
@endsection

@section('content')
<div style="min-height: 100vh; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 60px 40px; position: relative; overflow: hidden;">
    
    <!-- Animated Background Elements -->
    <div style="position: absolute; top: -100px; right: -100px; width: 400px; height: 400px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius: 50%; animation: float 20s infinite ease-in-out;"></div>
    <div style="position: absolute; bottom: -150px; left: -150px; width: 500px; height: 500px; background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%); border-radius: 50%; animation: float 25s infinite ease-in-out reverse;"></div>

    <!-- Header -->
    <div style="text-align: center; margin-bottom: 48px; position: relative; z-index: 1;">
        <div style="display: inline-block; background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); padding: 8px 20px; border-radius: 50px; margin-bottom: 16px; border: 1px solid rgba(255,255,255,0.3);">
            <span style="font-size: 0.75rem; font-weight: 600; color: white; letter-spacing: 2px;">⚡ PILIH PAKET TERBAIK</span>
        </div>
        <h1 style="font-size: 2.5rem; font-weight: 900; color: white; margin: 0 0 12px 0; text-shadow: 0 4px 20px rgba(0,0,0,0.3); line-height: 1.2;">
            Tingkatkan Pelayanan<br>Untuk Santri Anda
        </h1>
        <p style="font-size: 1rem; color: rgba(255,255,255,0.9); max-width: 600px; margin: 0 auto; line-height: 1.6;">
            Pilih paket yang sesuai dengan kebutuhan pesantren Anda. Tersedia pilihan 3 bulan atau 6 bulan.
        </p>
    </div>

    <!-- Pricing Cards -->
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 32px; max-width: 1100px; margin: 0 auto; position: relative; z-index: 1;">
        
        @php
            $plans = config('subscription.plans');
            $basicPlans = collect($plans)->filter(fn($p) => str_starts_with($p['id'], 'basic'));
            $advancePlans = collect($plans)->filter(fn($p) => str_starts_with($p['id'], 'advance'));
        @endphp

        <!-- BASIC Package -->
        <div style="background: rgba(255,255,255,0.95); backdrop-filter: blur(20px); border-radius: 24px; padding: 36px 32px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); border: 2px solid rgba(255,255,255,0.5); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); position: relative; overflow: hidden;" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 30px 80px rgba(0,0,0,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 20px 60px rgba(0,0,0,0.3)'">
            
            <!-- Badge -->
            <div style="position: absolute; top: 20px; right: 20px; background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%); padding: 6px 16px; border-radius: 50px; box-shadow: 0 4px 12px rgba(132, 250, 176, 0.4);">
                <span style="font-size: 0.7rem; font-weight: 700; color: #1a202c; letter-spacing: 1px;">BASIC</span>
            </div>

            <!-- Icon -->
            <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; box-shadow: 0 8px 24px rgba(132, 250, 176, 0.3);">
                <svg style="width: 32px; height: 32px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <!-- Title -->
            <h2 style="font-size: 1.5rem; font-weight: 800; color: #1a202c; margin: 0 0 8px 0;">BASIC</h2>
            
            <p style="color: #475569; font-size: 0.9375rem; line-height: 1.6; margin-bottom: 24px;">
                Solusi ideal untuk mengelola data santri dengan fitur-fitur esensial.
            </p>

            <!-- Features -->
            <div style="margin-bottom: 32px;">
                <div style="display: flex; align-items: start; gap: 10px; margin-bottom: 12px;">
                    <svg style="width: 20px; height: 20px; color: #10b981; flex-shrink: 0; margin-top: 2px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span style="color: #1e293b; font-size: 0.9375rem; font-weight: 500;">Manajemen Santri & Keuangan</span>
                </div>
                <div style="display: flex; align-items: start; gap: 10px; margin-bottom: 12px;">
                    <svg style="width: 20px; height: 20px; color: #10b981; flex-shrink: 0; margin-top: 2px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span style="color: #1e293b; font-size: 0.9375rem; font-weight: 500;">Laporan Nilai & Absensi</span>
                </div>
                <div style="display: flex; align-items: start; gap: 10px; margin-bottom: 12px; opacity: 0.4;">
                    <svg style="width: 20px; height: 20px; color: #ef4444; flex-shrink: 0; margin-top: 2px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span style="color: #64748b; font-size: 0.9375rem; font-weight: 500; text-decoration: line-through;">Payment Gateway</span>
                </div>
            </div>

            <!-- Package Selection Form -->
            <form action="{{ route('admin.billing.subscribe') }}" method="POST">
                @csrf
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 0.875rem;">Pilih Durasi:</label>
                    <select name="package" required style="width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 1rem; font-weight: 600; color: #1e293b; background: white; cursor: pointer; transition: all 0.2s;" onchange="updatePrice(this, 'basic')">
                        @foreach($basicPlans as $plan)
                        <option value="{{ $plan['id'] }}" data-price="{{ $plan['formatted_price'] }}" data-period="{{ $plan['period'] }}">
                            {{ $plan['duration_months'] }} Bulan - {{ $plan['formatted_price'] }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div id="basic-price-display" style="margin-bottom: 20px; padding: 16px; background: #f8fafc; border-radius: 12px; text-align: center;">
                    <div style="font-size: 2rem; font-weight: 900; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        {{ $basicPlans->first()['formatted_price'] }}
                    </div>
                    <p style="color: #64748b; font-size: 0.875rem; margin: 4px 0 0 0;">{{ $basicPlans->first()['period'] }}</p>
                </div>

                <button type="submit" style="width: 100%; padding: 14px 28px; background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%); color: #1a202c; border: none; border-radius: 12px; font-weight: 700; font-size: 1rem; cursor: pointer; box-shadow: 0 8px 24px rgba(132, 250, 176, 0.4); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 32px rgba(132, 250, 176, 0.5)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 24px rgba(132, 250, 176, 0.4)'">
                    <span>Pilih Paket Basic →</span>
                </button>
            </form>
        </div>

        <!-- ADVANCE Package (POPULAR) -->
        <div style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%), rgba(255,255,255,0.98); backdrop-filter: blur(20px); border-radius: 32px; padding: 48px 40px; box-shadow: 0 30px 80px rgba(102, 126, 234, 0.5); border: 3px solid #667eea; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); position: relative; overflow: hidden; transform: scale(1.05);" onmouseover="this.style.transform='translateY(-12px) scale(1.08)'; this.style.boxShadow='0 40px 100px rgba(102, 126, 234, 0.6)'" onmouseout="this.style.transform='translateY(0) scale(1.05)'; this.style.boxShadow='0 30px 80px rgba(102, 126, 234, 0.5)'">
            
            <!-- Popular Badge -->
            <div style="position: absolute; top: -10px; right: 40px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 12px 32px; border-radius: 0 0 20px 20px; box-shadow: 0 8px 24px rgba(245, 87, 108, 0.5);">
                <span style="font-size: 0.875rem; font-weight: 800; color: white; letter-spacing: 1.5px;">⭐ MOST POPULAR</span>
            </div>

            <!-- Badge -->
            <div style="position: absolute; top: 24px; right: 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 8px 20px; border-radius: 50px; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);">
                <span style="font-size: 0.75rem; font-weight: 700; color: white; letter-spacing: 1px;">ADVANCE</span>
            </div>

            <!-- Icon -->
            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin-bottom: 24px; box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4); margin-top: 40px;">
                <svg style="width: 40px; height: 40px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>

            <!-- Title -->
            <h2 style="font-size: 2rem; font-weight: 800; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin: 0 0 12px 0;">ADVANCE</h2>
            
            <p style="color: #475569; font-size: 1rem; line-height: 1.7; margin-bottom: 32px; font-weight: 500;">
                Solusi premium dengan otomasi penuh dan payment gateway.
            </p>

            <!-- Features -->
            <div style="margin-bottom: 32px;">
                <div style="display: flex; align-items: start; gap: 12px; margin-bottom: 16px;">
                    <svg style="width: 24px; height: 24px; color: #8b5cf6; flex-shrink: 0; margin-top: 2px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span style="color: #1e293b; font-size: 1rem; font-weight: 600;">Semua Fitur BASIC</span>
                </div>
                <div style="display: flex; align-items: start; gap: 12px; margin-bottom: 16px;">
                    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 4px; border-radius: 6px;">
                        <svg style="width: 16px; height: 16px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <span style="color: #1e293b; font-size: 1rem; font-weight: 600;">Payment Gateway Midtrans</span>
                </div>
                <div style="display: flex; align-items: start; gap: 12px; margin-bottom: 16px;">
                    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 4px; border-radius: 6px;">
                        <svg style="width: 16px; height: 16px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <span style="color: #1e293b; font-size: 1rem; font-weight: 600;">Virtual Account & Kartu Digital</span>
                </div>
            </div>

            <!-- Package Selection Form -->
            <form action="{{ route('admin.billing.subscribe') }}" method="POST">
                @csrf
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 0.875rem;">Pilih Durasi:</label>
                    <select name="package" required style="width: 100%; padding: 12px 16px; border: 2px solid #667eea; border-radius: 12px; font-size: 1rem; font-weight: 600; color: #1e293b; background: white; cursor: pointer; transition: all 0.2s;" onchange="updatePrice(this, 'advance')">
                        @foreach($advancePlans as $plan)
                        <option value="{{ $plan['id'] }}" data-price="{{ $plan['formatted_price'] }}" data-period="{{ $plan['period'] }}" {{ $plan['is_featured'] ? 'selected' : '' }}>
                            {{ $plan['duration_months'] }} Bulan - {{ $plan['formatted_price'] }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div id="advance-price-display" style="margin-bottom: 20px; padding: 16px; background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); border-radius: 12px; text-align: center;">
                    <div style="font-size: 2.5rem; font-weight: 900; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        {{ $advancePlans->firstWhere('is_featured', true)['formatted_price'] }}
                    </div>
                    <p style="color: #64748b; font-size: 0.875rem; margin: 4px 0 0 0;">{{ $advancePlans->firstWhere('is_featured', true)['period'] }}</p>
                </div>

                <button type="submit" style="width: 100%; padding: 18px 32px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 16px; font-weight: 700; font-size: 1.125rem; cursor: pointer; box-shadow: 0 12px 32px rgba(102, 126, 234, 0.5); transition: all 0.3s; position: relative; overflow: hidden;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 16px 40px rgba(102, 126, 234, 0.6)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 12px 32px rgba(102, 126, 234, 0.5)'">
                    <span style="position: relative; z-index: 1;">🚀 Pilih Paket Advance</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Help Section -->
    <div style="text-align: center; margin-top: 60px; position: relative; z-index: 1;">
        <div style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border-radius: 20px; padding: 32px; max-width: 600px; margin: 0 auto; border: 1px solid rgba(255,255,255,0.3);">
            <svg style="width: 48px; height: 48px; color: white; margin: 0 auto 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 style="font-size: 1.5rem; font-weight: 700; color: white; margin: 0 0 12px 0;">Butuh Bantuan? Hubungi Tim Kami</h3>
            <p style="color: rgba(255,255,255,0.9); font-size: 1rem; margin: 0;">
                Tim support kami siap membantu Anda memilih paket yang tepat untuk pesantren Anda.
            </p>
        </div>
    </div>
</div>

<script>
function updatePrice(select, packageType) {
    const option = select.options[select.selectedIndex];
    const price = option.dataset.price;
    const period = option.dataset.period;
    
    const displayDiv = document.getElementById(packageType + '-price-display');
    displayDiv.querySelector('div').textContent = price;
    displayDiv.querySelector('p').textContent = period;
}
</script>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-30px) rotate(5deg); }
}
</style>
@endsection
