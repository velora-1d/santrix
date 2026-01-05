<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar SANTRIX - {{ $selectedPlan['name'] ?? 'Paket' }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="bg-slate-50 antialiased min-h-screen">

    <div class="max-w-3xl mx-auto px-4 py-8">
        
        <!-- Back Link -->
        <div class="mb-4">
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
                <i data-feather="arrow-left" class="w-4 h-4"></i>
                Kembali ke Beranda
            </a>
        </div>

        <!-- Card Container -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-indigo-600 to-violet-600 px-8 py-10 text-white">
                @php
                    $trialDays = ($selectedPlan['duration_months'] == 3) ? 2 : 4;
                @endphp
                
                <div class="flex items-center gap-3 mb-4">
                    <i data-feather="package" class="w-5 h-5"></i>
                    <span class="font-semibold">{{ $selectedPlan['name'] }} - {{ $selectedPlan['duration_months'] }} Bulan</span>
                </div>
                
                <h1 class="text-3xl font-bold mb-3">Buat Akun Pesantren</h1>
                <p class="text-indigo-100">Dapatkan akses <strong class="text-white">trial {{ $trialDays }} hari gratis</strong> untuk mencoba semua fitur</p>
                
                <div class="flex flex-wrap gap-4 mt-6">
                    <div class="flex items-center gap-2 text-sm">
                        <i data-feather="check-circle" class="w-4 h-4"></i>
                        <span>Tanpa Kartu Kredit</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <i data-feather="zap" class="w-4 h-4"></i>
                        <span>Setup Instant</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <i data-feather="shield" class="w-4 h-4"></i>
                        <span>Data Aman</span>
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <div class="p-8">
                
                @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
                @endif

                <form action="{{ route('register.tenant.store') }}" method="POST" class="space-y-8">
                    @csrf
                    <input type="hidden" name="package" value="{{ $package }}">
                    
                    <!-- Data Pesantren -->
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                                <i data-feather="home" class="w-5 h-5 text-indigo-600"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900">Data Pesantren</h3>
                                <p class="text-xs text-slate-500">Informasi dasar pesantren Anda</p>
                            </div>
                        </div>
                        
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Pesantren <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_pesantren" value="{{ old('nama_pesantren') }}" 
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                    placeholder="Contoh: Pondok Pesantren Al-Hidayah" required>
                                @error('nama_pesantren') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Subdomain (Alamat Website) <span class="text-red-500">*</span></label>
                                <div class="flex">
                                    <input type="text" name="subdomain" value="{{ old('subdomain') }}" 
                                        class="flex-1 px-4 py-3 border border-r-0 border-slate-300 rounded-l-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                        placeholder="namapesantren" pattern="[a-z0-9-]+" required>
                                    <span class="px-4 py-3 bg-slate-100 border border-slate-300 rounded-r-lg text-slate-600">.santrix.my.id</span>
                                </div>
                                <small class="text-xs text-slate-500 mt-1 block">Hanya huruf kecil, angka, dan tanda hubung (-)</small>
                                @error('subdomain') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Akun Pemilik -->
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                                <i data-feather="user" class="w-5 h-5 text-emerald-600"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900">Akun Pemilik</h3>
                                <p class="text-xs text-slate-500">Informasi login Anda sebagai owner</p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" 
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                    placeholder="Nama Anda" required>
                                @error('name') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" 
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                    placeholder="email@pesantren.com" required>
                                @error('email') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">No. WhatsApp <span class="text-red-500">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone') }}" 
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                    placeholder="081234567890" required>
                                @error('phone') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Password <span class="text-red-500">*</span></label>
                                <div class="relative" style="position: relative;">
                                    <input type="password" name="password" id="password"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                        style="padding-right: 2.5rem;"
                                        placeholder="Minimal 8 karakter" required>
                                    <button type="button" onclick="togglePassword('password')" 
                                        class="text-slate-400 hover:text-indigo-600"
                                        style="position: absolute; right: 0; top: 0; bottom: 0; padding: 0 12px; display: flex; align-items: center; background: transparent; border: none;">
                                        <i data-feather="eye" class="w-4 h-4"></i>
                                    </button>
                                </div>
                                @error('password') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
                                <div class="relative" style="position: relative;">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                        style="padding-right: 2.5rem;"
                                        placeholder="Ulangi password" required>
                                    <button type="button" onclick="togglePassword('password_confirmation')" 
                                        class="text-slate-400 hover:text-indigo-600"
                                        style="position: absolute; right: 0; top: 0; bottom: 0; padding: 0 12px; display: flex; align-items: center; background: transparent; border: none;">
                                        <i data-feather="eye" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Details (Advance Only) -->
                    @if(str_starts_with($package, 'advance'))
                    <div class="bg-amber-50 border-2 border-amber-200 rounded-xl p-6">
                        <div class="flex items-start gap-3 mb-5">
                            <div class="w-10 h-10 rounded-lg bg-amber-500 flex items-center justify-center">
                                <i data-feather="credit-card" class="w-5 h-5 text-white"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-amber-900">Rekening Pencairan Dana</h3>
                                <p class="text-xs text-amber-700">Khusus paket Advance - untuk pencairan saldo pembayaran santri</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Bank <span class="text-red-500">*</span></label>
                                <input type="text" name="bank_name" value="{{ old('bank_name') }}" 
                                    class="w-full px-4 py-3 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white" 
                                    placeholder="BCA, BRI, Mandiri" required>
                                @error('bank_name') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">No. Rekening <span class="text-red-500">*</span></label>
                                <input type="text" name="bank_account_number" value="{{ old('bank_account_number') }}" 
                                    class="w-full px-4 py-3 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white" 
                                    placeholder="1234567890" required>
                                @error('bank_account_number') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Atas Nama <span class="text-red-500">*</span></label>
                                <input type="text" name="bank_account_name" value="{{ old('bank_account_name') }}" 
                                    class="w-full px-4 py-3 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white" 
                                    placeholder="Nama Pemilik" autocomplete="off" required>
                                @error('bank_account_name') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Submit -->
                    <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-bold py-4 rounded-xl transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                        <i data-feather="check-circle" class="w-5 h-5"></i>
                        Buat Akun & Mulai Trial {{ $trialDays }} Hari
                    </button>

                    <p class="text-center text-sm text-slate-600">
                        Sudah punya akun? <a href="{{ route('login') }}" class="text-indigo-600 font-semibold hover:underline">Masuk disini →</a>
                    </p>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-xs text-slate-500 mt-6">
            Dengan mendaftar, Anda menyetujui <a href="#" class="text-indigo-600 hover:underline">Syarat & Ketentuan</a> kami
        </p>
    </div>

    <script>
        feather.replace();

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.parentElement.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-feather', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-feather', 'eye');
            }
            feather.replace();
        }
    </script>
</body>
</html>
