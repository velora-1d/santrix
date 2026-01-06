@extends('layouts.app')

@section('title', 'Branding')

@section('sidebar-menu')
    @include('admin.partials.sidebar-menu')
@endsection

@section('content')
<div style="padding: 32px;">
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <h1 style="font-size: 1.875rem; font-weight: 800; color: #1e293b; margin-bottom: 8px;">
            🎨 Pengaturan Branding
        </h1>
        <p style="color: #64748b; font-size: 0.9375rem;">
            Sesuaikan tampilan aplikasi dengan identitas pesantren Anda
        </p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border-left: 4px solid #10b981; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
            <svg style="width: 20px; height: 20px; color: #059669;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span style="color: #047857; font-weight: 600;">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Branding Form -->
    <form action="{{ route('admin.branding.update') }}" method="POST" style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        @csrf
        
        <h2 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <svg style="width: 20px; height: 20px; color: #6366f1;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
            </svg>
            Warna Tema
        </h2>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
            <div>
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Warna Utama (Primary)</label>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <input type="color" name="primary_color" value="{{ $pesantren->primary_color ?? '#6366f1' }}" style="width: 60px; height: 40px; border: none; cursor: pointer; border-radius: 8px;">
                    <input type="text" name="primary_color_text" value="{{ $pesantren->primary_color ?? '#6366f1' }}" style="flex: 1; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem;" readonly>
                </div>
                <p style="color: #94a3b8; font-size: 0.75rem; margin-top: 6px;">Digunakan untuk tombol, link, dan elemen utama</p>
            </div>

            <div>
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Warna Sekunder (Secondary)</label>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <input type="color" name="secondary_color" value="{{ $pesantren->secondary_color ?? '#8b5cf6' }}" style="width: 60px; height: 40px; border: none; cursor: pointer; border-radius: 8px;">
                    <input type="text" name="secondary_color_text" value="{{ $pesantren->secondary_color ?? '#8b5cf6' }}" style="flex: 1; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem;" readonly>
                </div>
                <p style="color: #94a3b8; font-size: 0.75rem; margin-top: 6px;">Digunakan untuk aksen dan gradient</p>
            </div>
        </div>

        <!-- Preview -->
        <div style="background: #f8fafc; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
            <h3 style="font-size: 0.875rem; font-weight: 600; color: #64748b; margin-bottom: 16px;">PREVIEW</h3>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <button type="button" style="padding: 10px 20px; background: {{ $pesantren->primary_color ?? '#6366f1' }}; color: white; border: none; border-radius: 8px; font-weight: 600;">Tombol Utama</button>
                <button type="button" style="padding: 10px 20px; background: linear-gradient(135deg, {{ $pesantren->primary_color ?? '#6366f1' }} 0%, {{ $pesantren->secondary_color ?? '#8b5cf6' }} 100%); color: white; border: none; border-radius: 8px; font-weight: 600;">Tombol Gradient</button>
                <button type="button" style="padding: 10px 20px; background: white; color: {{ $pesantren->primary_color ?? '#6366f1' }}; border: 2px solid {{ $pesantren->primary_color ?? '#6366f1' }}; border-radius: 8px; font-weight: 600;">Tombol Outline</button>
            </div>
        </div>

        <button type="submit" style="padding: 14px 32px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; border: none; border-radius: 10px; font-weight: 700; font-size: 1rem; cursor: pointer; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(99, 102, 241, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(99, 102, 241, 0.3)'">
            💾 Simpan Perubahan
        </button>
    </form>

    <!-- Info Box -->
    <div style="background: #eff6ff; border: 1px solid #3b82f6; border-radius: 12px; padding: 16px; margin-top: 24px; display: flex; align-items: center; gap: 12px;">
        <svg style="width: 24px; height: 24px; color: #3b82f6; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p style="color: #1e40af; font-size: 0.875rem;">
            Perubahan warna akan diterapkan setelah halaman di-refresh. Untuk mengubah logo, silakan ke menu <a href="{{ route('admin.pengaturan') }}" style="font-weight: 600; text-decoration: underline;">Pengaturan</a>.
        </p>
    </div>
</div>

<script>
// Sync color picker with text input
document.querySelectorAll('input[type="color"]').forEach(picker => {
    picker.addEventListener('input', (e) => {
        const textInput = e.target.nextElementSibling;
        if (textInput) textInput.value = e.target.value;
    });
});
</script>
@endsection
