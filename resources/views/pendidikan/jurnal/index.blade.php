@extends('layouts.app')

@section('title', 'Jurnal KBM Digital')
@section('page-title', 'Jurnal KBM')

@section('sidebar-menu')
    @include('pendidikan.partials.sidebar-menu')
@endsection

@section('content')
    <!-- Filter Section -->
    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); margin-bottom: 24px;">
        <form method="GET" action="{{ route('pendidikan.jurnal') }}" style="display: flex; gap: 16px; align-items: flex-end;">
            <div style="flex: 1;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Pilih Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
            </div>
            <div style="flex: 1;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Kelas</label>
                <select name="kelas_id" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                    <option value="">Semua Kelas</option>
                    @foreach($kelas_list as $kelas)
                        <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i data-feather="filter" style="width: 16px; height: 16px; margin-right: 8px;"></i> Filter
            </button>
            <a href="{{ route('pendidikan.jurnal') }}" style="background: #f1f5f9; color: #475569; padding: 10px 24px; border: none; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center;">
                Reset
            </a>
        </form>
    </div>

    <!-- Jurnal Table -->
    <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #1f2937; margin: 0;">Riwayat Kegiatan Belajar Mengajar</h3>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 16px; text-align: left; font-weight: 700; color: #1e293b;">Tanggal</th>
                        <th style="padding: 16px; text-align: left; font-weight: 700; color: #1e293b;">Kelas</th>
                        <th style="padding: 16px; text-align: left; font-weight: 700; color: #1e293b;">Mapel / Kitab</th>
                        <th style="padding: 16px; text-align: left; font-weight: 700; color: #1e293b;">Ustadz</th>
                        <th style="padding: 16px; text-align: left; font-weight: 700; color: #1e293b;">Materi</th>
                        <th style="padding: 16px; text-align: center; font-weight: 700; color: #1e293b;">Status</th>
                        <th style="padding: 16px; text-align: center; font-weight: 700; color: #1e293b;">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jurnals as $jurnal)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                        <td style="padding: 16px; font-weight: 600; color: #1f2937;">{{ $jurnal->tanggal->format('d/m/Y') }}</td>
                        <td style="padding: 16px;">
                            <span style="background: #e0e7ff; color: #4338ca; padding: 4px 8px; border-radius: 6px; font-weight: 700;">
                                {{ $jurnal->kelas->nama_kelas }}
                            </span>
                        </td>
                        <td style="padding: 16px; color: #374151; font-weight: 500;">
                            {{ $jurnal->mapel->nama_mapel ?? '-' }}
                        </td>
                        <td style="padding: 16px; color: #64748b;">
                            {{ $jurnal->user->name }}
                        </td>
                        <td style="padding: 16px; color: #334155; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $jurnal->materi }}
                        </td>
                        <td style="padding: 16px; text-align: center;">
                             <span style="background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 99px; font-size: 0.8rem; font-weight: 600;">
                                Selesai
                             </span>
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            <a href="{{ route('pendidikan.jurnal.show', $jurnal->id) }}" style="background: white; border: 1px solid #cbd5e1; color: #334155; width: 32px; height: 32px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.borderColor='#667eea'; this.style.color='#667eea'" onmouseout="this.style.borderColor='#cbd5e1'; this.style.color='#334155'">
                                <i data-feather="eye" style="width: 16px; height: 16px;"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 32px; text-align: center; color: #94a3b8;">
                            <div style="margin-bottom: 12px;">
                                <i data-feather="book-open" style="width: 48px; height: 48px; opacity: 0.5;"></i>
                            </div>
                            Belum ada jurnal yang masuk
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 24px;">
            {{ $jurnals->links() }}
        </div>
    </div>
@endsection
