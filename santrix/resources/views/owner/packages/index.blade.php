@extends('layouts.app')

@section('title', 'Manajemen Paket')
@section('page-title', 'Manajemen Paket')

@section('sidebar-menu')
    @include('owner.partials.sidebar-menu')
@endsection

@section('content')
<div style="background: white; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden;">
    <!-- Header Actions -->
    <div style="padding: 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: flex-end;">
        <a href="{{ route('owner.packages.create') }}" style="display: inline-flex; align-items: center; padding: 10px 20px; background: #4f46e5; color: white; border-radius: 8px; font-weight: 500; font-size: 0.875rem; text-decoration: none; transition: background 0.2s;" onmouseover="this.style.background='#4338ca'" onmouseout="this.style.background='#4f46e5'">
            <i data-feather="plus" style="width: 16px; height: 16px; margin-right: 8px;"></i>
            Tambah Paket Baru
        </a>
    </div>

    <!-- Table -->
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    <th style="padding: 16px 24px; font-weight: 600;">Nama Paket</th>
                    <th style="padding: 16px 24px; font-weight: 600;">Harga (Net)</th>
                    <th style="padding: 16px 24px; font-weight: 600;">Durasi</th>
                    <th style="padding: 16px 24px; font-weight: 600;">Status</th>
                    <th style="padding: 16px 24px; font-weight: 600; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody style="font-size: 0.875rem; color: #1e2937;">
                @forelse($packages as $package)
                <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                    <td style="padding: 16px 24px;">
                        <div style="font-weight: 500;">{{ $package->name }}</div>
                        <div style="font-size: 0.75rem; color: #9ca3af;">{{ $package->slug }}</div>
                    </td>
                    <td style="padding: 16px 24px;">
                        <div style="font-weight: 700; color: #1e2937;">{{ $package->formatted_price }}</div>
                        @if($package->discount_price)
                            <div style="font-size: 0.75rem; color: #ef4444; text-decoration: line-through;">{{ $package->formatted_discount_price }}</div>
                        @endif
                    </td>
                    <td style="padding: 16px 24px; color: #64748b;">
                        {{ $package->duration_months }} Bulan
                    </td>
                    <td style="padding: 16px 24px;">
                        @if($package->is_featured)
                            <span style="padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background: #e0e7ff; color: #4338ca;">Featured</span>
                        @else
                            <span style="padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background: #f1f5f9; color: #64748b;">Standard</span>
                        @endif
                    </td>
                    <td style="padding: 16px 24px; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 8px;">
                            <a href="{{ route('owner.packages.edit', $package->id) }}" style="padding: 6px 12px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.75rem; font-weight: 600; color: #4f46e5; text-decoration: none; display: inline-block;">
                                Edit
                            </a>
                            <form action="{{ route('owner.packages.destroy', $package->id) }}" method="POST" onsubmit="return confirmDelete(event)" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="padding: 6px 12px; background: white; border: 1px solid #fecaca; border-radius: 6px; font-size: 0.75rem; font-weight: 600; color: #b91c1c; cursor: pointer;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='white'">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 48px; text-align: center;">
                        <i data-feather="package" style="width: 48px; height: 48px; color: #cbd5e1; margin-bottom: 12px;"></i>
                        <p style="margin: 0; color: #9ca3af; font-size: 0.875rem;">Belum ada paket tersedia.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
