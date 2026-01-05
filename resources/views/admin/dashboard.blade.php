@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('sidebar-menu')
    @include('admin.partials.sidebar-menu')
@endsection

@include('components.bottom-nav', ['active' => 'dashboard', 'context' => 'admin'])

@section('drawer-menu')
    <li class="drawer-menu-item">
        <form method="POST" action="{{ route('tenant.logout') }}">
            @csrf
            <button type="submit" class="drawer-menu-link" style="width: 100%; background: none; border: none; cursor: pointer; text-align: left;">
                <i data-feather="log-out"></i>
                <span>Logout</span>
            </button>
        </form>
    </li>
@endsection

@section('content')
    <!-- Welcome Banner -->
    <div style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); border-radius: 16px; padding: 32px; margin-bottom: 32px; box-shadow: 0 10px 30px rgba(79, 70, 229, 0.3); position: relative; overflow: hidden;">
        <div style="position: absolute; top: -30px; right: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -40px; left: 30%; width: 100px; height: 100px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
        
        <div class="welcome-banner-content" style="display: flex; align-items: center; gap: 24px; position: relative; z-index: 1; color: white;">
            <div style="background: rgba(255,255,255,0.2); width: 64px; height: 64px; border-radius: 16px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                <i data-feather="shield" style="width: 32px; height: 32px; color: white;"></i>
            </div>
            <div>
                <h2 style="font-size: 1.875rem; font-weight: 800; margin: 0 0 6px 0; letter-spacing: -0.025em;">Assalamualaikum {{ ucwords(str_replace('_', ' ', auth()->user()->role)) }} {{ auth()->user()->pesantren->nama ?? '' }}</h2>
                <p style="opacity: 0.9; font-size: 1.05rem; font-weight: 400; margin: 0;">Dashboard Administrator - Pusat kendali sistem manajemen pesantren.</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 32px;">
        
        {{-- TODO: Implement settings feature
        <!-- Settings/App Card -->
        <div class="card" style="background: white; border-radius: 16px; pading: 24px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="padding: 24px;">
                <div style="width: 48px; height: 48px; background: #eff6ff; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                    <i data-feather="sliders" style="width: 24px; height: 24px; color: #3b82f6;"></i>
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e2937; margin-bottom: 8px;">Pengaturan Aplikasi</h3>
                <p style="color: #64748b; font-size: 0.875rem; line-height: 1.5; margin-bottom: 24px;">
                    Konfigurasi identitas aplikasi, kontak, dan preferensi sistem umum.
                </p>
                <a href="{{ route('admin.pengaturan') }}?tab=app" style="display: inline-flex; align-items: center; color: #3b82f6; font-weight: 600; text-decoration: none; font-size: 0.875rem;">
                    Buka Pengaturan <i data-feather="arrow-right" style="width: 16px; height: 16px; margin-left: 4px;"></i>
                </a>
            </div>
        </div>

        <!-- User Management Card -->
        <div class="card" style="background: white; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="padding: 24px;">
                <div style="width: 48px; height: 48px; background: #ecfdf5; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                    <i data-feather="users" style="width: 24px; height: 24px; color: #10b981;"></i>
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e2937; margin-bottom: 8px;">Manajemen User</h3>
                <p style="color: #64748b; font-size: 0.875rem; line-height: 1.5; margin-bottom: 24px;">
                    Kelola akun pengguna, peran (role), dan hak akses sistem.
                </p>
                <a href="{{ route('admin.pengaturan') }}?tab=users" style="display: inline-flex; align-items: center; color: #10b981; font-weight: 600; text-decoration: none; font-size: 0.875rem;">
                    Kelola User <i data-feather="arrow-right" style="width: 16px; height: 16px; margin-left: 4px;"></i>
                </a>
            </div>
        </div>
        --}}

        {{-- TODO: Implement kelas & asrama feature
        <!-- Kelas & Asrama Card -->
        <div class="card" style="background: white; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="padding: 24px;">
                <div style="width: 48px; height: 48px; background: #fef3c7; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                    <i data-feather="grid" style="width: 24px; height: 24px; color: #d97706;"></i>
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e2937; margin-bottom: 8px;">Kelas & Asrama</h3>
                <p style="color: #64748b; font-size: 0.875rem; line-height: 1.5; margin-bottom: 24px;">
                    Manajemen data master kelas, asrama, dan kamar santri.
                </p>
                <a href="{{ route('admin.pengaturan') }}?tab=kelas-asrama" style="display: inline-flex; align-items: center; color: #d97706; font-weight: 600; text-decoration: none; font-size: 0.875rem;">
                    Kelola Data Master <i data-feather="arrow-right" style="width: 16px; height: 16px; margin-left: 4px;"></i>
                </a>
            </div>
        </div>
        --}}

    </div>

    <!-- System Info (Simplified) -->
    <div style="background: white; border-radius: 16px; padding: 24px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
        <h3 style="font-size: 1rem; font-weight: 700; color: #1e2937; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
            <i data-feather="activity" style="width: 18px; height: 18px; color: #64748b;"></i>
            Status Sistem
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <div style="padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="display: block; font-size: 0.75rem; color: #64748b; margin-bottom: 4px;">Versi Aplikasi</span>
                <span style="display: block; font-weight: 600; color: #334155;">v1.0.0</span>
            </div>
            <div style="padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="display: block; font-size: 0.75rem; color: #64748b; margin-bottom: 4px;">Framework</span>
                <span style="display: block; font-weight: 600; color: #334155;">Laravel v{{ Illuminate\Foundation\Application::VERSION }}</span>
            </div>
            <div style="padding: 12px; background: #f8fafc; border-radius: 8px;">
                <span style="display: block; font-size: 0.75rem; color: #64748b; margin-bottom: 4px;">PHP Version</span>
                <span style="display: block; font-weight: 600; color: #334155;">{{ phpversion() }}</span>
            </div>
        </div>
    </div>
@endsection
