@extends('layouts.app')

@section('title', 'Detail Santri')
@section('page-title', 'Data Santri')

@section('sidebar-menu')
    @include('sekretaris.partials.sidebar-menu')
@endsection

@section('content')
<div style="padding: 24px; max-width: 1200px; margin: 0 auto;">

    <!-- Back Button -->
    <div style="margin-bottom: 24px;">
        <a href="{{ route('sekretaris.data-santri') }}" style="display: inline-flex; align-items: center; gap: 8px; color: #6366f1; text-decoration: none; font-weight: 600;">
            <i data-feather="arrow-left" style="width: 20px; height: 20px;"></i>
            Kembali ke Data Santri
        </a>
    </div>

    <!-- Header Card -->
    <div style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -30px; right: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        
        <div style="position: relative; z-index: 1; display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
                    <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 800;">
                        {{ strtoupper(substr($santri->nama_santri, 0, 1)) }}
                    </div>
                    <div>
                        <h1 style="font-size: 1.75rem; font-weight: 800; margin: 0;">{{ $santri->nama_santri }}</h1>
                        <p style="font-size: 1rem; opacity: 0.9; margin: 4px 0 0 0;">NIS: {{ $santri->nis }}</p>
                    </div>
                </div>
                <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                    <span style="background: rgba(255,255,255,0.2); padding: 6px 16px; border-radius: 20px; font-size: 0.875rem;">
                        {{ ucfirst($santri->gender) }}
                    </span>
                    <span style="background: rgba(255,255,255,0.2); padding: 6px 16px; border-radius: 20px; font-size: 0.875rem;">
                        {{ $santri->kelas->nama_kelas ?? 'Tanpa Kelas' }}
                    </span>
                    <span style="background: {{ $santri->is_active ? 'rgba(52,211,153,0.3)' : 'rgba(239,68,68,0.3)' }}; padding: 6px 16px; border-radius: 20px; font-size: 0.875rem;">
                        {{ $santri->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>
            </div>
            <a href="{{ route('sekretaris.data-santri.edit', $santri->id) }}" style="background: white; color: #6366f1; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <i data-feather="edit" style="width: 18px; height: 18px;"></i>
                Edit
            </a>
        </div>
    </div>

    <!-- Info Grid -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px;">
        
        <!-- Data Pribadi -->
        <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <h3 style="font-size: 1rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 20px 0; display: flex; align-items: center; gap: 8px;">
                <i data-feather="user" style="width: 18px; height: 18px;"></i>
                Data Pribadi
            </h3>
            
            <div style="display: grid; gap: 16px;">
                <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                    <span style="color: #64748b;">NIS</span>
                    <span style="color: #1e293b; font-weight: 600;">{{ $santri->nis }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                    <span style="color: #64748b;">Nama Lengkap</span>
                    <span style="color: #1e293b; font-weight: 600;">{{ $santri->nama_santri }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                    <span style="color: #64748b;">Jenis Kelamin</span>
                    <span style="color: #1e293b; font-weight: 600;">{{ ucfirst($santri->gender) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #64748b;">Status</span>
                    <span style="padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; {{ $santri->is_active ? 'background: #d1fae5; color: #047857;' : 'background: #fee2e2; color: #b91c1c;' }}">
                        {{ $santri->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Data Alamat -->
        <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <h3 style="font-size: 1rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 20px 0; display: flex; align-items: center; gap: 8px;">
                <i data-feather="map-pin" style="width: 18px; height: 18px;"></i>
                Alamat
            </h3>
            
            <div style="display: grid; gap: 16px;">
                <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                    <span style="color: #64748b;">Negara</span>
                    <span style="color: #1e293b; font-weight: 600;">{{ $santri->negara }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                    <span style="color: #64748b;">Provinsi</span>
                    <span style="color: #1e293b; font-weight: 600;">{{ $santri->provinsi }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                    <span style="color: #64748b;">Kota/Kabupaten</span>
                    <span style="color: #1e293b; font-weight: 600;">{{ $santri->kota_kabupaten }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                    <span style="color: #64748b;">Kecamatan</span>
                    <span style="color: #1e293b; font-weight: 600;">{{ $santri->kecamatan }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                    <span style="color: #64748b;">Desa/Kampung</span>
                    <span style="color: #1e293b; font-weight: 600;">{{ $santri->desa_kampung }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #64748b;">RT/RW</span>
                    <span style="color: #1e293b; font-weight: 600;">{{ $santri->rt_rw }}</span>
                </div>
            </div>
        </div>

        <!-- Data Wali -->
        <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <h3 style="font-size: 1rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 20px 0; display: flex; align-items: center; gap: 8px;">
                <i data-feather="users" style="width: 18px; height: 18px;"></i>
                Data Orang Tua / Wali
            </h3>
            
            <div style="display: grid; gap: 16px;">
                <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                    <span style="color: #64748b;">Nama Ortu/Wali</span>
                    <span style="color: #1e293b; font-weight: 600;">{{ $santri->nama_ortu_wali }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #64748b;">No HP Ortu/Wali</span>
                    <span style="color: #1e293b; font-weight: 600;">{{ $santri->no_hp_ortu_wali }}</span>
                </div>
            </div>
        </div>

        <!-- Data Pesantren -->
        <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <h3 style="font-size: 1rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 20px 0; display: flex; align-items: center; gap: 8px;">
                <i data-feather="home" style="width: 18px; height: 18px;"></i>
                Data Kepesantrenan
            </h3>
            
            <div style="display: grid; gap: 16px;">
                <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                    <span style="color: #64748b;">Kelas</span>
                    <span style="color: #1e293b; font-weight: 600;">{{ $santri->kelas->nama_kelas ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                    <span style="color: #64748b;">Asrama</span>
                    <span style="color: #1e293b; font-weight: 600;">{{ $santri->asrama->nama_asrama ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                    <span style="color: #64748b;">Kobong</span>
                    <span style="color: #1e293b; font-weight: 600;">{{ $santri->kobong->nama_kobong ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #64748b;">Tanggal Masuk</span>
                    <span style="color: #1e293b; font-weight: 600;">{{ $santri->tanggal_masuk ? $santri->tanggal_masuk->format('d M Y') : '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Mutasi -->
    <div style="background: white; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); overflow: hidden;">
        <div style="padding: 24px; border-bottom: 1px solid #f1f5f9;">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 8px;">
                <i data-feather="clock" style="width: 20px; height: 20px; color: #6366f1;"></i>
                Riwayat Mutasi
            </h3>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th style="padding: 14px 24px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Tanggal</th>
                        <th style="padding: 14px 24px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Jenis Mutasi</th>
                        <th style="padding: 14px 24px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mutasiHistory as $mutasi)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 16px 24px; color: #1e293b;">
                            {{ \Carbon\Carbon::parse($mutasi->tanggal_mutasi)->format('d M Y') }}
                        </td>
                        <td style="padding: 16px 24px;">
                            @php
                                $badgeColors = [
                                    'masuk' => 'background: #d1fae5; color: #047857;',
                                    'keluar' => 'background: #fee2e2; color: #b91c1c;',
                                    'pindah_kelas' => 'background: #fef3c7; color: #92400e;',
                                    'pindah_asrama' => 'background: #e0e7ff; color: #4338ca;',
                                ];
                            @endphp
                            <span style="padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; {{ $badgeColors[$mutasi->jenis_mutasi] ?? 'background: #f1f5f9; color: #64748b;' }}">
                                {{ ucfirst(str_replace('_', ' ', $mutasi->jenis_mutasi)) }}
                            </span>
                        </td>
                        <td style="padding: 16px 24px; color: #64748b;">
                            {{ $mutasi->keterangan ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="padding: 40px; text-align: center; color: #94a3b8;">
                            Belum ada riwayat mutasi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
