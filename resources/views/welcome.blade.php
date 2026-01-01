<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>Aplikasi Manajemen Pesantren Modern Terlengkap & Aman #1 | Santrix</title>
    <meta name="description" content="Santrix adalah software manajemen pesantren berbasis web. Kelola data santri, SPP, keuangan, dan akademik secara otomatis. Aman, modern, dan terintegrasi WhatsApp Gateway. Coba Gratis!">
    <meta name="keywords" content="aplikasi pesantren, sistem informasi pesantren, software administrasi pesantren, aplikasi keuangan pesantren, data santri online">
    <meta name="author" content="Velora">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://santrix.my.id/">
    <meta property="og:title" content="Aplikasi Manajemen Pesantren Modern Terlengkap | Santrix">
    <meta property="og:description" content="Kelola pesantren jadi lebih mudah dengan Santrix. Sistem SPP, Tabungan, dan Akademik dalam satu aplikasi terintegrasi.">
    <meta property="og:image" content="{{ asset('images/seo-og-image.jpg') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://santrix.my.id/">
    <meta property="twitter:title" content="Aplikasi Manajemen Pesantren Modern Terlengkap | Santrix">
    <meta property="twitter:description" content="Kelola pesantren jadi lebih mudah dengan Santrix. Sistem SPP, Tabungan, dan Akademik dalam satu aplikasi terintegrasi.">
    <meta property="twitter:image" content="{{ asset('images/seo-og-image.jpg') }}">

    <!-- Canonical -->
    <link rel="canonical" href="https://santrix.my.id/">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    <!-- Schema Markup -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "SoftwareApplication",
      "name": "Santrix",
      "operatingSystem": "Web-based",
      "applicationCategory": "BusinessApplication",
      "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.8",
        "ratingCount": "120"
      },
      "offers": {
        "@type": "Offer",
        "price": "750000",
        "priceCurrency": "IDR"
      },
      "author": {
        "@type": "Organization",
        "name": "Velora",
        "url": "https://santrix.my.id"
      }
    }
    </script>

    <!-- Tailwind CSS (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .gradient-text {
            background: linear-gradient(135deg, #1e293b 0%, #4f46e5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .blob {
            position: absolute;
            background: linear-gradient(180deg, rgba(79, 70, 229, 0.1) 0%, rgba(236, 72, 153, 0.1) 100%);
            filter: blur(80px);
            z-index: -1;
            border-radius: 50%;
        }
    </style>
</head>
<body class="bg-white text-slate-800 antialiased">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 transition-all duration-300 bg-white/90 backdrop-blur-md border-b border-slate-100" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo Text -->
                <a href="#" class="flex flex-col">
                    <span class="text-2xl font-bold text-slate-900 tracking-tight leading-none">
                        SANTRIX
                    </span>
                    <span class="text-[10px] font-medium text-slate-500 tracking-wider">
                        by Velora
                    </span>
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#home" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">Beranda</a>
                    <a href="#features" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">Fitur</a>
                    <a href="#pricing" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">Harga</a>
                    <a href="#contact" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">Kontak</a>
                </div>

                <!-- CTA Button -->
                <div class="hidden md:flex items-center gap-4">
                    <a href="#pricing" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-full transition-all shadow-lg hover:shadow-indigo-500/30 transform hover:-translate-y-0.5">
                        Daftar Sekarang
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-slate-600 hover:text-indigo-600 focus:outline-none">
                        <i data-feather="menu" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-b border-slate-100 shadow-xl">
            <div class="px-4 pt-2 pb-6 space-y-2">
                <a href="#home" class="block px-3 py-2 text-base font-medium text-slate-600 hover:text-indigo-600 hover:bg-slate-50 rounded-md">Beranda</a>
                <a href="#features" class="block px-3 py-2 text-base font-medium text-slate-600 hover:text-indigo-600 hover:bg-slate-50 rounded-md">Fitur</a>
                <a href="#pricing" class="block px-3 py-2 text-base font-medium text-slate-600 hover:text-indigo-600 hover:bg-slate-50 rounded-md">Harga</a>
                <a href="#contact" class="block px-3 py-2 text-base font-medium text-slate-600 hover:text-indigo-600 hover:bg-slate-50 rounded-md">Kontak</a>
                <div class="pt-4 mt-4 border-t border-slate-100">
                    <a href="#pricing" class="block w-full text-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700">
                        Daftar Sekarang
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <!-- Blobs -->
        <div class="blob w-96 h-96 top-0 left-0 -translate-x-1/2 -translate-y-1/2 opacity-50"></div>
        <div class="blob w-[500px] h-[500px] bottom-0 right-0 translate-x-1/3 translate-y-1/3 opacity-30 bg-pink-100"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <span class="inline-block py-1.5 px-4 rounded-full bg-indigo-50 text-indigo-600 text-xs font-bold tracking-wide uppercase mb-6 ring-1 ring-indigo-100">
                ✨ Solusi Pesantren Digital v2.0
            </span>
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tight mb-8 leading-tight">
                Kelola Pesantren <br>
                <span class="gradient-text">Jadi Lebih Modern</span>
            </h1>
            <p class="mt-4 text-xl text-slate-500 max-w-2xl mx-auto mb-10 leading-relaxed">
                Platform all-in-one untuk manajemen Keuangan SPP, Akademik, dan Laporan Yayasan. Terintegrasi dengan WhatsApp Gateway.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('register.tenant') }}" class="w-full sm:w-auto px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white text-lg font-bold rounded-full shadow-xl hover:shadow-indigo-500/40 transition-all transform hover:-translate-y-1">
                    Mulai Gratis Sekarang
                </a>
                <a href="#" class="w-full sm:w-auto px-8 py-4 bg-white hover:bg-slate-50 text-slate-700 text-lg font-bold rounded-full border border-slate-200 shadow-sm hover:shadow-md transition-all flex items-center justify-center gap-2 group">
                    <i data-feather="play-circle" class="w-5 h-5 text-indigo-600 group-hover:scale-110 transition-transform"></i>
                    Lihat Demo
                </a>
            </div>

            <!-- Stats (Dynamic Data) -->
            <div class="mt-16 grid grid-cols-2 lg:grid-cols-3 gap-8 max-w-4xl mx-auto border-t border-slate-100 pt-10">
                <div class="text-center">
                    <div class="text-3xl font-bold text-slate-800">{{ number_format($stats['pesantren']) }}+</div>
                    <div class="text-sm text-slate-500 font-medium">Pesantren Bergabung</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-slate-800">{{ number_format($stats['santri']) }}+</div>
                    <div class="text-sm text-slate-500 font-medium">Santri Dikelola</div>
                </div>
                <div class="text-center col-span-2 lg:col-span-1">
                    <div class="text-3xl font-bold text-slate-800">99.9%</div>
                    <div class="text-sm text-slate-500 font-medium">Uptime Server</div>
                </div>
            </div>

            <!-- Dashboard Preview -->
            <div class="mt-16 relative mx-auto max-w-5xl">
                <div class="relative rounded-2xl bg-slate-900/5 p-2 lg:p-4 ring-1 ring-inset ring-slate-900/10">
                    <div class="bg-white rounded-xl shadow-2xl overflow-hidden aspect-[16/9] flex items-center justify-center bg-slate-50">
                         <!-- Replace with actual Dashboard Screenshot if available -->
                        <img src="https://via.placeholder.com/1200x675/f1f5f9/94a3b8?text=Dashboard+SANTRIX+V2" alt="Dashboard App" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-indigo-600 font-bold tracking-wide uppercase text-sm">Fitur Unggulan</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 mb-4 text-slate-900">Semua Kebutuhan Pesantren dalam Satu Aplikasi</h2>
                <p class="text-slate-500 text-lg">Santrix dirancang khusus untuk memahami alur kompleks administrasi pesantren salaf maupun modern.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="p-8 rounded-3xl bg-slate-50 hover:bg-white hover:shadow-xl transition-all duration-300 border border-transparent hover:border-slate-100 group">
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i data-feather="dollar-sign" class="text-green-500 w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-900">Keuangan & SPP</h3>
                    <p class="text-slate-500 leading-relaxed">Kelola tagihan Syahriah otomatis. Notifikasi tagihan langsung terkirim ke WhatsApp wali santri.</p>
                </div>

                <!-- Card 2 -->
                <div class="p-8 rounded-3xl bg-slate-50 hover:bg-white hover:shadow-xl transition-all duration-300 border border-transparent hover:border-slate-100 group">
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i data-feather="book-open" class="text-blue-500 w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-900">Akademik & Rapor</h3>
                    <p class="text-slate-500 leading-relaxed">Input nilai hafalan (Talaran) dan kitab kuning. Cetak rapor standar Diknas atau Pondok.</p>
                </div>

                <!-- Card 3 -->
                <div class="p-8 rounded-3xl bg-slate-50 hover:bg-white hover:shadow-xl transition-all duration-300 border border-transparent hover:border-slate-100 group">
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i data-feather="smartphone" class="text-purple-500 w-7 h-7"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-900">Portal Wali Santri</h3>
                    <p class="text-slate-500 leading-relaxed">Orang tua bisa pantau hafalan, absensi, dan riwayat pembayaran SPP dari HP masing-masing.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section (Dynamic Data) -->
    <section id="pricing" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-indigo-600 font-bold tracking-wide uppercase text-sm">Biaya Langganan</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 mb-4 text-slate-900">Investasi Terbaik untuk Pesantren</h2>
                <p class="text-slate-500 text-lg">Harga transparan, sesuai dengan database billing sistem kami.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-7xl mx-auto">
                @foreach($plans as $plan)
                <div class="relative bg-white rounded-3xl p-8 border {{ $plan['is_featured'] ? 'border-indigo-600 shadow-2xl scale-105 z-10' : 'border-slate-100 shadow-lg' }} flex flex-col h-full transition-transform hover:-translate-y-2">
                    
                    @if($plan['is_featured'])
                    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                        <span class="bg-indigo-600 text-white text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wide">Paling Laris</span>
                    </div>
                    @endif

                    <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $plan['name'] }}</h3>
                    <div class="flex items-baseline gap-1 mb-6">
                        @if(isset($plan['formatted_price']))
                            <span class="text-4xl font-extrabold text-slate-900">{{ $plan['formatted_price'] }}</span>
                        @else
                            <span class="text-4xl font-extrabold text-slate-900">Rp {{ number_format($plan['price']/1000) }}rb</span>
                        @endif
                        <span class="text-slate-500 font-medium">/ {{ $plan['period'] ?? 'bulan' }}</span>
                    </div>
                    
                    <p class="text-slate-500 mb-8 text-sm">{{ $plan['description'] }}</p>

                    <ul class="space-y-4 mb-8 flex-1">
                        @foreach($plan['features'] as $feature)
                        <li class="flex items-start gap-3 text-sm {{ $feature['included'] ? 'text-slate-700' : 'text-slate-400 line-through' }}">
                            @if($feature['included'])
                                <i data-feather="check-circle" class="w-5 h-5 text-indigo-600 shrink-0"></i>
                            @else
                                <i data-feather="x-circle" class="w-5 h-5 text-slate-300 shrink-0"></i>
                            @endif
                            {{ $feature['name'] }}
                        </li>
                        @endforeach
                    </ul>

                    <a href="{{ route('register.tenant', ['package' => $plan['id']]) }}" class="w-full py-4 rounded-xl font-bold text-center transition-all {{ $plan['is_featured'] ? 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg hover:shadow-indigo-500/30' : 'bg-slate-50 text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                        {{ $plan['button_text'] }}
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-2">
                    <a href="#" class="flex flex-col mb-6">
                        <span class="text-2xl font-bold text-white tracking-tight leading-none">
                            SANTRIX
                        </span>
                        <span class="text-[10px] font-medium text-slate-400 tracking-wider">
                            by Velora
                        </span>
                    </a>
                    <p class="text-slate-400 leading-relaxed max-w-sm">
                        Platform manajemen pesantren modern yang membantu digitalisasi administrasi pendidikan Islam di Indonesia.
                    </p>
                </div>
                
                <div>
                    <h4 class="text-white font-bold mb-6">Produk</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#features" class="hover:text-white transition-colors">Fitur</a></li>
                        <li><a href="#pricing" class="hover:text-white transition-colors">Harga</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Tutorial</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Roadmap</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-6">Legal</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-800 text-center text-sm text-slate-500">
                &copy; {{ date('Y') }} SANTRIX by Velora. All rights reserved. Made with ❤️ for Pesantren Indonesia.
            </div>
        </div>
    </footer>

    <script>
        feather.replace();

        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>
