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
    </style>
</head>
<body class="bg-gray-50 antialiased text-gray-900">

<div class="min-h-screen bg-white flex"
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
                "Kelola pesantren dengan amanah, transparan, dan modern sesuai kaidah syariah."
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
    <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-20 xl:px-24 overflow-y-auto h-screen bg-emerald-50/30">
        
        <div class="max-w-2xl w-full mx-auto">
            <!-- Mobile Header -->
            <div class="lg:hidden text-center mb-8">
                <h1 class="text-3xl font-bold text-emerald-900">Santrix</h1>
                <p class="mt-2 text-sm text-gray-600">Mulai Digitalisasi Pesantren Anda</p>
            </div>

            <!-- Header -->
            <div class="mb-10">
                <a href="/" class="group inline-flex items-center text-sm font-medium text-gray-500 hover:text-emerald-600 mb-6 transition-colors">
                    <i data-feather="arrow-left" class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform"></i>
                    Kembali ke Beranda
                </a>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-indigo-100/50 border border-white overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-0">
                    
                    <!-- Sidebar / Header Section -->
                    <div class="lg:col-span-4 bg-linear-to-br from-indigo-700 via-indigo-600 to-violet-700 text-white p-10 flex flex-col relative overflow-hidden">
                        
                        <!-- Decoration -->
                        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-32 -mt-32"></div>
                        <div class="absolute bottom-0 left-0 w-48 h-48 bg-indigo-900/20 rounded-full blur-2xl -ml-24 -mb-24"></div>

                        <div class="relative z-10 flex-1">
                            <div class="flex items-center gap-3 mb-8">
                                <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/20 backdrop-blur-md flex items-center justify-center shadow-inner">
                                    <i data-feather="package" class="w-6 h-6 text-indigo-100"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-medium text-indigo-200 uppercase tracking-widest">Paket Pilihan</span>
                                    <span class="font-bold text-lg" x-text="package ? package.replace('-', ' ').toUpperCase() : 'BELUM DIPILIH'"></span>
                                </div>
                            </div>

                            <h1 class="text-4xl font-extrabold mb-4 leading-tight">Mulai Digitalisasi Pesantren</h1>
                            <p class="text-indigo-100 text-lg leading-relaxed mb-8">Bergabunglah dengan ribuan pesantren modern lainnya. Kelola santri, keuangan, dan akademik dalam satu platform.</p>

                            <div class="space-y-4">
                                <div class="flex items-center gap-3 bg-white/5 p-3 rounded-xl border border-white/10 backdrop-blur-sm">
                                    <div class="p-1.5 rounded-full bg-emerald-500/20 text-emerald-300">
                                        <i data-feather="check" class="w-3 h-3"></i>
                                    </div>
                                    <span class="text-sm font-medium">Tanpa Kartu Kredit</span>
                                </div>
                                <div class="flex items-center gap-3 bg-white/5 p-3 rounded-xl border border-white/10 backdrop-blur-sm">
                                    <div class="p-1.5 rounded-full bg-emerald-500/20 text-emerald-300">
                                        <i data-feather="shield" class="w-3 h-3"></i>
                                    </div>
                                    <span class="text-sm font-medium">Aman & Terenkripsi</span>
                                </div>
                                <div class="flex items-center gap-3 bg-white/5 p-3 rounded-xl border border-white/10 backdrop-blur-sm">
                                    <div class="p-1.5 rounded-full bg-emerald-500/20 text-emerald-300">
                                        <i data-feather="zap" class="w-3 h-3"></i>
                                    </div>
                                    <span class="text-sm font-medium">Setup Instan < 5 Menit</span>
                                </div>
                            </div>
                        </div>

                        <div class="relative z-10 mt-12 pt-8 border-t border-white/10">
                            <p class="text-xs text-indigo-200 text-center">Butuh bantuan? <a href="#" class="text-white hover:underline font-bold">Hubungi Support</a></p>
                        </div>
                    </div>

                    <!-- Form Section -->
                    <div class="lg:col-span-8 p-8 lg:p-12 bg-white">
                        
                        @if(session('error'))
                        <div class="bg-red-50 border border-red-100 text-red-600 px-5 py-4 rounded-xl mb-8 flex items-start gap-3 shadow-xs">
                            <i data-feather="alert-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
                            <span class="font-medium text-sm">{{ session('error') }}</span>
                        </div>
                        @endif

                        <form action="{{ route('register.tenant.store') }}" method="POST" class="space-y-10" autocomplete="off">
                            @csrf
                            
                            <!-- Section: Choose Package -->
                            <section>
                                <div class="flex items-center justify-between mb-6">
                                    <div>
                                        <h3 class="text-xl font-bold text-slate-900 group flex items-center gap-2">
                                            <span>Pilih Paket Langganan</span>
                                            <div class="h-px bg-slate-200 flex-1 ml-4 w-12 group-hover:w-20 transition-all"></div>
                                        </h3>
                                        <p class="text-sm text-slate-500 mt-1">Pilih paket yang sesuai dengan skala pesantren Anda.</p>
                                    </div>
                                </div>
                                
                                <input type="hidden" name="package" :value="package">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($plans as $plan)
                                    <div class="relative group cursor-pointer" @click="package = '{{ $plan['slug'] }}'">
                                        <div class="absolute inset-0 bg-linear-to-r from-indigo-500 to-violet-500 rounded-2xl opacity-0 group-hover:opacity-5 transition-opacity duration-300"></div>
                                        
                                        <div class="relative h-full border-2 rounded-2xl p-5 transition-all duration-300 flex flex-col gap-3"
                                            :class="package === '{{ $plan['slug'] }}' 
                                                ? 'border-indigo-600 bg-indigo-50/50 shadow-lg shadow-indigo-100 scale-[1.02]' 
                                                : 'border-slate-200 bg-white hover:border-indigo-300 hover:shadow-md'">
                                            
                                            <div class="flex justify-between items-start">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors"
                                                        :class="package === '{{ $plan['slug'] }}' ? 'border-indigo-600 bg-indigo-600 text-white' : 'border-slate-300 text-transparent'">
                                                        <i data-feather="check" class="w-3.5 h-3.5"></i>
                                                    </div>
                                                    <span class="font-bold text-slate-800 text-lg">{{ $plan['name'] }}</span>
                                                </div>
                                                <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full border" 
                                                    :class="package === '{{ $plan['slug'] }}' ? 'bg-indigo-100 text-indigo-700 border-indigo-200' : 'bg-slate-100 text-slate-500 border-slate-200'">
                                                    {{ $plan['duration_months'] }} Bulan
                                                </span>
                                            </div>

                                            <div class="pl-9">
                                                <div class="flex items-baseline gap-1">
                                                    <span class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $plan['formatted_price'] }}</span>
                                                </div>
                                                <p class="text-xs text-slate-500 mt-2 leading-relaxed">{{ Str::limit($plan['description'], 80) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @error('package') 
                                    <div class="mt-2 flex items-center gap-2 text-red-600 text-xs font-semibold animate-pulse">
                                        <i data-feather="alert-octagon" class="w-3 h-3"></i> {{ $message }}
                                    </div> 
                                @enderror
                            </section>

                            <!-- Section: Data Pesantren -->
                            <section>
                                <div class="flex items-center justify-between mb-6">
                                    <div>
                                        <h3 class="text-xl font-bold text-slate-900">Identitas Pesantren</h3>
                                        <p class="text-sm text-slate-500 mt-1">Data ini akan menjadi identitas utama aplikasi Anda.</p>
                                    </div>
                                </div>

                                <div class="grid gap-6">
                                    <!-- Floating Label Input -->
                                    <div class="relative group">
                                        <input type="text" name="nama_pesantren" id="nama_pesantren" value="{{ old('nama_pesantren') }}" 
                                            class="peer block w-full px-4 pt-6 pb-2 border-2 border-slate-200 rounded-xl bg-slate-50/50 text-slate-900 focus:outline-hidden focus:border-indigo-600 focus:ring-0 placeholder-transparent transition-all" 
                                            placeholder="Nama Pesantren" required>
                                        <label for="nama_pesantren" 
                                            class="absolute left-4 top-4 text-slate-500 text-sm transition-all 
                                            peer-placeholder-shown:text-base peer-placeholder-shown:text-slate-400 peer-placeholder-shown:top-4 
                                            peer-focus:top-1.5 peer-focus:text-xs peer-focus:text-indigo-600 peer-focus:font-semibold
                                            peer-not-placeholder-shown:top-1.5 peer-not-placeholder-shown:text-xs peer-not-placeholder-shown:text-slate-500">
                                            Nama Pesantren
                                        </label>
                                        @error('nama_pesantren') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="relative group">
                                        <label class="block text-xs font-semibold text-slate-500 mb-1 ml-1">Domain Akses Aplikasi</label>
                                        <div class="flex items-stretch rounded-xl border-2 border-slate-200 overflow-hidden focus-within:border-indigo-600 focus-within:ring-4 focus-within:ring-indigo-500/10 transition-all">
                                            <input type="text" name="subdomain" value="{{ old('subdomain') }}" 
                                                class="flex-1 px-4 py-3 bg-slate-50/50 border-none focus:ring-0 placeholder-slate-400" 
                                                placeholder="namapesantren" pattern="[a-z0-9-]+" required>
                                            <div class="bg-slate-100 border-l px-4 flex items-center text-slate-500 text-sm font-medium">
                                                .santrix.my.id
                                            </div>
                                        </div>
                                        <p class="text-[10px] text-slate-400 mt-1.5 ml-1">*Hanya huruf kecil, angka, dan strip (-). Contoh: darul-ulum</p>
                                        @error('subdomain') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </section>

                            <!-- Section: Data Pemilik -->
                            <section>
                                <div class="flex items-center justify-between mb-6">
                                    <div>
                                        <h3 class="text-xl font-bold text-slate-900">Akun Administrator</h3>
                                        <p class="text-sm text-slate-500 mt-1">Akun ini akan memiliki akses penuh (Super Admin).</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="relative">
                                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                            class="peer block w-full px-4 pt-6 pb-2 border-2 border-slate-200 rounded-xl bg-slate-50/50 text-slate-900 focus:outline-hidden focus:border-indigo-600 focus:ring-0 placeholder-transparent" 
                                            placeholder="Nama Lengkap" required>
                                        <label for="name" class="absolute left-4 top-4 text-slate-500 text-sm transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-slate-400 peer-placeholder-shown:top-4 peer-focus:top-1.5 peer-focus:text-xs peer-focus:text-indigo-600 peer-focus:font-semibold peer-not-placeholder-shown:top-1.5 peer-not-placeholder-shown:text-xs">Nama Lengkap</label>
                                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="relative">
                                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" 
                                            class="peer block w-full px-4 pt-6 pb-2 border-2 border-slate-200 rounded-xl bg-slate-50/50 text-slate-900 focus:outline-hidden focus:border-indigo-600 focus:ring-0 placeholder-transparent" 
                                            placeholder="No. WhatsApp" required>
                                        <label for="phone" class="absolute left-4 top-4 text-slate-500 text-sm transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-slate-400 peer-placeholder-shown:top-4 peer-focus:top-1.5 peer-focus:text-xs peer-focus:text-indigo-600 peer-focus:font-semibold peer-not-placeholder-shown:top-1.5 peer-not-placeholder-shown:text-xs">No. WhatsApp</label>
                                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="md:col-span-2 relative">
                                        <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                            class="peer block w-full px-4 pt-6 pb-2 border-2 border-slate-200 rounded-xl bg-slate-50/50 text-slate-900 focus:outline-hidden focus:border-indigo-600 focus:ring-0 placeholder-transparent" 
                                            placeholder="Alamat Email" required>
                                        <label for="email" class="absolute left-4 top-4 text-slate-500 text-sm transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-slate-400 peer-placeholder-shown:top-4 peer-focus:top-1.5 peer-focus:text-xs peer-focus:text-indigo-600 peer-focus:font-semibold peer-not-placeholder-shown:top-1.5 peer-not-placeholder-shown:text-xs">Alamat Email</label>
                                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="relative">
                                        <input type="password" name="password" id="password" 
                                            class="peer block w-full px-4 pt-6 pb-2 border-2 border-slate-200 rounded-xl bg-slate-50/50 text-slate-900 focus:outline-hidden focus:border-indigo-600 focus:ring-0 placeholder-transparent pr-10" 
                                            placeholder="Password" required autocomplete="new-password">
                                        <label for="password" class="absolute left-4 top-4 text-slate-500 text-sm transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-slate-400 peer-placeholder-shown:top-4 peer-focus:top-1.5 peer-focus:text-xs peer-focus:text-indigo-600 peer-focus:font-semibold peer-not-placeholder-shown:top-1.5 peer-not-placeholder-shown:text-xs">Password</label>
                                        <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-4 text-slate-400 hover:text-indigo-600">
                                            <i data-feather="eye" class="w-4 h-4"></i>
                                        </button>
                                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="relative">
                                        <input type="password" name="password_confirmation" id="password_confirmation" 
                                            class="peer block w-full px-4 pt-6 pb-2 border-2 border-slate-200 rounded-xl bg-slate-50/50 text-slate-900 focus:outline-hidden focus:border-indigo-600 focus:ring-0 placeholder-transparent pr-10" 
                                            placeholder="Konfirmasi Password" required>
                                        <label for="password_confirmation" class="absolute left-4 top-4 text-slate-500 text-sm transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-slate-400 peer-placeholder-shown:top-4 peer-focus:top-1.5 peer-focus:text-xs peer-focus:text-indigo-600 peer-focus:font-semibold peer-not-placeholder-shown:top-1.5 peer-not-placeholder-shown:text-xs">Ulangi Password</label>
                                        <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-4 text-slate-400 hover:text-indigo-600">
                                            <i data-feather="eye" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </section>

                            <!-- Section: Bank Details (Conditional) -->
                            <div x-show="showBankDetails()" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="rounded-2xl border-2 border-amber-200 bg-amber-50/50 p-6 overflow-hidden relative">
                                <!-- Background Icon -->
                                <i data-feather="credit-card" class="absolute -right-6 -bottom-6 w-32 h-32 text-amber-500/10 rotate-12"></i>
                                
                                <div class="relative z-10">
                                    <div class="flex items-center gap-3 mb-6">
                                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center border border-amber-200">
                                            <i data-feather="dollar-sign" class="w-5 h-5"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-amber-900">Rekening Pencairan Dana</h3>
                                            <p class="text-xs text-amber-700 font-medium">Diperlukan untuk fitur withdrawal paket Muharam</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="relative bg-white/60 rounded-xl p-1">
                                            <input type="text" name="bank_name" placeholder="Nama Bank" class="w-full px-4 py-2 bg-transparent border-0 border-b-2 border-amber-200 focus:border-amber-500 focus:ring-0 placeholder-amber-400/70 text-amber-900 font-medium transition-colors" :required="showBankDetails()">
                                        </div>
                                        <div class="relative bg-white/60 rounded-xl p-1">
                                            <input type="text" name="bank_account_number" placeholder="No. Rekening" class="w-full px-4 py-2 bg-transparent border-0 border-b-2 border-amber-200 focus:border-amber-500 focus:ring-0 placeholder-amber-400/70 text-amber-900 font-medium transition-colors" :required="showBankDetails()">
                                        </div>
                                        <div class="relative bg-white/60 rounded-xl p-1">
                                            <input type="text" name="bank_account_name" placeholder="Atas Nama" class="w-full px-4 py-2 bg-transparent border-0 border-b-2 border-amber-200 focus:border-amber-500 focus:ring-0 placeholder-amber-400/70 text-amber-900 font-medium transition-colors" :required="showBankDetails()">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="pt-6">
                                <button type="submit" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-xl shadow-indigo-200 transition-all duration-300 hover:-translate-y-1">
                                    <span class="absolute right-0 inset-y-0 flex items-center pr-4">
                                        <i data-feather="arrow-right" class="w-5 h-5 text-indigo-400 group-hover:text-white transition-colors"></i>
                                    </span>
                                    BUAT PESANTREN SEKARANG
                                </button>
                                <p class="text-center text-xs text-slate-400 mt-4">
                                    Dengan mendaftar, Anda menyetujui <a href="#" class="text-indigo-600 hover:text-indigo-500 hover:underline">Syarat & Ketentuan</a> kami.
                                </p>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <!-- Footer links -->
            <div class="flex justify-center gap-6 mt-8 text-sm text-slate-500">
                <a href="#" class="hover:text-indigo-600 transition-colors">Privacy Policy</a>
                <span>&bull;</span>
                <a href="#" class="hover:text-indigo-600 transition-colors">Terms of Service</a>
                <span>&bull;</span>
                <a href="#" class="hover:text-indigo-600 transition-colors">Help Center</a>
            </div>
        </div>
    </div>

</html>
