<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>SANTRIX - Sistem Manajemen Pesantren Modern | by Velora</title>
    <meta name="description" content="Santrix adalah platform SaaS manajemen pesantren modern. Kelola data santri, SPP, keuangan, dan akademik secara otomatis. Aman, modern, dan terintegrasi.">
    <meta name="keywords" content="aplikasi pesantren, sistem informasi pesantren, software administrasi pesantren, aplikasi keuangan pesantren">
    <meta name="author" content="Velora">
    <meta name="robots" content="index, follow">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://santrix.my.id/">
    <meta property="og:title" content="SANTRIX - Sistem Manajemen Pesantren Modern">
    <meta property="og:description" content="Platform SaaS untuk mengelola administrasi pesantren dengan mudah dan modern.">

    <!-- Canonical -->
    <link rel="canonical" href="https://santrix.my.id/">

    <!-- Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">


    <!-- Tailwind via Vite -->
    @vite(['resources/css/app.css'])

    <style>
        * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }
        .gradient-text {
            background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .glass {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.1);
            will-change: transform;
            transform: translate3d(0, 0, 0);
        }
        /* Optimize scroll performance */
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        html {
            scroll-behavior: smooth;
        }
        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            * { animation-duration: 0.01ms !important; transition-duration: 0.01ms !important; }
        }
    </style>
