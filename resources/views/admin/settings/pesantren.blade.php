@extends('layouts.app')

@section('title', 'Pengaturan Pesantren')

@section('sidebar-menu')
    @include('admin.partials.sidebar-menu')
@endsection

@section('content')
<div style="padding: 32px;">
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <h1 style="font-size: 1.875rem; font-weight: 800; color: #1e293b; margin-bottom: 8px;">
            ⚙️ Pengaturan Pesantren
        </h1>
        <p style="color: #64748b; font-size: 0.9375rem;">
            Kelola informasi dan branding pesantren Anda
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

    @if(session('error'))
        <div style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border-left: 4px solid #ef4444; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
            <svg style="width: 20px; height: 20px; color: #dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <span style="color: #b91c1c; font-weight: 600;">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Logo Upload Grid (3 columns) -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px;">
        <!-- Logo Pesantren (Wajib) -->
        <div style="background: white; border-radius: 16px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                <svg style="width: 18px; height: 18px; color: #6366f1;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Logo Pesantren <span style="color: #ef4444;">*</span>
            </h3>
            <div id="logo-preview" style="margin-bottom: 16px; text-align: center; min-height: 120px; display: flex; align-items: center; justify-content: center;">
                <img src="{{ tenant_logo() }}" alt="Logo" style="max-width: 150px; max-height: 150px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
            </div>
            <div id="logo-drop-zone" style="border: 2px dashed #cbd5e1; border-radius: 10px; padding: 24px 12px; text-align: center; cursor: pointer; transition: all 0.3s; background: #f8fafc;">
                <svg style="width: 32px; height: 32px; color: #94a3b8; margin: 0 auto 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <p style="color: #475569; font-weight: 600; font-size: 0.875rem; margin-bottom: 4px;">Upload Logo</p>
                <p style="color: #94a3b8; font-size: 0.75rem;">Max 2MB</p>
                <input type="file" id="logo-input" accept="image/*" style="display: none;">
            </div>
            <div id="logo-progress" style="display: none; margin-top: 12px;">
                <div style="background: #e2e8f0; border-radius: 6px; height: 6px; overflow: hidden;">
                    <div id="logo-progress-bar" style="background: linear-gradient(90deg, #6366f1, #8b5cf6); height: 100%; width: 0%; transition: width 0.3s;"></div>
                </div>
                <p id="logo-status" style="color: #64748b; font-size: 0.75rem; margin-top: 6px; text-align: center;"></p>
            </div>
            @if($pesantren->logo_path)
            <button onclick="deleteLogo()" style="margin-top: 10px; width: 100%; padding: 8px; background: #fee2e2; color: #dc2626; border: none; border-radius: 6px; font-weight: 600; font-size: 0.875rem; cursor: pointer;">
                🗑️ Hapus
            </button>
            @endif
        </div>

        <!-- Logo Pendidikan (Opsional) -->
        <div style="background: white; border-radius: 16px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                <svg style="width: 18px; height: 18px; color: #8b5cf6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Logo Pendidikan <span style="color: #94a3b8; font-size: 0.75rem;">(Opsional)</span>
            </h3>
            <div id="logo-pendidikan-preview" style="margin-bottom: 16px; text-align: center; min-height: 120px; display: flex; align-items: center; justify-content: center;">
                @if($pesantren->logo_pendidikan_path)
                    <img src="{{ asset('storage/' . $pesantren->logo_pendidikan_path) }}" alt="Logo Pendidikan" style="max-width: 150px; max-height: 150px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                @else
                    <p style="color: #cbd5e1; font-style: italic; font-size: 0.875rem;">Tidak ada</p>
                @endif
            </div>
            <div id="logo-pendidikan-drop-zone" style="border: 2px dashed #cbd5e1; border-radius: 10px; padding: 24px 12px; text-align: center; cursor: pointer; transition: all 0.3s; background: #f8fafc;">
                <svg style="width: 32px; height: 32px; color: #94a3b8; margin: 0 auto 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <p style="color: #475569; font-weight: 600; font-size: 0.875rem; margin-bottom: 4px;">Upload Logo</p>
                <p style="color: #94a3b8; font-size: 0.75rem;">Max 2MB</p>
                <input type="file" id="logo-pendidikan-input" accept="image/*" style="display: none;">
            </div>
            <div id="logo-pendidikan-progress" style="display: none; margin-top: 12px;">
                <div style="background: #e2e8f0; border-radius: 6px; height: 6px; overflow: hidden;">
                    <div id="logo-pendidikan-progress-bar" style="background: linear-gradient(90deg, #8b5cf6, #a78bfa); height: 100%; width: 0%; transition: width 0.3s;"></div>
                </div>
                <p id="logo-pendidikan-status" style="color: #64748b; font-size: 0.75rem; margin-top: 6px; text-align: center;"></p>
            </div>
            @if($pesantren->logo_pendidikan_path)
            <button onclick="deleteLogoPendidikan()" style="margin-top: 10px; width: 100%; padding: 8px; background: #fee2e2; color: #dc2626; border: none; border-radius: 6px; font-weight: 600; font-size: 0.875rem; cursor: pointer;">
                🗑️ Hapus
            </button>
            @endif
        </div>

        <!-- Tanda Tangan Pimpinan -->
        <div style="background: white; border-radius: 16px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                <svg style="width: 18px; height: 18px; color: #10b981;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                </svg>
                TTD Pimpinan
            </h3>
            <div id="signature-preview" style="margin-bottom: 16px; text-align: center; min-height: 120px; display: flex; align-items: center; justify-content: center;">
                @if($pesantren->pimpinan_ttd_path)
                    <img src="{{ asset('storage/' . $pesantren->pimpinan_ttd_path) }}" alt="TTD" style="max-width: 150px; max-height: 100px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                @else
                    <p style="color: #cbd5e1; font-style: italic; font-size: 0.875rem;">Tidak ada</p>
                @endif
            </div>
            <div id="signature-drop-zone" style="border: 2px dashed #cbd5e1; border-radius: 10px; padding: 24px 12px; text-align: center; cursor: pointer; transition: all 0.3s; background: #f8fafc;">
                <svg style="width: 32px; height: 32px; color: #94a3b8; margin: 0 auto 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <p style="color: #475569; font-weight: 600; font-size: 0.875rem; margin-bottom: 4px;">Upload TTD</p>
                <p style="color: #94a3b8; font-size: 0.75rem;">Max 1MB</p>
                <input type="file" id="signature-input" accept="image/*" style="display: none;">
            </div>
            <div id="signature-progress" style="display: none; margin-top: 12px;">
                <div style="background: #e2e8f0; border-radius: 6px; height: 6px; overflow: hidden;">
                    <div id="signature-progress-bar" style="background: linear-gradient(90deg, #10b981, #34d399); height: 100%; width: 0%; transition: width 0.3s;"></div>
                </div>
                <p id="signature-status" style="color: #64748b; font-size: 0.75rem; margin-top: 6px; text-align: center;"></p>
            </div>
        </div>
    </div>

    <!-- Information Form -->
    <form action="{{ route('admin.pengaturan.update') }}" method="POST" style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        @csrf
        
        <h2 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <svg style="width: 20px; height: 20px; color: #6366f1;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Informasi Pesantren
        </h2>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Nama Pesantren *</label>
                <input type="text" name="nama" value="{{ old('nama', $pesantren->nama) }}" required style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem; transition: all 0.3s;" onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e5e7eb'">
            </div>

            <div>
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Kota</label>
                <input type="text" name="kota" value="{{ old('kota', $pesantren->kota) }}" style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem; transition: all 0.3s;" onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e5e7eb'">
            </div>

            <div style="grid-column: 1 / -1;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Alamat Lengkap</label>
                <textarea name="alamat" rows="3" style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem; transition: all 0.3s; resize: vertical;" onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e5e7eb'">{{ old('alamat', $pesantren->alamat) }}</textarea>
            </div>

            <div>
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Telepon</label>
                <input type="text" name="telepon" value="{{ old('telepon', $pesantren->telepon) }}" style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem; transition: all 0.3s;" onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e5e7eb'">
            </div>

            <div>
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Nama Pimpinan</label>
                <input type="text" name="pimpinan_nama" value="{{ old('pimpinan_nama', $pesantren->pimpinan_nama) }}" style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem; transition: all 0.3s;" onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e5e7eb'">
            </div>

            <div style="grid-column: 1 / -1;">
                <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Jabatan Pimpinan</label>
                <input type="text" name="pimpinan_jabatan" value="{{ old('pimpinan_jabatan', $pesantren->pimpinan_jabatan ?? 'Pengasuh') }}" style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem; transition: all 0.3s;" onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e5e7eb'">
            </div>
        </div>

        <button type="submit" style="margin-top: 24px; padding: 14px 32px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; border: none; border-radius: 10px; font-weight: 700; font-size: 1rem; cursor: pointer; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(99, 102, 241, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(99, 102, 241, 0.3)'">
            💾 Simpan Perubahan
        </button>
    </form>
