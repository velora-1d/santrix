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
</head>
<body class="bg-slate-50 antialiased min-h-screen" x-data="{ 
    package: '{{ $packageSlug ?? '' }}',
    showBankDetails() {
        return this.package && this.package.startsWith('muharam');
    }
}">

    <div class="max-w-4xl mx-auto px-4 py-8">
        
        <!-- Back Link -->
        <div class="mb-4">
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
                <i data-feather="arrow-left" class="w-4 h-4"></i>
                Kembali ke Beranda
            </a>
        </div>

        <!-- Card Container -->
        <div class="bg-white rounded-2xl shadow-lg">
            
            <!-- Header Section -->
            <div class="bg-linear-to-r from-indigo-600 to-violet-600 px-8 py-10 text-white rounded-t-2xl">
                <div class="flex items-center gap-3 mb-4">
                    <i data-feather="package" class="w-5 h-5"></i>
                    <span class="font-semibold" x-text="package ? package.replace('-', ' ').toUpperCase() : 'Pilih Paket'"></span>
                </div>
                
                <h1 class="text-3xl font-bold mb-3">Buat Akun Pesantren</h1>
                <p class="text-indigo-100">Dapatkan akses <strong class="text-white">trial gratis</strong> untuk mencoba semua fitur</p>
                
                <div class="flex flex-wrap gap-4 mt-6">
                    <div class="flex items-center gap-2 text-sm">
                        <i data-feather="check-circle" class="w-4 h-4"></i>
                        <span>Tanpa Kartu Kredit</span>
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

                <form action="{{ route('register.tenant.store') }}" method="POST" class="space-y-8" autocomplete="off">
                    @csrf
                    
                    <!-- Package Selection -->
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                                <i data-feather="grid" class="w-5 h-5 text-indigo-600"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900">Pilih Paket</h3>
                                <p class="text-xs text-slate-500">Sesuaikan dengan kebutuhan pesantren Anda</p>
                            </div>
                        </div>
                        
                        <input type="hidden" name="package" :value="package">
                            @foreach($plans as $plan)
                            <div class="relative border-2 rounded-xl p-4 cursor-pointer transition-all hover:border-indigo-300"
                                @click="package = '{{ $plan['slug'] }}'"
                                :class="package === '{{ $plan['slug'] }}' ? 'border-indigo-600 bg-indigo-50 ring-2 ring-indigo-200' : 'border-slate-200 bg-white'">
                                
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center gap-2">
                                        <!-- Active State Icon -->
                                        <div x-show="package === '{{ $plan['slug'] }}'" class="text-indigo-600">
                                            <i data-feather="check-circle" class="w-5 h-5"></i>
                                        </div>
                                        <!-- Inactive State Icon -->
                                        <div x-show="package !== '{{ $plan['slug'] }}'" class="text-slate-300">
                                            <i data-feather="circle" class="w-5 h-5"></i>
                                        </div>
                                        <span class="font-bold text-slate-900">{{ $plan['name'] }}</span>
                                    </div>
                                    <span class="text-xs font-semibold px-2 py-1 rounded-full" 
                                        :class="package === '{{ $plan['slug'] }}' ? 'bg-indigo-200 text-indigo-800' : 'bg-slate-100 text-slate-600'">
                                        {{ $plan['duration_months'] }} Bulan
                                    </span>
                                </div>
                                <div class="pl-7">
                                    <div class="text-indigo-600 font-extrabold text-lg">{{ $plan['formatted_price'] }}</div>
                                    <div class="text-xs text-slate-500 mt-1">{{ Str::limit($plan['description'], 60) }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('package') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                    </div>

                    <!-- Hack to enforce no autofill in some browsers -->
                    <input type="text" style="display:none">
                    <input type="password" style="display:none">
                    
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
                                        placeholder="Minimal 8 karakter" required
                                        readonly onfocus="this.removeAttribute('readonly');" autocomplete="new-password">
                                    <button type="button" onclick="togglePassword('password')" 
                                        class="text-slate-400 hover:text-indigo-600"
                                        style="position: absolute; right: 0; top: 0; bottom: 0; padding: 0 12px; display: flex; align-items: center; background: transparent; border: none;">
                                        <i data-feather="eye" class="w-4 h-4"></i>
                                    </button>
                                </div>
                                <small class="text-xs text-slate-500">Min 8 karakter, Huruf Besar, Huruf Kecil, Angka, Simbol (@$!%*?&)</small>
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

                    <!-- Bank Details (Muharam Only) -->
                    <div x-show="showBankDetails()" x-transition class="bg-amber-50 border-2 border-amber-200 rounded-xl p-6">
                        <div class="flex items-start gap-3 mb-5">
                            <div class="w-10 h-10 rounded-lg bg-amber-500 flex items-center justify-center">
                                <i data-feather="credit-card" class="w-5 h-5 text-white"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-amber-900">Rekening Pencairan Dana</h3>
                                <p class="text-xs text-amber-700">Khusus paket Muharam - untuk pencairan saldo pembayaran santri</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Bank <span class="text-red-500">*</span></label>
                                <input type="text" name="bank_name" value="{{ old('bank_name') }}" 
                                    class="w-full px-4 py-3 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white" 
                                    placeholder="BCA, BRI, Mandiri" :required="showBankDetails()">
                                @error('bank_name') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">No. Rekening <span class="text-red-500">*</span></label>
                                <input type="text" name="bank_account_number" value="{{ old('bank_account_number') }}" 
                                    class="w-full px-4 py-3 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white" 
                                    placeholder="1234567890" :required="showBankDetails()">
                                @error('bank_account_number') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Atas Nama <span class="text-red-500">*</span></label>
                                <input type="text" name="bank_account_name" value="{{ old('bank_account_name') }}" 
                                    class="w-full px-4 py-3 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 bg-white" 
                                    placeholder="Nama Pemilik" :required="showBankDetails()"
                                    readonly onfocus="this.removeAttribute('readonly');" autocomplete="off">
                                @error('bank_account_name') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pilihan Metode Mulai (Only show if package selected) -->
                    <div x-show="package" class="bg-indigo-50 border-2 border-indigo-100 rounded-xl p-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-4">Mulai Dengan:</label>
                        
                        <div class="space-y-3">
                            <!-- Option 1: Trial (Recommended) -->
                            <!-- Option 1: Demo (Redirect) -->
                            <a href="{{ route('demo.start', ['type' => 'sekretaris']) }}" target="_blank" class="flex items-center p-4 border rounded-lg cursor-pointer transition-all bg-white border-cyan-200 hover:border-cyan-400 group relative">
                                <div class="w-5 h-5 rounded-full border-2 border-cyan-500 flex items-center justify-center">
                                    <i data-feather="monitor" class="w-3 h-3 text-cyan-500"></i>
                                </div>
                                <div class="ml-3 flex-1">
                                    <span class="block text-sm font-bold text-slate-900 group-hover:text-cyan-600 transition-colors">Coba Demo Aja Dulu</span>
                                    <span class="block text-xs text-slate-500">Lihat simulasi dashboard lengkap tanpa daftar.</span>
                                </div>
                                <i data-feather="external-link" class="w-4 h-4 text-slate-300 group-hover:text-cyan-500"></i>
                            </a>

                            <!-- Option 2: Direct Payment -->
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer transition-all bg-white border-slate-200 hover:border-indigo-400 ring-2 ring-indigo-500/10">
                                <input type="radio" name="payment_method" value="transfer" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500" checked>
                                <div class="ml-3 flex-1">
                                    <span class="block text-sm font-bold text-slate-900">Langsung Bayar / Langganan</span>
                                    <span class="block text-xs text-slate-500">Lewati masa trial, langsung aktifkan langganan.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="w-full bg-linear-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-bold py-4 rounded-xl transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                        <i data-feather="check-circle" class="w-5 h-5"></i>
                        Buat Akun Pesantren
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
