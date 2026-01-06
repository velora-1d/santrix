@extends('layouts.app')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')

@section('sidebar-menu')
    @include('admin.partials.sidebar-menu')
@endsection

@include('components.bottom-nav', ['active' => 'settings', 'context' => 'admin'])

@section('drawer-menu')
    <li class="drawer-menu-item">
        <form method="POST" action="{{ route('tenant.logout') }}">
            @csrf
            <button type="submit" class="drawer-menu-link" style="width: 100%; background: none; border: none; cursor: pointer; text-align: left;">
                <i data-feather="log-out"></i>
                <span>Logout</span>
            </button>
        </form>
    </li>
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

    <!-- Page Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px; box-shadow: 0 20px 60px rgba(102, 126, 234, 0.4); position: relative; overflow: hidden;">
        <div style="position: absolute; top: -40px; right: -40px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="display: flex; align-items: center; gap: 20px; position: relative; z-index: 1; color: white;">
            <div style="background: rgba(255,255,255,0.2); width: 64px; height: 64px; border-radius: 16px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px);">
                <i data-feather="settings" style="width: 32px; height: 32px; color: white;"></i>
            </div>
            <div>
                <h1 style="font-size: 1.8rem; font-weight: 900; margin: 0 0 4px 0;">Assalamualaikum {{ ucwords(str_replace('_', ' ', auth()->user()->role)) }} {{ auth()->user()->pesantren->nama ?? '' }}</h1>
                <p style="opacity: 0.9; font-size: 1rem; margin: 0;">Kelola konfigurasi aplikasi dan user</p>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div style="background: white; border-radius: 16px; padding: 8px; margin-bottom: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); display: flex; gap: 8px;">
        <button onclick="showTab('app')" id="tab-app" class="tab-button active" style="flex: 1; padding: 12px 24px; border: none; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
            <i data-feather="sliders" style="width: 18px; height: 18px; margin-right: 8px;"></i>
            Aplikasi
        </button>
        <button onclick="showTab('users')" id="tab-users" class="tab-button" style="flex: 1; padding: 12px 24px; border: none; background: #f3f4f6; color: #6b7280; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
            <i data-feather="users" style="width: 18px; height: 18px; margin-right: 8px;"></i>
            Manajemen User
        </button>
        <button onclick="showTab('backup')" id="tab-backup" class="tab-button" style="flex: 1; padding: 12px 24px; border: none; background: #f3f4f6; color: #6b7280; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
            <i data-feather="database" style="width: 18px; height: 18px; margin-right: 8px;"></i>
            Backup
        </button>
        <button onclick="showTab('kelas-asrama')" id="tab-kelas-asrama" class="tab-button" style="flex: 1; padding: 12px 24px; border: none; background: #f3f4f6; color: #6b7280; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
            <i data-feather="grid" style="width: 18px; height: 18px; margin-right: 8px;"></i>
            Kelas & Asrama
        </button>
        <button onclick="showTab('system')" id="tab-system" class="tab-button" style="flex: 1; padding: 12px 24px; border: none; background: #f3f4f6; color: #6b7280; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
            <i data-feather="info" style="width: 18px; height: 18px; margin-right: 8px;"></i>
            Info Sistem
        </button>
    </div>

    <!-- Tab Content: Aplikasi -->
    <div id="content-app" class="tab-content">
        <!-- ... (existing app content) ... -->
    </div>

    <!-- Tab Content: Kelas & Asrama -->
    <div id="content-kelas-asrama" class="tab-content" style="display: none;">
        
        <!-- Kelas Section -->
        <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); margin-bottom: 24px;">
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #1f2937; margin: 0 0 24px 0;">Manajemen Kelas</h3>
            
            <!-- Form Tambah Kelas -->
            <form method="POST" action="{{ route('admin.pengaturan.kelas.store') }}" style="background: #f8fafc; padding: 24px; border-radius: 12px; margin-bottom: 24px; border: 1px solid #e2e8f0;">
                @csrf
                <div style="display: grid; grid-template-columns: repeat(3, 1fr) auto; gap: 16px; align-items: end;">
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Nama Kelas</label>
                        <input type="text" name="nama_kelas" placeholder="Contoh: 1A" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Tingkat</label>
                        <input type="text" name="tingkat" placeholder="Contoh: Ibtida / 1" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Kapasitas</label>
                        <input type="number" name="kapasitas" value="30" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                    </div>
                    <button type="submit" style="background: #3b82f6; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        <i data-feather="plus"></i> Tambah
                    </button>
                </div>
            </form>

            <!-- Table Kelas -->
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f1f5f9; text-align: left;">
                            <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Kelas</th>
                            <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Tingkat</th>
                            <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Santri</th>
                            <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Kapasitas</th>
                            <th style="padding: 12px; border-bottom: 2px solid #e2e8f0; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelas_list as $kelas)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 12px; font-weight: 600;">{{ $kelas->nama_kelas }}</td>
                            <td style="padding: 12px;">{{ $kelas->tingkat }}</td>
                            <td style="padding: 12px;">
                                <span style="background: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 4px; font-size: 0.875rem; font-weight: 600;">
                                    {{ $kelas->santri_count }}
                                </span>
                            </td>
                            <td style="padding: 12px;">{{ $kelas->kapasitas }}</td>
                            <td style="padding: 12px; text-align: center;">
                                <button onclick="editKelas({{ $kelas->id }}, '{{ $kelas->nama_kelas }}', '{{ $kelas->tingkat }}', {{ $kelas->kapasitas }})" style="background: none; border: none; color: #3b82f6; cursor: pointer; margin-right: 8px;">
                                    <i data-feather="edit-2" style="width: 16px; height: 16px;"></i>
                                </button>
                                <form action="{{ route('admin.pengaturan.kelas.delete', $kelas->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin hapus kelas ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer;">
                                        <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="padding: 24px; text-align: center; color: #64748b;">Belum ada data kelas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Asrama Section -->
        <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #1f2937; margin: 0 0 24px 0;">Manajemen Asrama</h3>
            
            <!-- Form Tambah Asrama -->
            <form method="POST" action="{{ route('admin.pengaturan.asrama.store') }}" style="background: #f8fafc; padding: 24px; border-radius: 12px; margin-bottom: 24px; border: 1px solid #e2e8f0;">
                @csrf
                <div style="display: grid; grid-template-columns: repeat(3, 1fr) auto; gap: 16px; align-items: end;">
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Nama Asrama</label>
                        <input type="text" name="nama_asrama" placeholder="Contoh: Al-Hamra" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Gender</label>
                        <select name="gender" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                            <option value="putra">Putra</option>
                            <option value="putri">Putri</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Jumlah Kamar</label>
                        <input type="number" name="jumlah_kamar" value="5" min="1" max="50" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                    </div>
                    <button type="submit" style="background: #3b82f6; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        <i data-feather="plus"></i> Tambah
                    </button>
                </div>
            </form>

            <!-- Table Asrama -->
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f1f5f9; text-align: left;">
                            <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Asrama</th>
                            <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Gender</th>
                            <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Jml Kamar</th>
                            <th style="padding: 12px; border-bottom: 2px solid #e2e8f0;">Total Santri</th>
                            <th style="padding: 12px; border-bottom: 2px solid #e2e8f0; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asrama_list as $asrama)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 12px; font-weight: 600;">{{ $asrama->nama_asrama }}</td>
                            <td style="padding: 12px;">
                                @if($asrama->gender == 'putra')
                                    <span style="color: #2563eb; background: #eff6ff; padding: 4px 8px; border-radius: 4px; font-weight: 600;">Putra</span>
                                @else
                                    <span style="color: #db2777; background: #fdf2f8; padding: 4px 8px; border-radius: 4px; font-weight: 600;">Putri</span>
                                @endif
                            </td>
                            <td style="padding: 12px;">{{ $asrama->kobong_count }} Kamar</td>
                            <td style="padding: 12px;">
                                <span style="background: #dbeafe; color: #1e40af; padding: 4px 8px; border-radius: 4px; font-size: 0.875rem; font-weight: 600;">
                                    {{ $asrama->santri_count }}
                                </span>
                            </td>
                            <td style="padding: 12px; text-align: center;">
                                <button onclick="editAsrama({{ $asrama->id }}, '{{ $asrama->nama_asrama }}', '{{ $asrama->gender }}')" style="background: none; border: none; color: #3b82f6; cursor: pointer; margin-right: 8px;">
                                    <i data-feather="edit-2" style="width: 16px; height: 16px;"></i>
                                </button>
                                <form action="{{ route('admin.pengaturan.asrama.delete', $asrama->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin hapus asrama ini? Semua data kamar juga akan terhapus.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer;">
                                        <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="padding: 24px; text-align: center; color: #64748b;">Belum ada data asrama</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
        <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #1f2937; margin: 0 0 24px 0;">Pengaturan Aplikasi</h3>
            
            <form method="POST" action="{{ route('admin.pengaturan.app') }}">
                @csrf
                <div style="display: grid; gap: 20px;">
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Nama Aplikasi</label>
                        <input type="text" name="app_name" value="{{ session('app_name', 'SANTRIX Dashboard') }}" required style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem; transition: all 0.3s;" onfocus="this.style.borderColor='#667eea'" onblur="this.style.borderColor='#e5e7eb'">
                    </div>
                    
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Kontak</label>
                        <input type="text" name="app_contact" value="{{ session('app_contact', '+62 xxx xxxx xxxx') }}" style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem; transition: all 0.3s;" onfocus="this.style.borderColor='#667eea'" onblur="this.style.borderColor='#e5e7eb'">
                    </div>
                    
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Email</label>
                        <input type="email" name="app_email" value="{{ session('app_email', 'info@riyadlulhuda.com') }}" style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem; transition: all 0.3s;" onfocus="this.style.borderColor='#667eea'" onblur="this.style.borderColor='#e5e7eb'">
                    </div>
                    
                    <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 14px 32px; border: none; border-radius: 10px; font-weight: 700; font-size: 1rem; cursor: pointer; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(102, 126, 234, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.3)'">
                        <i data-feather="save" style="width: 18px; height: 18px; margin-right: 8px;"></i>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tab Content: Users -->
    <div id="content-users" class="tab-content" style="display: none;">
        <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h3 style="font-size: 1.5rem; font-weight: 800; color: #1f2937; margin: 0;">Tambah User Baru</h3>
            </div>
            
            <form method="POST" action="{{ route('admin.pengaturan.user.create') }}">
                @csrf
                
                @if ($errors->any())
                    <div style="background: #fef2f2; border: 1px solid #fee2e2; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
                        <div style="display: flex; align-items: center; gap: 8px; color: #ef4444; font-weight: 700; margin-bottom: 8px;">
                            <i data-feather="alert-circle" style="width: 18px; height: 18px;"></i>
                            Gagal Menyimpan Data
                        </div>
                        <ul style="margin: 0; padding-left: 24px; color: #b91c1c; font-size: 0.875rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Nama</label>
                        <input type="text" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 12px 16px; border: 2px solid {{ $errors->has('name') ? '#ef4444' : '#e5e7eb' }}; border-radius: 10px; font-size: 1rem;">
                        @error('name')
                            <p style="color: #ef4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required style="width: 100%; padding: 12px 16px; border: 2px solid {{ $errors->has('email') ? '#ef4444' : '#e5e7eb' }}; border-radius: 10px; font-size: 1rem;">
                        @error('email')
                            <p style="color: #ef4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Password</label>
                        <div style="position: relative;">
                            <input type="password" name="password" id="create_password" required minlength="8" style="width: 100%; padding: 12px 40px 12px 16px; border: 2px solid {{ $errors->has('password') ? '#ef4444' : '#e5e7eb' }}; border-radius: 10px; font-size: 1rem;">
                            <button type="button" onclick="togglePassword('create_password', 'create_eye_icon')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #6b7280;">
                                <i data-feather="eye" id="create_eye_icon" style="width: 20px; height: 20px;"></i>
                            </button>
                        </div>
                        @error('password')
                            <p style="color: #ef4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</p>
                        @enderror
                        <p style="color: #9ca3af; font-size: 0.75rem; margin-top: 4px;">Minimal 8 karakter</p>
                    </div>
                    
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Role</label>
                        <select name="role" required style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem;">
                            <option value="admin_pusat">Admin Pusat</option>
                            <option value="sekretaris">Sekretaris</option>
                            <option value="bendahara">Bendahara</option>
                            <option value="pendidikan">Pendidikan</option>
                        </select>
                    </div>
                </div>
                
                <button type="submit" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 12px 28px; border: none; border-radius: 10px; font-weight: 700; margin-top: 16px; cursor: pointer; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                    <i data-feather="user-plus" style="width: 18px; height: 18px; margin-right: 8px;"></i>
                    Tambah User
                </button>
            </form>
        </div>

        <!-- Users Table -->
        <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #1f2937; margin: 0 0 24px 0;">Daftar User</h3>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 16px; text-align: left; font-weight: 700; color: #1e293b;">Nama</th>
                            <th style="padding: 16px; text-align: left; font-weight: 700; color: #1e293b;">Email</th>
                            <th style="padding: 16px; text-align: left; font-weight: 700; color: #1e293b;">Role</th>
                            <th style="padding: 16px; text-align: left; font-weight: 700; color: #1e293b;">Dibuat</th>
                            <th style="padding: 16px; text-align: center; font-weight: 700; color: #1e293b;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                            <td style="padding: 16px; font-weight: 600; color: #1f2937;">{{ $user->name }}</td>
                            <td style="padding: 16px; color: #6b7280;">{{ $user->email }}</td>
                            <td style="padding: 16px;">
                                <span style="padding: 6px 12px; border-radius: 6px; font-size: 0.875rem; font-weight: 600; 
                                    @if($user->role === 'admin_pusat') background: #fef3c7; color: #92400e;
                                    @elseif($user->role === 'sekretaris') background: #dbeafe; color: #1e40af;
                                    @elseif($user->role === 'bendahara') background: #d1fae5; color: #065f46;
                                    @else background: #fce7f3; color: #9f1239;
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>
                            <td style="padding: 16px; color: #6b7280;">{{ $user->created_at->format('d M Y') }}</td>
                            <td style="padding: 16px; text-align: center;">
                                <button onclick="editUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}')" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 8px 16px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; margin-right: 8px;">
                                    <i data-feather="edit-2" style="width: 16px; height: 16px;"></i>
                                </button>
                                
                                @if($user->id !== auth()->user()->id)
                                <form method="POST" action="{{ route('admin.pengaturan.user.delete', $user->id) }}" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 8px 16px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                                        <i data-feather="trash-2" style="width: 16px; height: 16px;"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab Content: Backup -->
    <div id="content-backup" class="tab-content" style="display: none;">
        <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #1f2937; margin: 0 0 16px 0;">Backup Database</h3>
            <p style="color: #6b7280; margin-bottom: 24px;">Download backup database dalam format SQL untuk keamanan data.</p>
            
            <a href="{{ route('backup.download') }}" style="display: inline-flex; align-items: center; gap: 12px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 16px 32px; border-radius: 12px; text-decoration: none; font-weight: 700; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                <i data-feather="download" style="width: 24px; height: 24px;"></i>
                Download Backup Database
            </a>
        </div>
    </div>

    <!-- Tab Content: System -->
    <div id="content-system" class="tab-content" style="display: none;">
        <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #1f2937; margin: 0 0 24px 0;">Informasi Sistem</h3>
            
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); padding: 24px; border-radius: 12px; border: 2px solid #bae6fd;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <i data-feather="code" style="width: 24px; height: 24px; color: #0284c7;"></i>
                        <span style="font-weight: 700; color: #0c4a6e;">Framework</span>
                    </div>
                    <p style="font-size: 1.5rem; font-weight: 800; color: #0369a1; margin: 0;">Laravel {{ app()->version() }}</p>
                </div>
                
                <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); padding: 24px; border-radius: 12px; border: 2px solid #fcd34d;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <i data-feather="server" style="width: 24px; height: 24px; color: #ca8a04;"></i>
                        <span style="font-weight: 700; color: #713f12;">PHP Version</span>
                    </div>
                    <p style="font-size: 1.5rem; font-weight: 800; color: #a16207; margin: 0;">{{ phpversion() }}</p>
                </div>
                
                <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); padding: 24px; border-radius: 12px; border: 2px solid #bbf7d0;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <i data-feather="database" style="width: 24px; height: 24px; color: #16a34a;"></i>
                        <span style="font-weight: 700; color: #14532d;">Database</span>
                    </div>
                    <p style="font-size: 1.5rem; font-weight: 800; color: #15803d; margin: 0;">MySQL</p>
                </div>
                
                <div style="background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%); padding: 24px; border-radius: 12px; border: 2px solid #f9a8d4;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                        <i data-feather="zap" style="width: 24px; height: 24px; color: #db2777;"></i>
                        <span style="font-weight: 700; color: #831843;">App Version</span>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Edit Kelas Modal -->
    <div id="editKelasModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 20px; padding: 32px; max-width: 500px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #1f2937; margin: 0 0 24px 0;">Edit Kelas</h3>
            
            <form id="editKelasForm" method="POST">
                @csrf
                @method('PUT')
                <div style="display: grid; gap: 16px;">
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Nama Kelas</label>
                        <input type="text" id="edit_kelas_nama" name="nama_kelas" required style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Tingkat</label>
                        <input type="text" id="edit_kelas_tingkat" name="tingkat" required style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Kapasitas</label>
                        <input type="number" id="edit_kelas_kapasitas" name="kapasitas" required style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem;">
                    </div>
                </div>
                
                <div style="display: flex; gap: 12px; margin-top: 24px;">
                    <button type="submit" style="flex: 1; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 12px; border: none; border-radius: 10px; font-weight: 700; cursor: pointer;">Simpan</button>
                    <button type="button" onclick="closeEditKelasModal()" style="flex: 1; background: #e5e7eb; color: #374151; padding: 12px; border: none; border-radius: 10px; font-weight: 700; cursor: pointer;">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Asrama Modal -->
    <div id="editAsramaModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 20px; padding: 32px; max-width: 500px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #1f2937; margin: 0 0 24px 0;">Edit Asrama</h3>
            
            <form id="editAsramaForm" method="POST">
                @csrf
                @method('PUT')
                <div style="display: grid; gap: 16px;">
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Nama Asrama</label>
                        <input type="text" id="edit_asrama_nama" name="nama_asrama" required style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Gender</label>
                        <select id="edit_asrama_gender" name="gender" required style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem;">
                            <option value="putra">Putra</option>
                            <option value="putri">Putri</option>
                        </select>
                    </div>
                    <div style="background: #fef2f2; border: 1px solid #fecaca; padding: 12px; border-radius: 8px;">
                        <span style="font-size: 0.875rem; color: #b91c1c;">
                            <i data-feather="alert-triangle" style="width: 14px; height: 14px; vertical-align: text-bottom;"></i>
                            Jumlah kamar tidak dapat diedit. Untuk menambah kamar, silakan gunakan fitur tambah kamar (coming soon).
                        </span>
                    </div>
                </div>
                
                <div style="display: flex; gap: 12px; margin-top: 24px;">
                    <button type="submit" style="flex: 1; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 12px; border: none; border-radius: 10px; font-weight: 700; cursor: pointer;">Simpan</button>
                    <button type="button" onclick="closeEditAsramaModal()" style="flex: 1; background: #e5e7eb; color: #374151; padding: 12px; border: none; border-radius: 10px; font-weight: 700; cursor: pointer;">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Modal (Hidden by default) -->
    <div id="editUserModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 20px; padding: 32px; max-width: 500px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #1f2937; margin: 0 0 24px 0;">Edit User</h3>
            
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div style="display: grid; gap: 16px;">
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Nama</label>
                        <input type="text" id="edit_name" name="name" required style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem;">
                    </div>
                    
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Email</label>
                        <input type="email" id="edit_email" name="email" required style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem;">
                    </div>
                    
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Password Baru (kosongkan jika tidak diubah)</label>
                        <input type="password" id="edit_password" name="password" style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem;">
                    </div>
                    
                    <div>
                        <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 8px;">Role</label>
                        <select id="edit_role" name="role" required style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem;">
                            <option value="admin_pusat">Admin Pusat</option>
                            <option value="sekretaris">Sekretaris</option>
                            <option value="bendahara">Bendahara</option>
                            <option value="pendidikan">Pendidikan</option>
                        </select>
                    </div>
                </div>
                
                <div style="display: flex; gap: 12px; margin-top: 24px;">
                    <button type="submit" style="flex: 1; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 12px; border: none; border-radius: 10px; font-weight: 700; cursor: pointer;">
                        Simpan
                    </button>
                    <button type="button" onclick="closeEditModal()" style="flex: 1; background: #e5e7eb; color: #374151; padding: 12px; border: none; border-radius: 10px; font-weight: 700; cursor: pointer;">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Tab switching
        function showTab(tabName) {
            // Hide all content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.style.display = 'none';
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.style.background = '#f3f4f6';
                button.style.color = '#6b7280';
            });
            
            // Show selected content
            document.getElementById('content-' + tabName).style.display = 'block';
            
            // Add active class to selected button
            const activeButton = document.getElementById('tab-' + tabName);
            activeButton.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
            activeButton.style.color = 'white';
            
            // Re-initialize feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }
        
        // Auto-show tab based on URL param or session flash
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');
            
            if (tabParam) {
                // Check if tab content exists before trying to show it
                if (document.getElementById('content-' + tabParam)) {
                    showTab(tabParam);
                }
            } 
            @if(session('tab'))
            else {
                showTab('{{ session('tab') }}');
            }
            @endif
        });
        
        // Edit user modal
        function editUser(id, name, email, role) {
            document.getElementById('editUserForm').action = '/admin/pengaturan/user/' + id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role').value = role;
            document.getElementById('edit_password').value = '';
            document.getElementById('editUserModal').style.display = 'flex';
        }
        
        function closeEditModal() {
            document.getElementById('editUserModal').style.display = 'none';
        }

        // Edit Kelas Modal
        function editKelas(id, nama, tingkat, kapasitas) {
            document.getElementById('editKelasForm').action = '/admin/pengaturan/kelas/' + id;
            document.getElementById('edit_kelas_nama').value = nama;
            document.getElementById('edit_kelas_tingkat').value = tingkat;
            document.getElementById('edit_kelas_kapasitas').value = kapasitas;
            document.getElementById('editKelasModal').style.display = 'flex';
        }

        function closeEditKelasModal() {
            document.getElementById('editKelasModal').style.display = 'none';
        }

        // Edit Asrama Modal
        function editAsrama(id, nama, gender) {
            document.getElementById('editAsramaForm').action = '/admin/pengaturan/asrama/' + id;
            document.getElementById('edit_asrama_nama').value = nama;
            document.getElementById('edit_asrama_gender').value = gender;
            document.getElementById('editAsramaModal').style.display = 'flex';
        }

        function closeEditAsramaModal() {
            document.getElementById('editAsramaModal').style.display = 'none';
        }
        
        // Close modal on outside click
        window.onclick = function(event) {
            if (event.target == document.getElementById('editUserModal')) {
                closeEditModal();
            }
            if (event.target == document.getElementById('editKelasModal')) {
                closeEditKelasModal();
            }
            if (event.target == document.getElementById('editAsramaModal')) {
                closeEditAsramaModal();
            }
        }
        // Toggle Password Visibility
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === "password") {
                input.type = "text";
                icon.innerHTML = '<polyline points="17.94 17.94 24 24 0.94 0.94 7.03 7.03"></polyline><path d="M22.73 17.3a13.3 13.3 0 0 0 1.94-3.8c-2.3-5.5-7.8-9.4-14.3-9.4a13.2 13.2 0 0 0-3.6.5"></path><path d="M1 1l22 22"></path><path d="M9.9 4.24a9.12 9.12 0 0 1 2.55-.23 13.26 13.26 0 0 1 10.22 5.39"></path>'; // crossed eye path
            } else {
                input.type = "password";
                icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>'; // open eye path
                feather.replace(); // Re-render feather icons if needed, but manual SVG might be safer for dynamic change
            }
            
            // Simpler approach: just switch icon class if using feather replace
            if (typeof feather !== 'undefined') {
                 setTimeout(() => {
                    icon.setAttribute('data-feather', input.type === "password" ? 'eye' : 'eye-off');
                    feather.replace();
                 }, 0);
            }
        }
    </script>
@endsection
