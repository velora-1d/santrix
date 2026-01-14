@extends('layouts.app')

@section('title', 'Absensi Harian Guru')
@section('page-title', 'Absensi Guru / Asatidz')

@section('sidebar-menu')
    @include('pendidikan.partials.sidebar-menu')
@endsection

@section('content')
    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 24px;">
        <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <div style="font-size: 0.875rem; color: #64748b; font-weight: 600; margin-bottom: 8px;">Hadir Tepat Waktu</div>
            <div style="font-size: 2rem; font-weight: 800; color: #10b981;">{{ $total_hadir }}</div>
        </div>
        <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <div style="font-size: 0.875rem; color: #64748b; font-weight: 600; margin-bottom: 8px;">Terlambat</div>
            <div style="font-size: 2rem; font-weight: 800; color: #f59e0b;">{{ $total_telat }}</div>
        </div>
        <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <div style="font-size: 0.875rem; color: #64748b; font-weight: 600; margin-bottom: 8px;">Izin</div>
            <div style="font-size: 2rem; font-weight: 800; color: #3b82f6;">{{ $total_izin }}</div>
        </div>
         <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <div style="font-size: 0.875rem; color: #64748b; font-weight: 600; margin-bottom: 8px;">Sakit</div>
            <div style="font-size: 2rem; font-weight: 800; color: #ef4444;">{{ $total_sakit }}</div>
        </div>
    </div>

    <!-- Filter & Table -->
    <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #1f2937; margin: 0;">Rekap Kehadiran</h3>
            <form method="GET" action="{{ route('pendidikan.absensi-guru') }}" style="display: flex; gap: 12px;">
                <input type="date" name="tanggal" value="{{ $date }}" onchange="this.form.submit()" style="padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-weight: 600; color: #334155;">
            </form>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 16px; text-align: left; font-weight: 700; color: #1e293b;">Nama Ustadz / Guru</th>
                        <th style="padding: 16px; text-align: center; font-weight: 700; color: #1e293b;">Jam Masuk</th>
                        <th style="padding: 16px; text-align: center; font-weight: 700; color: #1e293b;">Jam Pulang</th>
                        <th style="padding: 16px; text-align: center; font-weight: 700; color: #1e293b;">Foto Masuk</th>
                        <th style="padding: 16px; text-align: center; font-weight: 700; color: #1e293b;">Foto Pulang</th>
                        <th style="padding: 16px; text-align: center; font-weight: 700; color: #1e293b;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensis as $absen)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                        <td style="padding: 16px;">
                            <div style="font-weight: 700; color: #1f2937;">{{ $absen->user->name }}</div>
                            <div style="font-size: 0.85rem; color: #64748b;">{{ $absen->user->email }}</div>
                        </td>
                        <td style="padding: 16px; text-align: center; font-weight: 600; color: #334155;">
                            {{ $absen->jam_masuk }}
                        </td>
                        <td style="padding: 16px; text-align: center; font-weight: 600; color: #334155;">
                            {{ $absen->jam_pulang ?? '-' }}
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            @if($absen->foto_masuk)
                                <a href="{{ asset('storage/' . $absen->foto_masuk) }}" target="_blank" style="display: inline-block; width: 40px; height: 40px; border-radius: 50%; overflow: hidden; border: 2px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                    <img src="{{ asset('storage/' . $absen->foto_masuk) }}" alt="Foto Masuk" style="width: 100%; height: 100%; object-fit: cover;">
                                </a>
                            @else
                                <span style="color: #cbd5e1;">-</span>
                            @endif
                        </td>
                        <td style="padding: 16px; text-align: center;">
                             @if($absen->foto_pulang)
                                <a href="{{ asset('storage/' . $absen->foto_pulang) }}" target="_blank" style="display: inline-block; width: 40px; height: 40px; border-radius: 50%; overflow: hidden; border: 2px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                    <img src="{{ asset('storage/' . $absen->foto_pulang) }}" alt="Foto Pulang" style="width: 100%; height: 100%; object-fit: cover;">
                                </a>
                            @else
                                <span style="color: #cbd5e1;">-</span>
                            @endif
                        </td>
                         <td style="padding: 16px; text-align: center;">
                            @php
                                $statusColor = match($absen->status) {
                                    'hadir' => ['bg' => '#dcfce7', 'text' => '#166534'],
                                    'telat' => ['bg' => '#fef3c7', 'text' => '#b45309'],
                                    'izin' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                    'sakit' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                    default => ['bg' => '#f1f5f9', 'text' => '#475569'],
                                };
                            @endphp
                             <span style="background: {{ $statusColor['bg'] }}; color: {{ $statusColor['text'] }}; padding: 4px 10px; border-radius: 99px; font-size: 0.8rem; font-weight: 700;">
                                {{ ucfirst($absen->status) }}
                             </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 32px; text-align: center; color: #94a3b8;">
                            <div style="margin-bottom: 12px;">
                                <i data-feather="user-x" style="width: 48px; height: 48px; opacity: 0.5;"></i>
                            </div>
                            Belum ada data absensi hari ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