</div>

<script>
// Logo Upload
setupUpload('logo', '{{ route("admin.settings.logo") }}');
// Logo Pendidikan Upload
setupUpload('logo-pendidikan', '{{ route("admin.settings.logo-pendidikan") }}', 'logo_pendidikan');
// Signature Upload
setupUpload('signature', '{{ route("admin.settings.signature") }}');

function setupUpload(type, url, fieldName = type) {
    const dropZone = document.getElementById(`${type}-drop-zone`);
    const input = document.getElementById(`${type}-input`);
    const preview = document.getElementById(`${type}-preview`);
    const progress = document.getElementById(`${type}-progress`);
    const progressBar = document.getElementById(`${type}-progress-bar`);
    const status = document.getElementById(`${type}-status`);

    dropZone.addEventListener('click', () => input.click());
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#6366f1';
        dropZone.style.background = '#eef2ff';
    });
    dropZone.addEventListener('dragleave', () => {
        dropZone.style.borderColor = '#cbd5e1';
        dropZone.style.background = '#f8fafc';
    });
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#cbd5e1';
        dropZone.style.background = '#f8fafc';
        if (e.dataTransfer.files.length) {
            upload(e.dataTransfer.files[0]);
        }
    });
    input.addEventListener('change', (e) => {
        if (e.target.files.length) {
            upload(e.target.files[0]);
        }
    });

    function upload(file) {
        const formData = new FormData();
        formData.append(fieldName, file);
        formData.append('_token', '{{ csrf_token() }}');

        progress.style.display = 'block';
        status.textContent = 'Uploading...';
        progressBar.style.width = '0%';

        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                progressBar.style.width = '100%';
                status.textContent = data.message;
                status.style.color = '#10b981';
                setTimeout(() => location.reload(), 1500);
            } else {
                status.textContent = data.error || 'Upload gagal';
                status.style.color = '#ef4444';
            }
        })
        .catch(error => {
            status.textContent = 'Error: ' + error.message;
            status.style.color = '#ef4444';
        });
    }
}

function deleteLogo() {
    if (!confirm('Yakin ingin menghapus logo?')) return;
    fetch('{{ route("admin.settings.logo.delete") }}', {
        method: 'DELETE',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json'}
    })
    .then(response => response.json())
    .then(data => {
        alert(data.success ? data.message : data.error);
        if (data.success) location.reload();
    });
}

function deleteLogoPendidikan() {
    if (!confirm('Yakin ingin menghapus logo pendidikan?')) return;
    fetch('{{ route("admin.settings.logo-pendidikan.delete") }}', {
        method: 'DELETE',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json'}
    })
    .then(response => response.json())
    .then(data => {
        alert(data.success ? data.message : data.error);
        if (data.success) location.reload();
    });
}
</script>
@endsection
