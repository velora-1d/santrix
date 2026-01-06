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
    <!-- JSON-LD Removed temporarily to fix build error -->

    <!-- Tailwind CSS (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Outfit', sans-serif; overflow-x: hidden; }
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
            <div data-aos="fade-up" data-aos-duration="1000">
                <span class="inline-block py-1.5 px-4 rounded-full bg-indigo-50 text-indigo-600 text-xs font-bold tracking-wide uppercase mb-6 ring-1 ring-indigo-100">
                    ✨ Solusi Pesantren Digital v2.0
                </span>
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold tracking-tight mb-8 leading-tight text-slate-900">
                    Sistem Manajemen <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-violet-600">Pesantren Modern</span>
                </h1>
                <p class="mt-4 text-xl text-slate-500 max-w-2xl mx-auto mb-10 leading-relaxed">
                    Platform all-in-one untuk manajemen Keuangan SPP, Akademik, dan Laporan Yayasan. Terintegrasi dengan Payment Gateway & WhatsApp Gateway.
                </p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
                <a href="https://wa.me/6281320442174?text=Halo%20Admin%20Santrix,%20saya%20ingin%20konsultasi%20tentang%20aplikasi%20pesantren." target="_blank" class="w-full sm:w-auto px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white text-lg font-bold rounded-full shadow-xl hover:shadow-indigo-500/40 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                    <i data-feather="message-circle" class="w-5 h-5"></i>
                    Mulai Konsultasi
                </a>
                <a href="#features" class="w-full sm:w-auto px-8 py-4 bg-white hover:bg-slate-50 text-slate-700 text-lg font-bold rounded-full border border-slate-200 shadow-sm hover:shadow-md transition-all flex items-center justify-center gap-2 group">
                    <i data-feather="info" class="w-5 h-5 text-indigo-600 group-hover:scale-110 transition-transform"></i>
                    Pelajari Dulu
                </a>
            </div>

            <!-- Stats (Hardcoded) -->
            <div class="mt-16 grid grid-cols-2 lg:grid-cols-3 gap-8 max-w-4xl mx-auto border-t border-slate-100 pt-10" data-aos="fade-up" data-aos-delay="400">
                <div class="text-center">
                    <div class="text-3xl font-bold text-slate-800">120+</div>
                    <div class="text-sm text-slate-500 font-medium">Pesantren Bergabung</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-slate-800">15.000+</div>
                    <div class="text-sm text-slate-500 font-medium">Santri Dikelola</div>
                </div>
                <div class="text-center col-span-2 lg:col-span-1">
                    <div class="text-3xl font-bold text-slate-800">99.9%</div>
                    <div class="text-sm text-slate-500 font-medium">Uptime Server</div>
                </div>
            </div>
            
            <!-- Dashboard Preview Carousel -->
            <div class="mt-20 relative max-w-6xl mx-auto" data-aos="zoom-in-up" data-aos-duration="1000" data-aos-delay="300">
                <div style="position: absolute; inset: 0; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); filter: blur(60px); opacity: 0.15; z-index: -1; border-radius: 50%;"></div>
                
                <!-- Image Container -->
                <div style="background: white; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); overflow: hidden; border: 4px solid rgba(241, 245, 249, 0.8);">
                    
                    <!-- Carousel Images -->
                    <div id="carouselImages" style="position: relative; width: 100%; padding-bottom: 52%; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); overflow: hidden;">
                        
                        <!-- Slide 1: Admin -->
                        <div class="slide-image" data-index="0" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 1; transition: opacity 0.6s ease-in-out;">
                            <img src="{{ asset('images/preview-admin.png') }}" alt="Dashboard Admin" style="width: 100%; height: 100%; object-fit: cover; object-position: top center;">
                        </div>
                        
                        <!-- Slide 2: Bendahara -->
                        <div class="slide-image" data-index="1" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; transition: opacity 0.6s ease-in-out;">
                            <img src="{{ asset('images/preview-bendahara.png') }}" alt="Dashboard Bendahara" style="width: 100%; height: 100%; object-fit: cover; object-position: top center;">
                        </div>
                        
                        <!-- Slide 3: Sekretaris -->
                        <div class="slide-image" data-index="2" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; transition: opacity 0.6s ease-in-out;">
                            <img src="{{ asset('images/preview-sekretaris.png') }}" alt="Dashboard Sekretaris" style="width: 100%; height: 100%; object-fit: cover; object-position: top center;">
                        </div>
                        
                        <!-- Slide 4: Akademik -->
                        <div class="slide-image" data-index="3" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; transition: opacity 0.6s ease-in-out;">
                            <img src="{{ asset('images/preview-akademik.png') }}" alt="Rekap Nilai" style="width: 100%; height: 100%; object-fit: cover; object-position: top center;">
                        </div>
                        
                        <!-- Slide 5: Login -->
                        <div class="slide-image" data-index="4" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; transition: opacity 0.6s ease-in-out;">
                            <img src="{{ asset('images/preview-login.png') }}" alt="Login Page" style="width: 100%; height: 100%; object-fit: cover; object-position: center center;">
                        </div>

                        <!-- Navigation Arrows -->
                        <button id="prevBtn" style="position: absolute; top: 50%; left: 20px; transform: translateY(-50%); width: 56px; height: 56px; background: rgba(255,255,255,0.95); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; box-shadow: 0 10px 25px rgba(0,0,0,0.15); z-index: 10; transition: all 0.3s ease;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                        </button>
                        <button id="nextBtn" style="position: absolute; top: 50%; right: 20px; transform: translateY(-50%); width: 56px; height: 56px; background: rgba(255,255,255,0.95); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; box-shadow: 0 10px 25px rgba(0,0,0,0.15); z-index: 10; transition: all 0.3s ease;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </button>
                    </div>

                    <!-- Caption Card Below Image -->
                    <div style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%); padding: 32px 40px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 24px;">
                        
                        <!-- Text Content -->
                        <div style="flex: 1; min-width: 280px;">
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                                <div id="captionIcon" style="width: 44px; height: 44px; background: rgba(255,255,255,0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                                </div>
                                <h3 id="captionTitle" style="color: white; font-size: 1.5rem; font-weight: 700; margin: 0;">Dashboard Admin & Yayasan</h3>
                            </div>
                            <p id="captionDesc" style="color: rgba(199, 210, 254, 0.9); font-size: 1rem; margin: 0; line-height: 1.6; max-width: 600px;">Kontrol penuh sistem dengan manajemen user, backup data otomatis, dan monitoring aktivitas real-time.</p>
                        </div>
                        
                        <!-- Indicators -->
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <button class="dot-indicator" data-slide="0" style="width: 40px; height: 8px; border-radius: 10px; background: white; border: none; cursor: pointer; transition: all 0.3s ease;"></button>
                            <button class="dot-indicator" data-slide="1" style="width: 8px; height: 8px; border-radius: 50%; background: rgba(255,255,255,0.4); border: none; cursor: pointer; transition: all 0.3s ease;"></button>
                            <button class="dot-indicator" data-slide="2" style="width: 8px; height: 8px; border-radius: 50%; background: rgba(255,255,255,0.4); border: none; cursor: pointer; transition: all 0.3s ease;"></button>
                            <button class="dot-indicator" data-slide="3" style="width: 8px; height: 8px; border-radius: 50%; background: rgba(255,255,255,0.4); border: none; cursor: pointer; transition: all 0.3s ease;"></button>
                            <button class="dot-indicator" data-slide="4" style="width: 8px; height: 8px; border-radius: 50%; background: rgba(255,255,255,0.4); border: none; cursor: pointer; transition: all 0.3s ease;"></button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                (function() {
                    const slideData = [
                        { title: 'Dashboard Admin & Yayasan', desc: 'Kontrol penuh sistem dengan manajemen user, backup data otomatis, dan monitoring aktivitas real-time.' },
                        { title: 'Keuangan & SPP', desc: 'Transparansi arus kas, pemasukan SPP Syahriah, dan monitoring tagihan santri yang akurat.' },
                        { title: 'Database Santri Terpadu', desc: 'Kelola data diri, asrama, mutasi, dan administrasi kesantrian dalam satu pintu.' },
                        { title: 'Akademik & E-Rapor Digital', desc: 'Input nilai ujian, hitung rata-rata otomatis, dan cetak rapor santri dengan mudah.' },
                        { title: 'Sistem Akses Multi-Role', desc: 'Keamanan terjamin dengan pemisahan hak akses spesifik untuk setiap jabatan.' }
                    ];
                    
                    let current = 0;
                    let autoSlideTimer;
                    const totalSlides = slideData.length;
                    
                    const images = document.querySelectorAll('.slide-image');
                    const dots = document.querySelectorAll('.dot-indicator');
                    const titleEl = document.getElementById('captionTitle');
                    const descEl = document.getElementById('captionDesc');
                    const prevBtn = document.getElementById('prevBtn');
                    const nextBtn = document.getElementById('nextBtn');
                    
                    function updateCarousel() {
                        // Update images
                        images.forEach((img, i) => {
                            img.style.opacity = (i === current) ? '1' : '0';
                        });
                        
                        // Update dots
                        dots.forEach((dot, i) => {
                            if (i === current) {
                                dot.style.width = '40px';
                                dot.style.borderRadius = '10px';
                                dot.style.background = 'white';
                            } else {
                                dot.style.width = '8px';
                                dot.style.borderRadius = '50%';
                                dot.style.background = 'rgba(255,255,255,0.4)';
                            }
                        });
                        
                        // Update caption
                        titleEl.textContent = slideData[current].title;
                        descEl.textContent = slideData[current].desc;
                    }
                    
                    function goTo(index) {
                        current = (index + totalSlides) % totalSlides;
                        updateCarousel();
                        resetAutoSlide();
                    }
                    
                    function next() { goTo(current + 1); }
                    function prev() { goTo(current - 1); }
                    
                    function startAutoSlide() {
                        autoSlideTimer = setInterval(next, 5000);
                    }
                    
                    function resetAutoSlide() {
                        clearInterval(autoSlideTimer);
                        startAutoSlide();
                    }
                    
                    // Event listeners
                    prevBtn.addEventListener('click', prev);
                    nextBtn.addEventListener('click', next);
                    dots.forEach((dot, i) => {
                        dot.addEventListener('click', () => goTo(i));
                    });
                    
                    // Hover effects
                    prevBtn.addEventListener('mouseenter', () => { prevBtn.style.transform = 'translateY(-50%) scale(1.1)'; });
                    prevBtn.addEventListener('mouseleave', () => { prevBtn.style.transform = 'translateY(-50%) scale(1)'; });
                    nextBtn.addEventListener('mouseenter', () => { nextBtn.style.transform = 'translateY(-50%) scale(1.1)'; });
                    nextBtn.addEventListener('mouseleave', () => { nextBtn.style.transform = 'translateY(-50%) scale(1)'; });
                    
                    // Initialize
                    document.addEventListener('DOMContentLoaded', () => {
                        updateCarousel();
                        startAutoSlide();
                    });
                })();
            </script>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-white relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-96 h-96 bg-indigo-50 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/2 w-96 h-96 bg-violet-50 rounded-full blur-3xl opacity-50"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-4xl mx-auto mb-16" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-extrabold mb-4 text-slate-900 tracking-tight">
                    Dashboard Profesional
                </h2>
                <p class="text-slate-600 text-lg">
                    Akses terpisah untuk setiap divisi. Lebih fokus, lebih aman.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-6 lg:gap-8">
                
                <!-- 1. Dashboard Sekretaris (Tata Usaha) - Theme: Indigo -->
                <div class="bg-white rounded-2xl border border-indigo-100 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden group" data-aos="fade-up" data-aos-delay="100">
                    <div class="bg-indigo-50/50 p-6 border-b border-indigo-100 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-lg shadow-indigo-200">
                                <i data-feather="users" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Sekretaris & TU</h3>
                                <p class="text-indigo-700 text-sm font-medium">Administrasi & Data Santri</p>
                            </div>
                        </div>
                        <a href="{{ route('demo.start', ['type' => 'sekretaris']) }}" class="hidden sm:flex items-center gap-2 px-4 py-2 bg-white text-indigo-700 text-sm font-bold rounded-lg border border-indigo-200 hover:bg-indigo-50 transition-colors">
                            Masuk
                            <i data-feather="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-y-3 gap-x-4">
                            <!-- Items -->
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-indigo-700 transition-colors">
                                <i data-feather="monitor" class="w-4 h-4 text-slate-400 group-hover/item:text-indigo-600"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Statistik</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-indigo-700 transition-colors">
                                <i data-feather="database" class="w-4 h-4 text-slate-400 group-hover/item:text-indigo-600"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Data Induk</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-indigo-700 transition-colors">
                                <i data-feather="home" class="w-4 h-4 text-slate-400 group-hover/item:text-indigo-600"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Asrama</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-indigo-700 transition-colors">
                                <i data-feather="credit-card" class="w-4 h-4 text-slate-400 group-hover/item:text-indigo-600"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Kartu Digital</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-indigo-700 transition-colors">
                                <i data-feather="repeat" class="w-4 h-4 text-slate-400 group-hover/item:text-indigo-600"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Mutasi</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-indigo-700 transition-colors">
                                <i data-feather="trending-up" class="w-4 h-4 text-slate-400 group-hover/item:text-indigo-600"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Kenaikan Kelas</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-indigo-700 transition-colors">
                                <i data-feather="user-check" class="w-4 h-4 text-slate-400 group-hover/item:text-indigo-600"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Alumni</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-indigo-700 transition-colors">
                                <i data-feather="file-text" class="w-4 h-4 text-slate-400 group-hover/item:text-indigo-600"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Laporan</span>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-100 flex gap-3">
                            <a href="{{ route('demo.start', ['type' => 'sekretaris']) }}" class="flex-1 py-2 text-center text-xs font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 rounded hover:bg-indigo-100 transition-colors">Input Santri</a>
                            <a href="{{ route('demo.start', ['type' => 'sekretaris']) }}" class="flex-1 py-2 text-center text-xs font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 rounded hover:bg-indigo-100 transition-colors">Cek Kamar</a>
                        </div>
                    </div>
                </div>

                <!-- 2. Dashboard Bendahara (Keuangan) - Theme: Emerald (Green) -->
                <div class="bg-white rounded-2xl border border-emerald-100 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden group" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-emerald-50/50 p-6 border-b border-emerald-100 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-emerald-600 text-white flex items-center justify-center shadow-lg shadow-emerald-200">
                                <i data-feather="dollar-sign" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Bendahara</h3>
                                <p class="text-emerald-700 text-sm font-medium">Cashflow & SPP</p>
                            </div>
                        </div>
                        <a href="{{ route('demo.start', ['type' => 'bendahara']) }}" class="hidden sm:flex items-center gap-2 px-4 py-2 bg-white text-emerald-700 text-sm font-bold rounded-lg border border-emerald-200 hover:bg-emerald-50 transition-colors">
                            Masuk
                            <i data-feather="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-y-3 gap-x-4">
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-emerald-700 transition-colors">
                                <i data-feather="pie-chart" class="w-4 h-4 text-slate-400 group-hover/item:text-emerald-600"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Ringkasan</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-emerald-700 transition-colors">
                                <i data-feather="file-minus" class="w-4 h-4 text-slate-400 group-hover/item:text-emerald-600"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Tagihan SPP</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-emerald-700 transition-colors">
                                <i data-feather="arrow-down-circle" class="w-4 h-4 text-slate-400 group-hover/item:text-emerald-600"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Pemasukan</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-emerald-700 transition-colors">
                                <i data-feather="arrow-up-circle" class="w-4 h-4 text-slate-400 group-hover/item:text-emerald-600"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Pengeluaran</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-emerald-700 transition-colors">
                                <i data-feather="alert-circle" class="w-4 h-4 text-slate-400 group-hover/item:text-emerald-600"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Cek Tunggakan</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-emerald-700 transition-colors">
                                <i data-feather="briefcase" class="w-4 h-4 text-slate-400 group-hover/item:text-emerald-600"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Gaji Pegawai</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-emerald-700 transition-colors">
                                <i data-feather="message-circle" class="w-4 h-4 text-slate-400 group-hover/item:text-emerald-600"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Kirim WA</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-emerald-700 transition-colors">
                                <i data-feather="activity" class="w-4 h-4 text-slate-400 group-hover/item:text-emerald-600"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Arus Kas</span>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-100 flex gap-3">
                            <a href="{{ route('demo.start', ['type' => 'bendahara']) }}" class="flex-1 py-2 text-center text-xs font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 rounded hover:bg-emerald-100 transition-colors">Buat Tagihan</a>
                            <a href="{{ route('demo.start', ['type' => 'bendahara']) }}" class="flex-1 py-2 text-center text-xs font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 rounded hover:bg-emerald-100 transition-colors">Catat Bayar</a>
                        </div>
                    </div>
                </div>

                <!-- 3. Dashboard Pendidikan (Akademik) - Theme: Blue -->
                <div class="bg-white rounded-2xl border border-blue-100 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden group" data-aos="fade-up" data-aos-delay="300">
                    <div class="bg-blue-50/50 p-6 border-b border-blue-100 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-200">
                                <i data-feather="file-text" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Pendidikan</h3>
                                <p class="text-blue-700 text-sm font-medium">Kurikulum & Rapor</p>
                            </div>
                        </div>
                        <a href="{{ route('demo.start', ['type' => 'pendidikan']) }}" class="hidden sm:flex items-center gap-2 px-4 py-2 bg-white text-blue-700 text-sm font-bold rounded-lg border border-blue-200 hover:bg-blue-50 transition-colors">
                            Masuk
                            <i data-feather="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-y-3 gap-x-4">
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-blue-700 transition-colors">
                                <i data-feather="grid" class="w-4 h-4 text-slate-400 group-hover/item:text-blue-600"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Kelas</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-blue-700 transition-colors">
                                <i data-feather="calendar" class="w-4 h-4 text-slate-400 group-hover/item:text-blue-600"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Jadwal</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-blue-700 transition-colors">
                                <i data-feather="check-circle" class="w-4 h-4 text-slate-400 group-hover/item:text-blue-600"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Absensi Santri</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-blue-700 transition-colors">
                                <i data-feather="user-check" class="w-4 h-4 text-slate-400 group-hover/item:text-blue-600"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Absensi Guru</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-blue-700 transition-colors">
                                <i data-feather="edit-3" class="w-4 h-4 text-slate-400 group-hover/item:text-blue-600"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Jurnal KBM</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-blue-700 transition-colors">
                                <i data-feather="mic" class="w-4 h-4 text-slate-400 group-hover/item:text-blue-600"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Hafalan</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-blue-700 transition-colors">
                                <i data-feather="award" class="w-4 h-4 text-slate-400 group-hover/item:text-blue-600"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Nilai & Ujian</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-blue-700 transition-colors">
                                <i data-feather="printer" class="w-4 h-4 text-slate-400 group-hover/item:text-blue-600"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Cetak Rapor</span>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-100 flex gap-3">
                            <a href="{{ route('demo.start', ['type' => 'pendidikan']) }}" class="flex-1 py-2 text-center text-xs font-bold uppercase tracking-wider bg-blue-50 text-blue-700 rounded hover:bg-blue-100 transition-colors">Input Nilai</a>
                            <a href="{{ route('demo.start', ['type' => 'pendidikan']) }}" class="flex-1 py-2 text-center text-xs font-bold uppercase tracking-wider bg-blue-50 text-blue-700 rounded hover:bg-blue-100 transition-colors">Cetak Rapor</a>
                        </div>
                    </div>
                </div>

                <!-- 4. Dashboard Admin/Yayasan - Theme: Slate -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden group" data-aos="fade-up" data-aos-delay="400">
                    <div class="bg-slate-50 p-6 border-b border-slate-200 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-800 text-white flex items-center justify-center shadow-lg shadow-slate-200">
                                <i data-feather="shield" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Admin & Yayasan</h3>
                                <p class="text-slate-600 text-sm font-medium">Kontrol & Backup Data</p>
                            </div>
                        </div>
                        <a href="{{ route('demo.start', ['type' => 'admin']) }}" class="hidden sm:flex items-center gap-2 px-4 py-2 bg-white text-slate-700 text-sm font-bold rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">
                            Masuk
                            <i data-feather="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-y-3 gap-x-4">
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-slate-900 transition-colors">
                                <i data-feather="activity" class="w-4 h-4 text-slate-400 group-hover/item:text-slate-700"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Monitoring</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-slate-900 transition-colors">
                                <i data-feather="users" class="w-4 h-4 text-slate-400 group-hover/item:text-slate-700"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Manajemen User</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-slate-900 transition-colors">
                                <i data-feather="image" class="w-4 h-4 text-slate-400 group-hover/item:text-slate-700"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Branding</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-slate-900 transition-colors">
                                <i data-feather="download-cloud" class="w-4 h-4 text-slate-400 group-hover/item:text-slate-700"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Backup Data</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-slate-900 transition-colors">
                                <i data-feather="calendar" class="w-4 h-4 text-slate-400 group-hover/item:text-slate-700"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Tahun Ajaran</span>
                            </div>
                            <div class="flex items-center gap-2 group/item cursor-pointer hover:text-slate-900 transition-colors">
                                <i data-feather="smartphone" class="w-4 h-4 text-slate-400 group-hover/item:text-slate-700"></i>
                                <span class="text-sm font-semibold text-slate-700 group-hover/item:text-slate-900">Koneksi WA</span>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-100 flex gap-3">
                            <a href="{{ route('demo.start', ['type' => 'admin']) }}" class="flex-1 py-2 text-center text-xs font-bold uppercase tracking-wider bg-slate-100 text-slate-700 rounded hover:bg-slate-200 transition-colors">Download Backup</a>
                            <a href="{{ route('demo.start', ['type' => 'admin']) }}" class="flex-1 py-2 text-center text-xs font-bold uppercase tracking-wider bg-slate-100 text-slate-700 rounded hover:bg-slate-200 transition-colors">Tambah User</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-24 bg-slate-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold uppercase tracking-wide mb-4">
                    <i data-feather="message-circle" class="w-3 h-3"></i> Kata Mereka
                </span>
                <h2 class="text-3xl md:text-4xl font-bold mb-6 text-slate-900 leading-tight">
                    Dipercaya oleh <span class="text-indigo-600">Pesantren Modern</span>
                </h2>
                <p class="text-slate-500 text-lg">
                    Dengar langsung pengalaman para pengasuh dan pengelola pesantren yang telah beralih ke digital.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Testimoni 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition-shadow border border-slate-100" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xl">A</div>
                        <div>
                            <h4 class="font-bold text-slate-900">KH. Abdullah</h4>
                            <p class="text-xs text-slate-500">Pimp. Ponpes Al-Hidayah</p>
                        </div>
                    </div>
                    <p class="text-slate-600 italic">
                        "Alhamdulillah, sejak pakai Santrix, laporan keuangan jadi transparan. Wali santri juga senang ada notifikasi WA otomatis saat terima pembayaran."
                    </p>
                    <div class="flex text-amber-400 mt-4 gap-1">
                        <i data-feather="star" class="w-4 h-4 fill-current"></i>
                        <i data-feather="star" class="w-4 h-4 fill-current"></i>
                        <i data-feather="star" class="w-4 h-4 fill-current"></i>
                        <i data-feather="star" class="w-4 h-4 fill-current"></i>
                        <i data-feather="star" class="w-4 h-4 fill-current"></i>
                    </div>
                </div>

                <!-- Testimoni 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition-shadow border border-slate-100" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-full bg-violet-100 flex items-center justify-center text-violet-600 font-bold text-xl">S</div>
                        <div>
                            <h4 class="font-bold text-slate-900">Ust. Syamsul</h4>
                            <p class="text-xs text-slate-500">Kepala Bag. Kesantrian</p>
                        </div>
                    </div>
                    <p class="text-slate-600 italic">
                        "Fitur setor hafalan (Talaran) sangat membantu ustadz memantau perkembangan santri. Data tersimpan rapi per tahun ajaran. Mantap!"
                    </p>
                    <div class="flex text-amber-400 mt-4 gap-1">
                        <i data-feather="star" class="w-4 h-4 fill-current"></i>
                        <i data-feather="star" class="w-4 h-4 fill-current"></i>
                        <i data-feather="star" class="w-4 h-4 fill-current"></i>
                        <i data-feather="star" class="w-4 h-4 fill-current"></i>
                        <i data-feather="star" class="w-4 h-4 fill-current"></i>
                    </div>
                </div>

                <!-- Testimoni 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition-shadow border border-slate-100" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-full bg-pink-100 flex items-center justify-center text-pink-600 font-bold text-xl">L</div>
                        <div>
                            <h4 class="font-bold text-slate-900">Ibu Laila</h4>
                            <p class="text-xs text-slate-500">Admin Tata Usaha</p>
                        </div>
                    </div>
                    <p class="text-slate-600 italic">
                        "Support tim-nya responsif banget. Awalnya bingung migrasi data dari Excel, tapi dibantu sampai tuntas. Sekarang kerjaan TU jadi lebih cepat."
                    </p>
                    <div class="flex text-amber-400 mt-4 gap-1">
                        <i data-feather="star" class="w-4 h-4 fill-current"></i>
                        <i data-feather="star" class="w-4 h-4 fill-current"></i>
                        <i data-feather="star" class="w-4 h-4 fill-current"></i>
                        <i data-feather="star" class="w-4 h-4 fill-current"></i>
                        <i data-feather="star" class="w-4 h-4 fill-current"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section (HARDCODED - NO BLADE LOGIC - PREVENTS 500 ERROR) -->
    <section id="pricing" class="py-24 bg-white relative">
        <div class="absolute inset-0 bg-slate-50 skew-y-3 transform origin-bottom-left -z-10 h-1/2 bottom-0"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <span class="text-indigo-600 font-bold tracking-wide uppercase text-sm">Biaya Langganan</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 mb-4 text-slate-900">Investasi Terbaik untuk Pesantren</h2>
                <p class="text-slate-500 text-lg">Harga transparan, sesuai dengan kebutuhan pesantren Anda.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-7xl mx-auto">
                @foreach($plans as $plan)
                <div class="relative bg-white rounded-3xl p-8 border {{ $plan->is_featured ? 'border-2 border-indigo-600 shadow-2xl scale-105 z-10' : 'border-slate-100 shadow-lg' }} flex flex-col h-full transition-transform hover:-translate-y-2" data-aos="fade-up">
                    
                    @if($plan->is_featured)
                    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                        <span class="bg-indigo-600 text-white text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wide shadow-lg">Paling Laris</span>
                    </div>
                    @endif

                    <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $plan->name }}</h3>
                    
                    <div class="flex flex-col mb-4">
                         @if($plan->discount_price)
                            <span class="text-sm text-red-500 line-through font-medium">{{ $plan->formatted_discount_price }}</span>
                         @endif
                         <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-extrabold text-slate-900">{{ $plan->formatted_price }}</span>
                            <span class="text-slate-500 font-medium">/ {{ $plan->duration_months }} bulan</span>
                        </div>
                    </div>

                    <p class="text-slate-500 mb-6 text-sm">{{ $plan->description }}</p>
                    
                    <ul class="space-y-3 mb-8 flex-1">
                        @if(isset($plan->features) && is_array($plan->features))
                            @foreach($plan->features as $feature)
                                @if(isset($feature['included']) && $feature['included'])
                                <li class="flex items-start gap-3 text-sm text-slate-700">
                                    <i data-feather="check-circle" class="w-5 h-5 text-indigo-600 shrink-0"></i> {{ $feature['name'] }}
                                </li>
                                @endif
                            @endforeach
                        @endif
                    </ul>

                    <a href="{{ route('register.tenant', ['package' => $plan->slug]) }}" class="w-full py-4 rounded-xl font-bold text-center transition-all {{ $plan->is_featured ? 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg' : 'bg-slate-50 text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                        Pilih Paket
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
                &copy; {{ date('Y') }} SANTRIX by Velora. All rights reserved. Made with ❤️ for Pesantren Indonesia & Umat.
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        feather.replace();
        AOS.init({
            once: true,
            offset: 100,
            duration: 800,
            easing: 'ease-out-cubic',
        });

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
