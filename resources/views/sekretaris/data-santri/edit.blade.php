@extends('layouts.app')

@section('title', 'Edit Santri')
@section('page-title', 'Edit Data Santri')

@section('sidebar-menu')
    <li class="sidebar-menu-item">
        <a href="{{ route('sekretaris.dashboard') }}" class="sidebar-menu-link">
            <i data-feather="home" class="sidebar-menu-icon"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="{{ route('sekretaris.data-santri') }}" class="sidebar-menu-link active">
            <i data-feather="users" class="sidebar-menu-icon"></i>
            <span>Data Santri</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="{{ route('sekretaris.mutasi-santri') }}" class="sidebar-menu-link">
            <i data-feather="repeat" class="sidebar-menu-icon"></i>
            <span>Mutasi Santri</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="{{ route('sekretaris.laporan') }}" class="sidebar-menu-link">
            <i data-feather="file-text" class="sidebar-menu-icon"></i>
            <span>Laporan</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-menu-link" style="width: 100%; background: none; border: none; cursor: pointer; text-align: left;">
                <i data-feather="log-out" class="sidebar-menu-icon"></i>
                <span>Logout</span>
            </button>
        </form>
    </li>
@endsection

@section('content')
    <!-- Aesthetic Header with Gradient -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 16px; padding: 24px 32px; margin-bottom: 32px; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3); position: relative; overflow: hidden;">
        <div style="position: absolute; top: -30px; right: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -40px; left: 40%; width: 80px; height: 80px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
        <div style="display: flex; align-items: center; gap: 16px; position: relative; z-index: 1;">
            <div style="background: rgba(255,255,255,0.2); width: 56px; height: 56px; border-radius: 12px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px);">
                <i data-feather="edit" style="width: 28px; height: 28px; color: white;"></i>
            </div>
            <div>
                <h2 style="font-size: 1.5rem; font-weight: 700; color: white; margin: 0 0 4px 0;">Edit Data Santri</h2>
                <p style="color: rgba(255,255,255,0.9); font-size: 0.875rem; margin: 0;">{{ $santri->nama_santri }} ({{ $santri->nis }})</p>
            </div>
        </div>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('sekretaris.data-santri.update', $santri->id) }}">
            @csrf
            @method('PUT')
            
            <h3 style="font-size: var(--font-size-lg); font-weight: var(--font-weight-semibold); margin-bottom: var(--spacing-lg); color: var(--color-gray-900);">
                Informasi Santri
            </h3>
            
            <div class="grid grid-cols-2">
                <div class="form-group">
                    <label class="form-label">NIS *</label>
                    <input type="text" name="nis" class="form-input" value="{{ old('nis', $santri->nis) }}" required>
                    @error('nis')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Nama Santri *</label>
                    <input type="text" name="nama_santri" class="form-input" value="{{ old('nama_santri', $santri->nama_santri) }}" required>
                    @error('nama_santri')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Gender *</label>
                    <select name="gender" class="form-select" required>
                        <option value="">Pilih Gender</option>
                        <option value="putra" {{ old('gender', $santri->gender) == 'putra' ? 'selected' : '' }}>Putra</option>
                        <option value="putri" {{ old('gender', $santri->gender) == 'putri' ? 'selected' : '' }}>Putri</option>
                    </select>
                    @error('gender')<span class="form-error">{{ $message }}</span>@enderror
                </div>
            </div>
            
            <h3 style="font-size: var(--font-size-lg); font-weight: var(--font-weight-semibold); margin: var(--spacing-xl) 0 var(--spacing-lg); color: var(--color-gray-900);">
                Alamat Lengkap
            </h3>
            
            <div class="grid grid-cols-2">
                <div class="form-group">
                    <label class="form-label">Negara *</label>
                    <input type="text" name="negara" class="form-input" value="{{ old('negara', $santri->negara) }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Provinsi *</label>
                    <input type="text" name="provinsi" class="form-input" value="{{ old('provinsi', $santri->provinsi) }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Kota/Kabupaten *</label>
                    <input type="text" name="kota_kabupaten" class="form-input" value="{{ old('kota_kabupaten', $santri->kota_kabupaten) }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Kecamatan *</label>
                    <input type="text" name="kecamatan" class="form-input" value="{{ old('kecamatan', $santri->kecamatan) }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Desa/Kampung *</label>
                    <input type="text" name="desa_kampung" class="form-input" value="{{ old('desa_kampung', $santri->desa_kampung) }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">RT/RW *</label>
                    <input type="text" name="rt_rw" class="form-input" value="{{ old('rt_rw', $santri->rt_rw) }}" required>
                </div>
            </div>
            
            <h3 style="font-size: var(--font-size-lg); font-weight: var(--font-weight-semibold); margin: var(--spacing-xl) 0 var(--spacing-lg); color: var(--color-gray-900);">
                Orang Tua / Wali
            </h3>
            
            <div class="grid grid-cols-2">
                <div class="form-group">
                    <label class="form-label">Nama Orang Tua/Wali *</label>
                    <input type="text" name="nama_ortu_wali" class="form-input" value="{{ old('nama_ortu_wali', $santri->nama_ortu_wali) }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">No. HP Orang Tua/Wali *</label>
                    <input type="text" name="no_hp_ortu_wali" class="form-input" value="{{ old('no_hp_ortu_wali', $santri->no_hp_ortu_wali) }}" required>
                </div>
            </div>
            
            <h3 style="font-size: var(--font-size-lg); font-weight: var(--font-weight-semibold); margin: var(--spacing-xl) 0 var(--spacing-lg); color: var(--color-gray-900);">
                Penempatan
            </h3>
            
            <div class="grid grid-cols-3">
                <div class="form-group">
                    <label class="form-label">Kelas *</label>
                    <select name="kelas_id" class="form-select" required>
                        <option value="">Pilih Kelas</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ old('kelas_id', $santri->kelas_id) == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Asrama *</label>
                    <select name="asrama_id" id="asrama_id" class="form-select" required>
                        <option value="">Pilih Asrama</option>
                        @foreach($asramaList as $asrama)
                            <option value="{{ $asrama->id }}" {{ old('asrama_id', $santri->asrama_id) == $asrama->id ? 'selected' : '' }}>
                                {{ $asrama->nama_asrama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Kobong *</label>
                    <select name="kobong_id" id="kobong_id" class="form-select" required>
                        @foreach($kobongList as $kobong)
                            <option value="{{ $kobong->id }}" {{ old('kobong_id', $santri->kobong_id) == $kobong->id ? 'selected' : '' }}>
                                Kobong {{ $kobong->nomor_kobong }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div style="display: flex; gap: var(--spacing-md); margin-top: var(--spacing-xl);">
                <button type="submit" class="btn btn-primary">
                    <i data-feather="save" style="width: 16px; height: 16px;"></i>
                    Update
                </button>
                <a href="{{ route('sekretaris.data-santri') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('asrama_id').addEventListener('change', function() {
    const asramaId = this.value;
    const kobongSelect = document.getElementById('kobong_id');
    
    if (!asramaId) {
        kobongSelect.innerHTML = '<option value="">Pilih Asrama Dulu</option>';
        return;
    }
    
    fetch(`/sekretaris/api/kobong/${asramaId}`)
        .then(response => response.json())
        .then(data => {
            kobongSelect.innerHTML = '<option value="">Pilih Kobong</option>';
            data.forEach(kobong => {
                kobongSelect.innerHTML += `<option value="${kobong.id}">Kobong ${kobong.nomor_kobong}</option>`;
            });
        })
        .catch(error => console.error('Error:', error));
});
</script>
@endpush