</head>
<body class="bg-slate-950 text-white antialiased" x-data="{ mobileMenu: false }">

    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 glass transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="/" class="flex flex-col">
                    <span class="text-2xl font-black text-white tracking-tight">SANTRIX</span>
                    <span class="text-[10px] text-slate-400 tracking-widest">by Velora</span>
                </a>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#features" class="text-sm font-medium text-slate-300 hover:text-cyan-400 transition-colors">Fitur</a>
                    <a href="#pricing" class="text-sm font-medium text-slate-300 hover:text-cyan-400 transition-colors">Harga</a>
                    <a href="#testimonials" class="text-sm font-medium text-slate-300 hover:text-cyan-400 transition-colors">Testimoni</a>
                    <a href="#contact" class="text-sm font-medium text-slate-300 hover:text-cyan-400 transition-colors">Kontak</a>
                </div>

                <!-- CTA -->
                <div class="hidden md:flex items-center gap-4">

                    <a href="{{ route('register.tenant') }}" class="px-6 py-2.5 bg-linear-to-r from-cyan-500 to-blue-500 text-white text-sm font-semibold rounded-lg shadow-lg shadow-cyan-500/25 hover:shadow-cyan-500/40 transition-all transform hover:-translate-y-0.5">
                        Daftar Pesantren
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenu = !mobileMenu" class="md:hidden text-white p-2">
                    <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenu" x-cloak class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenu" x-cloak x-transition class="md:hidden glass border-t border-white/10">
            <div class="px-4 py-6 space-y-3">
                <a href="#features" @click="mobileMenu = false" class="block px-4 py-3 text-slate-300 hover:bg-white/5 rounded-lg font-medium">Fitur</a>
                <a href="#pricing" @click="mobileMenu = false" class="block px-4 py-3 text-slate-300 hover:bg-white/5 rounded-lg font-medium">Harga</a>
                <a href="#testimonials" @click="mobileMenu = false" class="block px-4 py-3 text-slate-300 hover:bg-white/5 rounded-lg font-medium">Testimoni</a>
                <a href="#contact" @click="mobileMenu = false" class="block px-4 py-3 text-slate-300 hover:bg-white/5 rounded-lg font-medium">Kontak</a>
                <hr class="border-white/10 my-4">

                <a href="{{ route('register.tenant') }}" class="block px-4 py-3 bg-linear-to-r from-cyan-500 to-blue-500 text-white text-center font-semibold rounded-lg">
                    Daftar Pesantren
                </a>
            </div>
        </div>
    </nav>

    <!-- Global Flash Message -->
    @if(session('success') || session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
        class="fixed top-24 right-4 z-50 max-w-sm w-full shadow-lg rounded-xl overflow-hidden transform transition-all duration-300"
        x-transition:enter="translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave="translate-x-full opacity-0">
        
        @if(session('success'))
        <div class="bg-emerald-500 border-l-4 border-emerald-700 p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-white mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <div class="flex-1">
                <h3 class="text-white font-bold text-sm">Berhasil!</h3>
                <p class="text-white/90 text-sm mt-1">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-white/70 hover:text-white"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-500 border-l-4 border-red-800 p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-white mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            <div class="flex-1">
                <h3 class="text-white font-bold text-sm">Gagal!</h3>
                <p class="text-white/90 text-sm mt-1">{{ session('error') }}</p>
            </div>
            <button @click="show = false" class="text-white/70 hover:text-white"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </div>
        @endif
    </div>
    @endif

    <!-- Hero Section -->
    <section id="hero" class="relative min-h-screen flex items-center pt-20 overflow-hidden">
        <!-- Background -->
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-linear-to-br from-slate-900 via-slate-950 to-slate-900"></div>
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-cyan-500/10 rounded-full blur-[128px]"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-[128px]"></div>
            <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.15) 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center max-w-4xl mx-auto">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 border border-white/10 rounded-full text-sm text-slate-300 mb-8" data-aos="fade-up">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute h-2 w-2 rounded-full bg-cyan-400 opacity-75"></span>
                        <span class="h-2 w-2 rounded-full bg-cyan-500"></span>
                    </span>
                    Dipercaya {{ number_format($stats['pesantren']) }}+ pesantren
                </div>

                <!-- Headline -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white leading-tight mb-6" data-aos="fade-up" data-aos-delay="100">
                    Sistem Manajemen Pesantren
                    <span class="block gradient-text">Modern & Terintegrasi</span>
                </h1>

                <p class="text-lg sm:text-xl text-slate-400 max-w-2xl mx-auto mb-10 leading-relaxed" data-aos="fade-up" data-aos-delay="200">
                    Platform SaaS untuk mengelola administrasi santri, keuangan, dan pendidikan pesantren dalam satu sistem yang powerful.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16" data-aos="fade-up" data-aos-delay="300">
                    <a href="{{ route('register.tenant') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 bg-linear-to-r from-cyan-500 to-blue-500 text-white font-bold rounded-xl shadow-xl shadow-cyan-500/25 hover:shadow-cyan-500/40 transition-all transform hover:-translate-y-1">
                        Daftar Pesantren
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </a>

                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-6 max-w-lg mx-auto" data-aos="fade-up" data-aos-delay="400">
                    <div class="text-center p-4 bg-white/5 rounded-xl border border-white/10 hover:bg-white/10 transition-colors">
                        <div class="text-2xl sm:text-3xl font-black text-white">{{ number_format($stats['pesantren']) }}+</div>
                        <div class="text-sm text-slate-400">Pesantren</div>
                    </div>
                    <div class="text-center p-4 bg-white/5 rounded-xl border border-white/10 hover:bg-white/10 transition-colors">
                        <div class="text-2xl sm:text-3xl font-black text-white">{{ $stats['santri'] >= 1000 ? number_format($stats['santri']/1000, 0) . 'K' : number_format($stats['santri']) }}+</div>
                        <div class="text-sm text-slate-400">Santri</div>
                    </div>
                    <div class="text-center p-4 bg-white/5 rounded-xl border border-white/10 hover:bg-white/10 transition-colors">
                        <div class="text-2xl sm:text-3xl font-black text-white">{{ number_format($stats['users']) }}+</div>
                        <div class="text-sm text-slate-400">Pengguna</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <div class="w-6 h-10 border-2 border-white/20 rounded-full flex justify-center pt-2">
                <div class="w-1 h-2 bg-cyan-400 rounded-full"></div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <span class="inline-block px-4 py-1.5 bg-cyan-500/10 text-cyan-400 text-sm font-medium rounded-full border border-cyan-500/20 mb-4">
                    Fitur Modular
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-white mb-4">
                    Tiga Modul Terintegrasi
                </h2>
                <p class="text-lg text-slate-400">
                    Akses terpisah untuk setiap divisi. Lebih fokus, lebih aman, lebih produktif.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <!-- Sekretaris -->
                <div class="group bg-slate-800/50 rounded-2xl p-8 border border-white/5 hover:border-cyan-500/30 transition-all" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-14 h-14 bg-linear-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center text-white mb-6 shadow-lg">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Sekretaris</h3>
                    <p class="text-slate-400 leading-relaxed mb-4">Kelola data santri, asrama, mutasi, perizinan, dan administrasi lengkap.</p>
                    <a href="{{ route('demo.start', ['type' => 'sekretaris']) }}" class="inline-flex items-center gap-2 text-cyan-400 font-medium group-hover:gap-3 transition-all">
                        Coba Demo <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </a>
                </div>

                <!-- Pendidikan -->
                <div class="group bg-slate-800/50 rounded-2xl p-8 border border-white/5 hover:border-cyan-500/30 transition-all" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-14 h-14 bg-linear-to-br from-cyan-500 to-teal-500 rounded-xl flex items-center justify-center text-white mb-6 shadow-lg">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Pendidikan</h3>
                    <p class="text-slate-400 leading-relaxed mb-4">Nilai, absensi, hafalan Al-Quran, rapor digital, dan jadwal pelajaran.</p>
                    <a href="{{ route('demo.start', ['type' => 'pendidikan']) }}" class="inline-flex items-center gap-2 text-cyan-400 font-medium group-hover:gap-3 transition-all">
                        Coba Demo <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </a>
                </div>

                <!-- Bendahara -->
                <div class="group bg-slate-800/50 rounded-2xl p-8 border border-white/5 hover:border-cyan-500/30 transition-all" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-14 h-14 bg-linear-to-br from-teal-500 to-emerald-500 rounded-xl flex items-center justify-center text-white mb-6 shadow-lg">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Bendahara</h3>
                    <p class="text-slate-400 leading-relaxed mb-4">Pembayaran SPP, pemasukan, pengeluaran, gaji, dan laporan keuangan.</p>
                    <a href="{{ route('demo.start', ['type' => 'bendahara']) }}" class="inline-flex items-center gap-2 text-cyan-400 font-medium group-hover:gap-3 transition-all">
                        Coba Demo <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-24 bg-slate-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <span class="inline-block px-4 py-1.5 bg-emerald-500/10 text-emerald-400 text-sm font-medium rounded-full border border-emerald-500/20 mb-4">
                    Harga Transparan
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-white mb-4">
                    Pilih Paket Sesuai Kebutuhan
                </h2>
                <p class="text-lg text-slate-400">
                    Investasi kecil untuk efisiensi besar dalam pengelolaan pesantren.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($packages as $index => $pkg)
                <div class="relative bg-slate-800/50 rounded-2xl overflow-hidden border {{ $index === 1 ? 'border-cyan-500 ring-1 ring-cyan-500/50' : 'border-white/10' }} hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                    @if($index === 1)
                    <div class="absolute top-0 left-0 right-0 bg-linear-to-r from-cyan-500 to-blue-500 text-white text-xs font-bold text-center py-1.5">
                        REKOMENDASI
                    </div>
                    @endif
                    <div class="p-6 {{ $index === 1 ? 'pt-10' : '' }}">
                        <h3 class="text-xl font-bold text-white mb-2">{{ $pkg->name }}</h3>
                        <div class="text-3xl font-black text-cyan-400 mb-1">
                            Rp {{ number_format($pkg->price, 0, ',', '.') }}
                        </div>
                        <div class="text-sm text-slate-400 mb-6">/ {{ $pkg->duration_months }} bulan</div>

                        <ul class="space-y-3 mb-6">
                            <li class="flex items-center gap-2 text-slate-300 text-sm">
                                <svg class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                {{ $pkg->max_santri ? 'Maks ' . number_format($pkg->max_santri) . ' Santri' : 'Unlimited Santri' }}
                            </li>
                            <li class="flex items-center gap-2 text-slate-300 text-sm">
                                <svg class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                {{ $pkg->max_users ? 'Maks ' . $pkg->max_users . ' User' : 'Unlimited User' }}
                            </li>
                            @php
                                $features = is_string($pkg->features) ? json_decode($pkg->features, true) : $pkg->features;
                            @endphp
                            @if(is_array($features))
                                @foreach(array_slice($features, 0, 3) as $feature)
                                <li class="flex items-center gap-2 text-slate-300 text-sm">
                                    <svg class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    {{ is_array($feature) ? ($feature['name'] ?? '') : $feature }}
                                </li>
                                @endforeach
                            @endif
                        </ul>

                        <a href="{{ route('register.tenant', ['package' => $pkg->slug]) }}" class="block w-full py-3 text-center font-bold rounded-xl transition-all {{ $index === 1 ? 'bg-linear-to-r from-cyan-500 to-blue-500 text-white shadow-lg shadow-cyan-500/25' : 'bg-white/5 text-white hover:bg-white/10 border border-white/10' }}">
                            Pilih Paket
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-24 bg-slate-900 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-block px-4 py-1.5 bg-amber-500/10 text-amber-400 text-sm font-medium rounded-full border border-amber-500/20 mb-4">
                    Testimoni
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-white mb-4">
                    Dipercaya Pesantren Nusantara
                </h2>
                <p class="text-lg text-slate-400">
                    Cerita sukses dari pesantren yang telah menggunakan Santrix.
                </p>
            </div>

            <!-- Infinite Carousel -->
            <div class="relative">
                <div class="carousel-container">
                    <div class="carousel-track">
                        @php
                            $testimonials = [
                                ['name' => 'Ust. Ahmad Fauzi', 'role' => 'Mudir PP. Riyadlul Huda', 'text' => 'Santrix sangat membantu mengelola data santri dan keuangan. Semuanya jadi lebih rapi dan terorganisir.', 'avatar' => 'AF'],
                                ['name' => 'Ust. Muhammad Rizki', 'role' => 'Kepala TU PP. Darul Ulum', 'text' => 'Fitur laporan otomatis menghemat waktu kami. Tidak perlu input manual setiap bulan lagi.', 'avatar' => 'MR'],
                                ['name' => 'Ustadzah Siti Aminah', 'role' => 'Bendahara PP. Nurul Hikmah', 'text' => 'Tracking pembayaran SPP jadi mudah. Wali santri bisa cek tagihan via WhatsApp secara real-time.', 'avatar' => 'SA'],
                                ['name' => 'Ust. Abdul Malik', 'role' => 'Direktur PP. Al-Ikhlas', 'text' => 'Dashboard yang intuitif memudahkan monitoring seluruh aktivitas pesantren dalam satu platform.', 'avatar' => 'AM'],
                                ['name' => 'Ustadzah Fatimah', 'role' => 'Sekretaris PP. Darul Falah', 'text' => 'Sistem mutasi santri sangat membantu tracking perpindahan dan kenaikan kelas secara otomatis.', 'avatar' => 'FA'],
                                ['name' => 'Ust. Hasan Basri', 'role' => 'Mudir PP. Nurul Iman', 'text' => 'Notifikasi WhatsApp otomatis sangat membantu komunikasi dengan wali santri. Efisien sekali!', 'avatar' => 'HB'],
                                ['name' => 'Ust. Ibrahim Khalil', 'role' => 'Bendahara PP. Al-Hidayah', 'text' => 'Integrasi Midtrans memudahkan pembayaran online. Wali santri tidak perlu datang langsung.', 'avatar' => 'IK'],
                                ['name' => 'Ustadzah Khadijah', 'role' => 'Kepala Madrasah PP. Raudhatul Jannah', 'text' => 'Fitur nilai dan raport digital sangat membantu evaluasi akademik santri setiap semester.', 'avatar' => 'KH'],
                                ['name' => 'Ust. Lukman Hakim', 'role' => 'Wakil Mudir PP. Darul Hikmah', 'text' => 'Multi-user access dengan role berbeda sangat membantu pembagian tugas tim administrasi.', 'avatar' => 'LH'],
                                ['name' => 'Ustadzah Maryam', 'role' => 'Pengurus Asrama PP. Al-Barokah', 'text' => 'Manajemen asrama dan kobong jadi lebih terstruktur. Data santri per kamar sangat detail.', 'avatar' => 'MA'],
                                ['name' => 'Ust. Nashir Ahmad', 'role' => 'Mudir PP. Darul Qur\'an', 'text' => 'Backup otomatis setiap hari membuat data kami aman. Tidak khawatir kehilangan data lagi.', 'avatar' => 'NA'],
                                ['name' => 'Ustadzah Aisyah', 'role' => 'Bendahara PP. Nurul Huda', 'text' => 'Laporan keuangan bulanan sangat lengkap. Transparansi keuangan pesantren meningkat drastis.', 'avatar' => 'AI'],
                                ['name' => 'Ust. Qasim Idris', 'role' => 'Kepala TU PP. Al-Furqon', 'text' => 'Export data ke Excel sangat membantu untuk laporan ke yayasan. Praktis dan cepat!', 'avatar' => 'QI'],
                                ['name' => 'Ustadzah Ruqayyah', 'role' => 'Sekretaris PP. Darul Aitam', 'text' => 'Cetak kartu santri dan surat-surat administrasi jadi lebih profesional dengan template yang disediakan.', 'avatar' => 'RU'],
                                ['name' => 'Ust. Salman Farisi', 'role' => 'Mudir PP. Al-Mubarok', 'text' => 'Support tim Santrix sangat responsif. Setiap kendala langsung dibantu dengan cepat dan ramah.', 'avatar' => 'SF'],
                            ];
                        @endphp
                        
                        <!-- Duplicate for infinite loop -->
                        @foreach(array_merge($testimonials, $testimonials) as $index => $t)
                        <div class="carousel-item">
                            <div class="bg-slate-800/50 rounded-2xl p-6 border border-white/5 h-full">
                                <div class="flex gap-1 mb-3">
                                    @for($i = 0; $i < 5; $i++)
                                    <svg class="w-4 h-4 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    @endfor
                                </div>
                                <p class="text-slate-300 mb-4 leading-relaxed text-sm">"{{ $t['text'] }}"</p>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-linear-to-br from-cyan-500 to-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xs">
                                        {{ $t['avatar'] }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-white text-sm">{{ $t['name'] }}</div>
                                        <div class="text-xs text-slate-400">{{ $t['role'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <style>
            .carousel-container {
                overflow: hidden;
                position: relative;
            }
            .carousel-track {
                display: flex;
                gap: 1.5rem;
                animation: scroll 60s linear infinite;
                width: max-content;
            }
            .carousel-track:hover {
                animation-play-state: paused;
            }
            .carousel-item {
                flex: 0 0 350px;
                max-width: 350px;
            }
            @keyframes scroll {
                0% {
                    transform: translateX(0);
                }
                100% {
                    transform: translateX(calc(-350px * 15 - 1.5rem * 15));
                }
            }
            @media (max-width: 640px) {
                .carousel-item {
                    flex: 0 0 280px;
                    max-width: 280px;
                }
                @keyframes scroll {
                    0% {
                        transform: translateX(0);
                    }
                    100% {
                        transform: translateX(calc(-280px * 15 - 1.5rem * 15));
                    }
                }
            }
        </style>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-linear-to-r from-cyan-600 to-blue-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center" data-aos="fade-up">
            <h2 class="text-3xl sm:text-4xl font-black text-white mb-6">
                Siap Modernisasi Pesantren Anda?
            </h2>
            <p class="text-xl text-white/90 mb-10 max-w-2xl mx-auto">
                Bergabung dengan ratusan pesantren yang telah merasakan kemudahan mengelola administrasi dengan SANTRIX.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#pricing" class="px-10 py-4 bg-white text-slate-900 font-bold rounded-xl shadow-xl hover:shadow-2xl transition-all transform hover:-translate-y-1 inline-flex items-center gap-2">
                    Daftar Sekarang
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
                <a href="https://wa.me/6281234567890" target="_blank" class="px-10 py-4 bg-white/10 backdrop-blur text-white font-bold rounded-xl border-2 border-white/30 hover:bg-white/20 transition-all">
                    Hubungi Sales
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="bg-slate-950 text-white pt-20 pb-8 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-2">
                    <div class="flex flex-col mb-6">
                        <span class="text-2xl font-black text-white">SANTRIX</span>
                        <span class="text-xs text-slate-500 tracking-widest">by Velora</span>
                    </div>
                    <p class="text-slate-400 max-w-md mb-6 leading-relaxed">
                        Platform SaaS manajemen pesantren modern yang membantu ribuan pesantren di Indonesia mengelola administrasi dengan lebih efisien.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold mb-6 text-lg">Produk</h4>
                    <ul class="space-y-3 text-slate-400">
                        <li><a href="#features" class="hover:text-cyan-400 transition-colors">Fitur</a></li>
                        <li><a href="#pricing" class="hover:text-cyan-400 transition-colors">Harga</a></li>
                        <li><a href="{{ route('demo.start', ['type' => 'sekretaris']) }}" class="hover:text-cyan-400 transition-colors">Demo</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-6 text-lg">Kontak</h4>
                    <ul class="space-y-3 text-slate-400">
                        <li>nawawimahinutsman@gmail.com</li>
                        <li>+62 821-2031-8007</li>
                        <li>Indonesia</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/5 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-slate-500 text-sm">© {{ date('Y') }} SANTRIX by Velora. All rights reserved.</p>
                <p class="text-slate-500 text-sm">Made with ❤️ for Pesantren Indonesia</p>
            </div>
        </div>
    </footer>

    <!-- AlpineJS -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>
