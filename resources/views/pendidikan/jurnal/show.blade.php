@extends('layouts.app')

@section('title', 'Detail Jurnal KBM')
@section('page-title', 'Detail Jurnal')

@section('sidebar-menu')
    @include('pendidikan.partials.sidebar-menu')
@endsection

@section('content')
    <a href="{{ route('pendidikan.jurnal') }}" style="display: inline-flex; align-items: center; color: #64748b; text-decoration: none; font-weight: 600; margin-bottom: 24px;">
        <i data-feather="arrow-left" style="width: 20px; height: 20px; margin-right: 8px;"></i> Kembali
    </a>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        <!-- Card Detail Materi -->
        <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #f1f5f9;">
                <div>
                    <h2 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin: 0 0 4px 0;">{{ $jurnal->mapel->nama_mapel ?? 'Mapel Tidak Ditemukan' }}</h2>
                    <p style="color: #64748b; margin: 0;">{{ $jurnal->tanggal->format('l, d F Y') }}</p>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 1rem; font-weight: 700; color: #374151;">{{ $jurnal->kelas->nama_kelas }}</div>
                    <div style="color: #64748b; font-size: 0.9rem;">Oleh: {{ $jurnal->user->name }}</div>
                </div>
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Materi / Pembahasan</label>
                <div style="background: #f8fafc; padding: 20px; border-radius: 12px; color: #334155; line-height: 1.6; font-size: 1.1rem; border-left: 4px solid #3b82f6;">
                    {!! nl2br(e($jurnal->materi)) !!}
                </div>
            </div>

            @if($jurnal->catatan)
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Catatan Tambahan</label>
                <div style="background: #fffbeb; padding: 16px; border-radius: 12px; color: #92400e; border: 1px solid #fde68a;">
                    {{ $jurnal->catatan }}
                </div>
            </div>
            @endif
        </div>

        <!-- Card Absensi -->
        <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); height: fit-content;">
            <h3 style="font-size: 1.1rem; font-weight: 800; color: #1f2937; margin: 0 0 16px 0; display: flex; align-items: center;">
                <i data-feather="users" style="width: 20px; height: 20px; margin-right: 8px; color: #64748b;"></i>
                Kehadiran Santri
            </h3>

            @php
                $absents = $jurnal->details->where('status', '!=', 'hadir');
                $attendance_rate = 100;
                $total_students = \App\Models\Santri::where('kelas_id', $jurnal->kelas_id)->count();
                if($total_students > 0) {
                     $attendance_rate = round((($total_students - $absents->count()) / $total_students) * 100);
                }
            @endphp

            <!-- Progress Bar -->
            <div style="margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.9rem; font-weight: 600;">
                    <span style="color: #64748b;">Tingkat Kehadiran</span>
                    <span style="color: #10b981;">{{ $attendance_rate }}%</span>
                </div>
                <div style="width: 100%; background: #e2e8f0; height: 8px; border-radius: 99px; overflow: hidden;">
                    <div style="width: {{ $attendance_rate }}%; background: #10b981; height: 100%; border-radius: 99px;"></div>
                </div>
            </div>

            @if($absents->count() > 0)
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @foreach($absents as $absen)
                    <li style="display: flex; align-items: flex-start; padding: 12px 0; border-bottom: 1px solid #f1f5f9; gap: 12px;">
                        <span style="width: 32px; height: 32px; background: #fee2e2; color: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem;">
                           {{ strtoupper(substr($absen->status, 0, 1)) }}
                        </span>
                        <div>
                            <div style="font-weight: 600; color: #1e293b;">{{ $absen->santri->nama }}</div>
                            <div style="font-size: 0.85rem; color: #64748b;">
                                {{ ucfirst($absen->status) }}
                                @if($absen->keterangan)
                                    - {{ $absen->keterangan }}
                                @endif
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            @else
                <div style="text-align: center; padding: 32px 0; color: #10b981;">
                    <i data-feather="check-circle" style="width: 48px; height: 48px; margin-bottom: 8px;"></i>
                    <p style="font-weight: 600; margin: 0;">Nihil</p>
                    <p style="font-size: 0.9rem; margin: 0;">Semua santri hadir</p>
                </div>
            @endif
        </div>
    </div>
@endsection
