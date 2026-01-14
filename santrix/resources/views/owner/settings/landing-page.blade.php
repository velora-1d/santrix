@extends('layouts.owner')

@section('title', 'Pengaturan Landing Page')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Pengaturan Landing Page</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Pengaturan Landing Page</li>
    </ol>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-line me-2"></i>
                    Statistik Landing Page
                </div>
                <div class="card-body">
                    <form action="{{ route('owner.settings.landing.update') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="pesantren" class="form-label">Jumlah Pesantren</label>
                            <input type="number" class="form-control @error('pesantren') is-invalid @enderror" 
                                   id="pesantren" name="pesantren" value="{{ old('pesantren', $stats['pesantren']) }}" 
                                   min="0" required>
                            @error('pesantren')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Angka yang ditampilkan di landing page untuk jumlah pesantren yang menggunakan Santrix</div>
                        </div>

                        <div class="mb-3">
                            <label for="santri" class="form-label">Jumlah Santri</label>
                            <input type="number" class="form-control @error('santri') is-invalid @enderror" 
                                   id="santri" name="santri" value="{{ old('santri', $stats['santri']) }}" 
                                   min="0" required>
                            @error('santri')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Angka yang ditampilkan di landing page untuk total santri yang terdaftar</div>
                        </div>

                        <div class="mb-3">
                            <label for="users" class="form-label">Jumlah Pengguna</label>
                            <input type="number" class="form-control @error('users') is-invalid @enderror" 
                                   id="users" name="users" value="{{ old('users', $stats['users']) }}" 
                                   min="0" required>
                            @error('users')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Angka yang ditampilkan di landing page untuk total pengguna aktif</div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('owner.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-eye me-2"></i>
                    Preview
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Tampilan di landing page:</p>
                    
                    <div class="bg-dark text-white p-4 rounded">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="fs-4 fw-bold" id="preview-pesantren">{{ number_format($stats['pesantren']) }}+</div>
                                <div class="small text-muted">Pesantren</div>
                            </div>
                            <div class="col-4">
                                <div class="fs-4 fw-bold" id="preview-santri">{{ number_format($stats['santri']) }}+</div>
                                <div class="small text-muted">Santri</div>
                            </div>
                            <div class="col-4">
                                <div class="fs-4 fw-bold" id="preview-users">{{ number_format($stats['users']) }}+</div>
                                <div class="small text-muted">Pengguna</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="https://santrix.my.id" target="_blank" class="btn btn-sm btn-outline-primary w-100">
                            <i class="fas fa-external-link-alt me-2"></i>Lihat Landing Page
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2"></i>
                    Informasi
                </div>
                <div class="card-body">
                    <p class="small mb-2"><strong>Tips:</strong></p>
                    <ul class="small mb-0">
                        <li>Gunakan angka yang realistis dan impressive</li>
                        <li>Update secara berkala untuk menunjukkan pertumbuhan</li>
                        <li>Angka akan di-cache selama 1 jam untuk performa</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Live preview update
document.getElementById('pesantren').addEventListener('input', function(e) {
    document.getElementById('preview-pesantren').textContent = parseInt(e.target.value || 0).toLocaleString() + '+';
});
document.getElementById('santri').addEventListener('input', function(e) {
    document.getElementById('preview-santri').textContent = parseInt(e.target.value || 0).toLocaleString() + '+';
});
document.getElementById('users').addEventListener('input', function(e) {
    document.getElementById('preview-users').textContent = parseInt(e.target.value || 0).toLocaleString() + '+';
});
</script>
@endsection
