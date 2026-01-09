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
            Pilih paket yang sesuai dengan kebutuhan pesantren Anda.
        </p>
    </div>

    @php
        // Load packages from database (same as landing page and owner dashboard)
        $packages = \App\Models\Package::orderBy('sort_order')->orderBy('price')->get();
    @endphp

    <!-- Pricing Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; max-width: 1200px; margin: 0 auto; position: relative; z-index: 1;">
        
        @foreach($packages as $package)
        <div style="background: {{ $package->is_featured ? 'linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%), rgba(255,255,255,0.98)' : 'rgba(255,255,255,0.95)' }}; backdrop-filter: blur(20px); border-radius: {{ $package->is_featured ? '32px' : '24px' }}; padding: {{ $package->is_featured ? '40px 32px' : '32px 28px' }}; box-shadow: {{ $package->is_featured ? '0 30px 80px rgba(102, 126, 234, 0.5)' : '0 20px 60px rgba(0,0,0,0.3)' }}; border: {{ $package->is_featured ? '3px solid #667eea' : '2px solid rgba(255,255,255,0.5)' }}; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); position: relative; overflow: hidden; {{ $package->is_featured ? 'transform: scale(1.02);' : '' }}" 
            onmouseover="this.style.transform='translateY(-8px) {{ $package->is_featured ? 'scale(1.05)' : '' }}'" 
            onmouseout="this.style.transform='translateY(0) {{ $package->is_featured ? 'scale(1.02)' : '' }}'">
            
            @if($package->is_featured)
            <!-- Popular Badge -->
            <div style="position: absolute; top: -10px; right: 30px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 10px 24px; border-radius: 0 0 16px 16px; box-shadow: 0 8px 24px rgba(245, 87, 108, 0.5);">
                <span style="font-size: 0.75rem; font-weight: 800; color: white; letter-spacing: 1px;">⭐ REKOMENDASI</span>
            </div>
            @endif

            <!-- Package Name Badge -->
            <div style="position: absolute; top: 20px; right: 20px; background: {{ $package->is_featured ? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' : 'linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%)' }}; padding: 6px 14px; border-radius: 50px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                <span style="font-size: 0.65rem; font-weight: 700; color: {{ $package->is_featured ? 'white' : '#1a202c' }}; letter-spacing: 1px; text-transform: uppercase;">{{ $package->duration_months }} Bulan</span>
            </div>

            <!-- Icon -->
            <div style="width: {{ $package->is_featured ? '72px' : '56px' }}; height: {{ $package->is_featured ? '72px' : '56px' }}; background: {{ $package->is_featured ? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' : 'linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%)' }}; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; box-shadow: 0 8px 24px rgba(0,0,0,0.2); {{ $package->is_featured ? 'margin-top: 32px;' : '' }}">
                <svg style="width: {{ $package->is_featured ? '36px' : '28px' }}; height: {{ $package->is_featured ? '36px' : '28px' }}; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if($package->is_featured)
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    @else
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    @endif
                </svg>
            </div>

            <!-- Title -->
            <h2 style="font-size: {{ $package->is_featured ? '1.75rem' : '1.5rem' }}; font-weight: 800; {{ $package->is_featured ? 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;' : 'color: #1a202c;' }} margin: 0 0 8px 0;">
                {{ $package->name }}
            </h2>
            
            <!-- Duration -->
            <p style="color: #64748b; font-size: 0.875rem; margin-bottom: 16px;">
                / {{ $package->duration_months }} bulan
            </p>

            <!-- Price -->
            <div style="margin-bottom: 20px;">
                @if($package->discount_price)
                <span style="text-decoration: line-through; color: #94a3b8; font-size: 1rem; display: block;">{{ $package->formatted_discount_price }}</span>
                @endif
                <div style="font-size: {{ $package->is_featured ? '2.25rem' : '2rem' }}; font-weight: 900; {{ $package->is_featured ? 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;' : 'color: #1a202c;' }}">
                    {{ $package->formatted_price }}
                </div>
            </div>

            <!-- Features -->
            @if($package->features && is_array($package->features))
            <div style="margin-bottom: 24px; min-height: 120px;">
                @foreach(array_slice($package->features, 0, 5) as $feature)
                <div style="display: flex; align-items: start; gap: 8px; margin-bottom: 10px; {{ isset($feature['included']) && !$feature['included'] ? 'opacity: 0.4;' : '' }}">
                    @if(isset($feature['included']) && $feature['included'])
                    <svg style="width: 18px; height: 18px; color: {{ $package->is_featured ? '#8b5cf6' : '#10b981' }}; flex-shrink: 0; margin-top: 2px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                    @else
                    <svg style="width: 18px; height: 18px; color: #ef4444; flex-shrink: 0; margin-top: 2px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    @endif
                    <span style="color: {{ isset($feature['included']) && $feature['included'] ? '#1e293b' : '#64748b' }}; font-size: 0.875rem; font-weight: 500; {{ isset($feature['included']) && !$feature['included'] ? 'text-decoration: line-through;' : '' }}">
                        {{ $feature['name'] ?? $feature }}
                    </span>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Subscribe Button -->
            <form action="{{ route('admin.billing.subscribe') }}" method="POST">
                @csrf
                <input type="hidden" name="package" value="{{ $package->slug }}">
                
                <button type="submit" style="width: 100%; padding: {{ $package->is_featured ? '16px 28px' : '14px 24px' }}; background: {{ $package->is_featured ? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' : 'linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%)' }}; color: {{ $package->is_featured ? 'white' : '#1a202c' }}; border: none; border-radius: 12px; font-weight: 700; font-size: {{ $package->is_featured ? '1.0625rem' : '1rem' }}; cursor: pointer; box-shadow: 0 8px 24px {{ $package->is_featured ? 'rgba(102, 126, 234, 0.5)' : 'rgba(132, 250, 176, 0.4)' }}; transition: all 0.3s;" 
                    onmouseover="this.style.transform='translateY(-2px)'" 
                    onmouseout="this.style.transform='translateY(0)'">
                    <span>{{ $package->is_featured ? '🚀 ' : '' }}Pilih {{ $package->name }} →</span>
                </button>
            </form>
        </div>
        @endforeach
    </div>

    @if($packages->isEmpty())
    <div style="text-align: center; padding: 60px; color: white;">
        <svg style="width: 64px; height: 64px; margin: 0 auto 16px; opacity: 0.7;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 8px;">Belum Ada Paket</h3>
        <p style="opacity: 0.8;">Silakan hubungi Owner untuk menambahkan paket berlangganan.</p>
    </div>
    @endif

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

<style>
@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-30px) rotate(5deg); }
}
</style>
@endsection
