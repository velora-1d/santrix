@extends('layouts.app')

@section('title', 'Tahun Ajaran')
@section('page-title', 'Pengaturan Tahun Ajaran')

@section('sidebar-menu')
    @include('admin.partials.sidebar-menu')
@endsection

@section('content')
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 16px 24px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); display: flex; align-items: center; gap: 12px;">
            <i data-feather="check-circle" style="width: 24px; height: 24px;"></i>
            <span style="font-weight: 600;">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 16px 24px; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); display: flex; align-items: center; gap: 12px;">
            <i data-feather="alert-circle" style="width: 24px; height: 24px;"></i>
            <span style="font-weight: 600;">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Page Header (Gradient Banner) -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px; box-shadow: 0 20px 60px rgba(102, 126, 234, 0.4); position: relative; overflow: hidden;">
        <div style="position: absolute; top: -40px; right: -40px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="display: flex; align-items: center; justify-content: space-between; position: relative; z-index: 1; flex-wrap: wrap; gap: 20px;">
            <div style="display: flex; align-items: center; gap: 20px; color: white;">
                <div style="background: rgba(255,255,255,0.2); width: 64px; height: 64px; border-radius: 16px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px);">
                    <i data-feather="calendar" style="width: 32px; height: 32px; color: white;"></i>
                </div>
                <div>
                    <h1 style="font-size: 1.8rem; font-weight: 900; margin: 0 0 4px 0;">Manajemen Tahun Ajaran</h1>
                    <p style="opacity: 0.9; font-size: 1rem; margin: 0;">Atur periode akademik sekolah</p>
                </div>
            </div>
            <button type="button" data-modal-open="createModal" style="background: white; color: #667eea; border: none; padding: 12px 24px; border-radius: 12px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 8px; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                <i data-feather="plus" style="width: 18px; height: 18px;"></i>
                Tambah Tahun Ajaran
            </button>
        </div>
    </div>

    <!-- Main Content Card -->
    <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); margin-bottom: 24px;">
        <h3 style="font-size: 1.5rem; font-weight: 800; color: #1f2937; margin: 0 0 24px 0;">Daftar Tahun Ajaran</h3>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 16px; text-align: left; font-weight: 700; color: #1e293b;">Tahun Ajaran</th>
                        <th style="padding: 16px; text-align: left; font-weight: 700; color: #1e293b;">Semester</th>
                        <th style="padding: 16px; text-align: left; font-weight: 700; color: #1e293b;">Periode</th>
                        <th style="padding: 16px; text-align: left; font-weight: 700; color: #1e293b;">Status</th>
                        <th style="padding: 16px; text-align: center; font-weight: 700; color: #1e293b;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tahunAjaran as $tahun)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                        <td style="padding: 16px; font-weight: 600; color: #1f2937;">{{ $tahun->nama }}</td>
                        <td style="padding: 16px; color: #4b5563;">{{ $tahun->semester }}</td>
                        <td style="padding: 16px; color: #6b7280;">
                            {{ $tahun->tanggal_mulai ? date('d M Y', strtotime($tahun->tanggal_mulai)) : '-' }} - 
                            {{ $tahun->tanggal_selesai ? date('d M Y', strtotime($tahun->tanggal_selesai)) : '-' }}
                        </td>
                        <td style="padding: 16px;">
                            @if($tahun->is_active)
                                <span style="background: #d1fae5; color: #065f46; padding: 6px 12px; border-radius: 6px; font-size: 0.875rem; font-weight: 600;">
                                    AKTIF
                                </span>
                            @else
                                <span style="background: #f3f4f6; color: #6b7280; padding: 6px 12px; border-radius: 6px; font-size: 0.875rem; font-weight: 600;">
                                    Tidak Aktif
                                </span>
                            @endif
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            <button data-modal-open="editModal{{ $tahun->id }}" style="background: #3b82f6; color: white; padding: 8px 12px; border: none; border-radius: 8px; cursor: pointer; margin-right: 6px; transition: background 0.2s;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
                                <i data-feather="edit-2" style="width: 16px; height: 16px; vertical-align: middle;"></i>
                            </button>
                            
                            @if(!$tahun->is_active)
                            <form action="{{ route('admin.pengaturan.tahun-ajaran.destroy', $tahun->id) }}" method="POST" style="display: inline;" onsubmit="return confirmDelete(event)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: #ef4444; color: white; padding: 8px 12px; border: none; border-radius: 8px; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='#ef4444'">
                                    <i data-feather="trash-2" style="width: 16px; height: 16px; vertical-align: middle;"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding: 32px; text-align: center; color: #64748b;">Belum ada data tahun ajaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Modals -->
    @foreach($tahunAjaran as $tahun)
    <div id="editModal{{ $tahun->id }}" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 20px; padding: 32px; max-width: 500px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <form action="{{ route('admin.pengaturan.tahun-ajaran.update_explicit', $tahun->id) }}" method="POST">
                @csrf
                {{-- Method spoofing optional if we support direct POST, but keep for compatibility if controller expects --}}
                {{-- We can remove @method('PUT') since we are hitting a POST route --}}
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <h5 style="font-size: 1.5rem; font-weight: 800; color: #1f2937; margin: 0;">Edit Tahun Ajaran</h5>
                    <button type="button" data-modal-close style="background: none; border: none; cursor: pointer; color: #9ca3af;">
                        <i data-feather="x" style="width: 24px; height: 24px;"></i>
                    </button>
                </div>
                
                <div style="display: grid; gap: 16px;">
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Nama Tahun Ajaran</label>
                        <input type="text" name="nama" class="form-control" value="{{ $tahun->nama }}" required placeholder="Contoh: 2024/2025" style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Semester</label>
                        <select name="semester" class="form-select" required style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem;">
                            <option value="Ganjil" {{ $tahun->semester == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="Genap" {{ $tahun->semester == 'Genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control" value="{{ $tahun->tanggal_mulai ? $tahun->tanggal_mulai->format('Y-m-d') : '' }}" required style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="form-control" value="{{ $tahun->tanggal_selesai ? $tahun->tanggal_selesai->format('Y-m-d') : '' }}" required style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Status Aktif</label>
                        <select name="is_active" class="form-select" style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem;">
                            <option value="0" {{ !$tahun->is_active ? 'selected' : '' }}>Tidak Aktif</option>
                            <option value="1" {{ $tahun->is_active ? 'selected' : '' }}>AKTIF (Set sebagai tahun berjalan)</option>
                        </select>
                        <small style="color: #6b7280; font-size: 0.875rem; display: block; margin-top: 4px;">Mengaktifkan tahun ini akan menonaktifkan tahun ajaran lainnya.</small>
                    </div>
                </div>
                
                <div style="display: flex; gap: 12px; margin-top: 24px;">
                    <button type="submit" style="flex: 1; background: #3b82f6; color: white; padding: 12px; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">Simpan Perubahan</button>
                    <button type="button" data-modal-close style="flex: 1; background: #e5e7eb; color: #374151; padding: 12px; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#d1d5db'" onmouseout="this.style.background='#e5e7eb'">Batal</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    <!-- Create Modal -->
    <div id="createModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 20px; padding: 32px; max-width: 500px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <form action="{{ route('admin.pengaturan.tahun-ajaran.store') }}" method="POST">
                @csrf
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <h5 style="font-size: 1.5rem; font-weight: 800; color: #1f2937; margin: 0;">Tambah Tahun Ajaran Baru</h5>
                    <button type="button" data-modal-close style="background: none; border: none; cursor: pointer; color: #9ca3af;">
                        <i data-feather="x" style="width: 24px; height: 24px;"></i>
                    </button>
                </div>
                
                <div style="display: grid; gap: 16px;">
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Nama Tahun Ajaran</label>
                        <input type="text" name="nama" class="form-control" required placeholder="Contoh: 2025/2026" style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Semester</label>
                        <select name="semester" class="form-select" required style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem;">
                            <option value="Ganjil" selected>Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control" required style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="form-control" required style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Status Aktif</label>
                        <select name="is_active" class="form-select" style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem;">
                            <option value="0">Tidak Aktif</option>
                            <option value="1">AKTIF (Set sebagai tahun berjalan)</option>
                        </select>
                        <small style="color: #6b7280; font-size: 0.875rem; display: block; margin-top: 4px;">Mengaktifkan tahun ini akan menonaktifkan tahun ajaran lainnya.</small>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; margin-top: 24px;">
                    <button type="submit" style="flex: 1; background: #3b82f6; color: white; padding: 12px; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">Simpan</button>
                    <button type="button" data-modal-close style="flex: 1; background: #e5e7eb; color: #374151; padding: 12px; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#d1d5db'" onmouseout="this.style.background='#e5e7eb'">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Re-initialize Feather Icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // Manually Bind Modal Logic (Backup if modal.js fails)
            const openButtons = document.querySelectorAll('[data-modal-open]');
            const closeButtons = document.querySelectorAll('[data-modal-close]');

            function openModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                }
            }

            function closeModal(modal) {
                if (modal) {
                    modal.style.display = 'none';
                    document.body.style.overflow = '';
                }
            }

            openButtons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault(); // Prevent default link/button behavior
                    const modalId = btn.getAttribute('data-modal-open');
                    openModal(modalId);
                });
            });

            closeButtons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const modal = btn.closest('.modal');
                    closeModal(modal);
                });
            });

            // Close when clicking outside
            window.addEventListener('click', (e) => {
                if (e.target.classList.contains('modal')) {
                    closeModal(e.target);
                }
            });
        });
    </script>
@endsection
