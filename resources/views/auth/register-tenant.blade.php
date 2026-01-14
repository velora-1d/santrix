<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Pesantren - Santrix</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/feather-icons"></script>
    
    <!-- AlpineJS -->
    <script>window.Alpine = window.Alpine || {};</script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Outfit', sans-serif; }
        .font-heading { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* Custom Scrollbar for Form Container */
        .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }
        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f1f1; 
        }
        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #d1fae5; 
            border-radius: 3px;
        }
        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #10b981; 
        }
        /* Hide scrollbar for Chrome, Safari and Opera */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        /* Hide scrollbar for IE, Edge and Firefox */
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
</head>
<body class="bg-gray-50 antialiased text-gray-900">

<div class="min-h-screen bg-white md:bg-gray-50 flex"
     x-data="{ 
        package: '{{ $packageSlug }}', 
        billingCycle: 'monthly',
        toggleBilling() {
            this.billingCycle = this.billingCycle === 'monthly' ? 'yearly' : 'monthly';
        }
     }"
     x-init="$watch('package', value => {
         const input = document.getElementById('selected-package');
         if(input) input.value = value;
     })">
    
    <!-- Left Side: Clean Simple Branding -->
    <div class="hidden lg:flex lg:w-1/3 bg-emerald-900 flex-col justify-between p-12 relative overflow-hidden text-white">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10" 
             style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');">
        </div>
        
        <div class="relative z-10">
            <h1 class="text-3xl font-bold font-heading mb-2">Santrix</h1>
            <p class="text-emerald-200">Platform Digital Pesantren</p>
        </div>

        <div class="relative z-10 space-y-6">
            <blockquote class="text-xl font-light italic opacity-90">
                &quot;Kelola pesantren dengan amanah, transparan, dan modern sesuai kaidah syariah.&quot;
            </blockquote>
            <div class="flex items-center gap-3 pt-4 border-t border-emerald-700/50">
                <div class="w-10 h-10 rounded-full bg-emerald-800 flex items-center justify-center">
                    <i data-feather="shield" class="w-5 h-5 text-emerald-400"></i>
                </div>
                <div>
                    <p class="font-medium text-sm">Privasi & Keamanan Terjamin</p>
                    <p class="text-xs text-emerald-400">Enkripsi Data Standar Industri</p>
                </div>
            </div>
        </div>

        <div class="relative z-10 text-xs text-emerald-400">
            &copy; {{ date('Y') }} Santrix. All rights reserved.
        </div>
    </div>

    <!-- Right Side: Registration Form -->
    <div class="flex-1 flex flex-col justify-center py-6 px-4 sm:px-6 lg:px-20 xl:px-24 overflow-y-auto h-screen bg-emerald-50/30">
        
        <div class="max-w-2xl w-full mx-auto bg-white p-6 md:p-10 rounded-3xl shadow-xl shadow-emerald-100/50 border border-emerald-50">
            <!-- Mobile Header -->
            <div class="lg:hidden text-center mb-8">
                <h1 class="text-3xl font-bold text-emerald-900">Santrix</h1>
                <p class="mt-2 text-sm text-gray-600">Mulai Digitalisasi Pesantren Anda</p>
            </div>

            <!-- Header -->
            <div class="mb-8 md:mb-10">
                <a href="/" class="group inline-flex items-center text-sm font-medium text-gray-500 hover:text-emerald-600 mb-6 transition-colors">
                    <i data-feather="arrow-left" class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform"></i>
                    Kembali ke Beranda
                </a>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 tracking-tight font-heading">Buat Akun Pesantren</h2>
                <p class="mt-2 text-gray-600 text-sm md:text-base">Lengkapi data di bawah untuk memulai masa percobaan gratis.</p>
            </div>

            <form method="POST" action="{{ route('register.tenant') }}" class="space-y-8" x-data="{ showBankDetails: false }">
                @csrf
                <input type="hidden" name="package_slug" id="selected-package" :value="package">

                <!-- 1. Pilih Paket (Simplified) -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold">1</span>
                            Pilih Paket
                        </h3>
                        
                        <!-- Billing Toggle -->
                        <div class="flex items-center bg-gray-100 p-1 rounded-lg">
                            <button type="button" 
                                    @click="billingCycle = 'monthly'"
                                    class="px-4 py-1.5 text-xs md:text-sm font-medium rounded-md transition-all duration-200"
                                    :class="billingCycle === 'monthly' ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                                Bulanan
                            </button>
                            <button type="button" 
                                    @click="billingCycle = 'yearly'"
                                    class="px-4 py-1.5 text-xs md:text-sm font-medium rounded-md transition-all duration-200"
                                    :class="billingCycle === 'yearly' ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
                                Tahunan
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($packages as $pkg)
                        <div x-cloak x-show="(billingCycle === 'monthly' && {{ $pkg->duration_in_days }} === 30) || (billingCycle === 'yearly' && {{ $pkg->duration_in_days }} === 365)"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="relative group cursor-pointer"
                             @click="package = '{{ $pkg->slug }}'; showBankDetails = ('{{ $pkg->slug }}'.startsWith('muharam'))">
                            
                            <div class="p-5 rounded-xl border-2 transition-all duration-200 flex flex-col h-full bg-white hover:border-emerald-300"
                                 :class="package === '{{ $pkg->slug }}' 
                                    ? 'border-emerald-500 bg-emerald-50/50 shadow-md ring-1 ring-emerald-500' 
                                    : 'border-gray-200 shadow-sm'">
                                
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h4 class="font-bold text-gray-900 uppercase tracking-wide text-sm">{{ $pkg->name }}</h4>
                                        <div class="mt-1 flex items-baseline gap-1">
                                            <span class="text-2xl font-bold text-gray-900">Rp {{ number_format($pkg->price, 0, ',', '.') }}</span>
                                            <span class="text-xs text-gray-500 font-medium">/{{ $pkg->duration_in_days == 30 ? 'bulan' : 'tahun' }}</span>
                                        </div>
                                    </div>
                                    <div class="w-5 h-5 rounded-full border border-gray-300 flex items-center justify-center transition-colors"
                                         :class="package === '{{ $pkg->slug }}' ? 'bg-emerald-500 border-emerald-500' : 'bg-white'">
                                        <div x-show="package === '{{ $pkg->slug }}'">
                                            <i data-feather="check" class="w-3 h-3 text-white"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 leading-relaxed mt-auto pt-2 border-t border-gray-100">
                                    {{ $pkg->description }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @error('package_slug') <p class="text-red-500 text-sm mt-1 pl-1">{{ $message }}</p> @enderror
                </div>

                <div class="border-t border-gray-100"></div>

                <!-- 2. Identitas Pesantren -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold">2</span>
                        Identitas Pesantren
                    </h3>

                    <div class="grid grid-cols-1 gap-y-6 gap-x-4">
                        <!-- Nama Pesantren -->
                        <div>
                            <label for="nama_pesantren" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Pesantren</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-feather="home" class="w-5 h-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                </div>
                                <input type="text" name="nama_pesantren" id="nama_pesantren" required
                                    class="block w-full pl-10 pr-3 py-3 border-gray-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm shadow-sm transition-all"
                                    style="padding-left: 2.75rem !important;"
                                    placeholder="Contoh: Pondok Pesantren Darul Ulum"
                                    value="{{ old('nama_pesantren') }}">
                            </div>
                            @error('nama_pesantren') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Subdomain -->
                        <div>
                            <label for="subdomain" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Website (Subdomain)</label>
                            <div class="flex rounded-xl shadow-sm ring-1 ring-gray-300 focus-within:ring-2 focus-within:ring-emerald-500 focus-within:border-emerald-500 overflow-hidden bg-white">
                                <input type="text" name="subdomain" id="subdomain" required
                                    class="flex-1 block w-full min-w-0 border-none focus:ring-0 sm:text-sm py-3 pl-4 text-gray-900 placeholder-gray-400 bg-transparent"
                                    placeholder="namapesantren"
                                    value="{{ old('subdomain') }}">
                                <span class="inline-flex items-center px-4 bg-gray-50 text-gray-500 text-sm border-l border-gray-200 font-medium tracking-wide">
                                    .santrix.my.id
                                </span>
                            </div>
                            <p class="mt-1.5 text-xs text-gray-500">Gunakan huruf kecil dan angka saja. Tanpa spasi.</p>
                            @error('subdomain') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-100"></div>

                <!-- 3. Akun Administrator -->
                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold">3</span>
                        Akun Administrator
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Nama Lengkap -->
                        <div class="col-span-1 md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap Pengurus</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-feather="user" class="w-5 h-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                </div>
                                <input type="text" name="name" id="name" required
                                    class="block w-full pl-10 pr-3 py-3 border-gray-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm shadow-sm"
                                    style="padding-left: 2.75rem !important;"
                                    placeholder="Nama Lengkap"
                                    value="{{ old('name') }}">
                            </div>
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-span-1">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Email</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-feather="mail" class="w-5 h-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                </div>
                                <input type="email" name="email" id="email" required
                                    class="block w-full pl-10 pr-3 py-3 border-gray-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm shadow-sm"
                                    style="padding-left: 2.75rem !important;"
                                    placeholder="email@pesantren.com"
                                    value="{{ old('email') }}">
                            </div>
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- WhatsApp -->
                        <div class="col-span-1">
                            <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1.5">No. WhatsApp</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-feather="smartphone" class="w-5 h-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                </div>
                                <input type="text" name="no_hp" id="no_hp" required
                                    class="block w-full pl-10 pr-3 py-3 border-gray-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm shadow-sm"
                                    style="padding-left: 2.75rem !important;"
                                    placeholder="08123xxxx"
                                    value="{{ old('no_hp') }}">
                            </div>
                            @error('no_hp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Kata Sandi</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-feather="lock" class="w-5 h-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                </div>
                                <input type="password" name="password" id="password" required
                                    class="block w-full pl-10 pr-3 py-3 border-gray-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm shadow-sm"
                                    style="padding-left: 2.75rem !important;"
                                    placeholder="••••••••">
                            </div>
                            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Sandi</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-feather="check-circle" class="w-5 h-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                </div>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                    class="block w-full pl-10 pr-3 py-3 border-gray-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm shadow-sm"
                                    style="padding-left: 2.75rem !important;"
                                    placeholder="••••••••">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Rekening Pencairan (Conditional) -->
                <div x-show="showBankDetails" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                     class="rounded-xl bg-emerald-50 border border-emerald-100 p-6 space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="p-2 bg-white rounded-lg shadow-sm text-emerald-600">
                            <i data-feather="credit-card" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">Rekening Pencairan Dana</h3>
                            <p class="text-sm text-gray-600 mt-1">Khusus paket Muharam untuk pencairan donasi/pembayaran.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="col-span-1 md:col-span-2">
                            <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Bank</label>
                            <input type="text" name="bank_name" id="bank_name"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2.5"
                                placeholder="Contoh: Bank Syariah Indonesia"
                                value="{{ old('bank_name') }}">
                        </div>
                        <div>
                            <label for="account_number" class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Rekening</label>
                            <input type="text" name="account_number" id="account_number"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2.5"
                                placeholder="1234567890"
                                value="{{ old('account_number') }}">
                        </div>
                        <div>
                            <label for="account_holder" class="block text-sm font-medium text-gray-700 mb-1.5">Atas Nama</label>
                            <input type="text" name="account_holder" id="account_holder"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2.5"
                                placeholder="Nama Pemilik Rekening"
                                value="{{ old('account_holder') }}">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 transform hover:-translate-y-0.5">
                        Daftar Pendaftaran Sekarang
                    </button>
                    <p class="mt-4 text-center text-xs text-gray-500">
                        Dengan mendaftar, Anda menyetujui <a href="#" class="font-medium text-emerald-600 hover:text-emerald-500">Syarat & Ketentuan</a> kami.
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Initialize Feather Icons
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>

</body>
</html>
