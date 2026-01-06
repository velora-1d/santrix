@extends('layouts.app')

@section('title', 'Pengaturan Tahun Ajaran')

@section('sidebar-menu')
    @include('admin.partials.sidebar-menu')
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Pengaturan Tahun Ajaran</h1>
            <button type="button" class="btn btn-primary" data-modal-open="createModal">
                <i data-feather="plus"></i> Tambah Tahun Ajaran
            </button>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead class="table-light">
                            <tr>
                                <th>Tahun Ajaran</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tahunAjaran as $tahun)
                            <tr class="{{ $tahun->is_active ? 'table-success' : '' }}">
                                <td class="fw-bold">{{ $tahun->nama }}</td>
                                <td>
                                    {{ $tahun->tanggal_mulai ? date('d M Y', strtotime($tahun->tanggal_mulai)) : '-' }} - 
                                    {{ $tahun->tanggal_selesai ? date('d M Y', strtotime($tahun->tanggal_selesai)) : '-' }}
                                </td>
                                <td>
                                    @if($tahun->is_active)
                                        <span class="badge bg-success">AKTIF</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info me-1" data-modal-open="editModal{{ $tahun->id }}">
                                        <i data-feather="edit"></i>
                                    </button>
                                    @if(!$tahun->is_active)
                                    <form action="{{ route('admin.pengaturan.tahun-ajaran.destroy', $tahun->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i data-feather="trash-2"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>

                            <!-- Edit Modal moved to bottom -->
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data tahun ajaran.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modals (Moved Outside Table) -->
@foreach($tahunAjaran as $tahun)
<div class="modal fade" id="editModal{{ $tahun->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.pengaturan.tahun-ajaran.update', $tahun->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Tahun Ajaran</h5>
                    <button type="button" class="btn-close" data-modal-close></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Tahun Ajaran</label>
                        <input type="text" name="nama" class="form-control" value="{{ $tahun->nama }}" required placeholder="Contoh: 2024/2025">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control" value="{{ $tahun->tanggal_mulai ? $tahun->tanggal_mulai->format('Y-m-d') : '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="form-control" value="{{ $tahun->tanggal_selesai ? $tahun->tanggal_selesai->format('Y-m-d') : '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Aktif</label>
                        <select name="is_active" class="form-select">
                            <option value="0" {{ !$tahun->is_active ? 'selected' : '' }}>Tidak Aktif</option>
                            <option value="1" {{ $tahun->is_active ? 'selected' : '' }}>AKTIF (Set sebagai tahun berjalan)</option>
                        </select>
                        <small class="text-muted">Mengaktifkan tahun ini akan menonaktifkan tahun ajaran lainnya.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.pengaturan.tahun-ajaran.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tahun Ajaran Baru</h5>
                    <button type="button" class="btn-close" data-modal-close></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Tahun Ajaran</label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: 2025/2026" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="0">Tidak Aktif (Simpan Dulu)</option>
                            <option value="1">Langsung Aktifkan</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-modal-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
