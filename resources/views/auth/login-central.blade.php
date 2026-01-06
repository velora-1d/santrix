<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SANTRIX Portal - Masuk</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    
    <!-- Background Decoration -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
        <div class="absolute top-[-20%] left-[-10%] w-[50%] h-[50%] bg-indigo-600/20 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[50%] h-[50%] bg-purple-600/20 rounded-full blur-[120px]"></div>
    </div>

    <div class="w-full max-w-md bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl p-8 z-10">
        
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-indigo-500/30">
                <i data-feather="grid" class="text-white w-8 h-8"></i>
            </div>
            <h1 class="text-2xl font-bold text-white mb-2">Portal Masuk</h1>
            <p class="text-slate-400 text-sm">Silakan pilih akses masuk Anda</p>
        </div>

        <!-- Tab Switcher -->
        <div class="flex p-1 bg-white/5 rounded-lg mb-6">
            <button onclick="switchTab('tenant')" id="tab-tenant" class="flex-1 py-2 text-sm font-medium rounded-md text-white bg-indigo-600 shadow-sm transition-all">
                Sekolah / Santri
            </button>
            <button onclick="switchTab('owner')" id="tab-owner" class="flex-1 py-2 text-sm font-medium rounded-md text-slate-400 hover:text-white transition-all">
                Pemilik / Owner
            </button>
        </div>

        <!-- Tenant Form -->
        <div id="form-tenant" class="space-y-4">
            <div>
                <label class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-2">ID Pesantren (Subdomain)</label>
                <div class="relative">
                    <input type="text" id="subdomain" class="w-full bg-white/5 border border-white/10 rounded-lg py-3 px-4 pl-10 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all" placeholder="contoh: darul-ulum" autocomplete="off">
                    <i data-feather="home" class="absolute left-3 top-3.5 w-4 h-4 text-slate-500"></i>
                    <div class="absolute right-3 top-3.5 text-xs text-slate-500 font-mono">.{{ $mainDomain ?? 'santrix.my.id' }}</div>
                </div>
                <p class="text-xs text-slate-500 mt-2">Masukan ID Pesantren yang Anda daftarkan.</p>
            </div>
            
            <button onclick="goToTenant()" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-medium py-3 rounded-lg shadow-lg shadow-indigo-500/25 transition-all flex items-center justify-center gap-2 group">
                Lanjut ke Login
                <i data-feather="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
            </button>
        </div>

        <!-- Owner Form -->
        <div id="form-owner" class="hidden space-y-4">
            <div class="p-4 bg-purple-500/10 border border-purple-500/20 rounded-lg">
                <p class="text-sm text-purple-200 text-center">
                    Akses khusus untuk Pemilik Yayasan / Platform Administrator.
                </p>
            </div>
            <a href="https://owner.{{ $mainDomain ?? 'santrix.my.id' }}/login" class="block w-full text-center bg-white/10 hover:bg-white/20 text-white font-medium py-3 rounded-lg border border-white/10 transition-all">
                Buka Portal Owner
            </a>
        </div>

        <div class="mt-8 pt-6 border-t border-white/10 text-center">
            <p class="text-slate-400 text-sm">Belum punya akun?</p>
            <a href="{{ route('landing') }}#pricing" class="inline-block mt-2 text-indigo-400 hover:text-indigo-300 text-sm font-medium transition-colors">
                Daftar Pesantren Baru &rarr;
            </a>
        </div>

    </div>

    <script>
        feather.replace();

        function switchTab(tab) {
            const formTenant = document.getElementById('form-tenant');
            const formOwner = document.getElementById('form-owner');
            const btnTenant = document.getElementById('tab-tenant');
            const btnOwner = document.getElementById('tab-owner');

            if (tab === 'tenant') {
                formTenant.classList.remove('hidden');
                formOwner.classList.add('hidden');
                
                btnTenant.classList.add('bg-indigo-600', 'text-white', 'shadow-sm');
                btnTenant.classList.remove('text-slate-400');
                
                btnOwner.classList.remove('bg-indigo-600', 'text-white', 'shadow-sm');
                btnOwner.classList.add('text-slate-400');
            } else {
                formTenant.classList.add('hidden');
                formOwner.classList.remove('hidden');
                
                btnOwner.classList.add('bg-indigo-600', 'text-white', 'shadow-sm');
                btnOwner.classList.remove('text-slate-400');
                
                btnTenant.classList.remove('bg-indigo-600', 'text-white', 'shadow-sm');
                btnTenant.classList.add('text-slate-400');
            }
        }

        function goToTenant() {
            const subdomain = document.getElementById('subdomain').value.trim();
            const mainDomain = "{{ $mainDomain ?? 'santrix.my.id' }}";
            
            if (!subdomain) {
                alert('Silakan isi ID Pesantren terlebih dahulu.');
                return;
            }

            // Redirect to subdomain login
            const url = `https://${subdomain}.${mainDomain}/login`;
            window.location.href = url;
        }

        // Allow Enter key
        document.getElementById('subdomain').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                goToTenant();
            }
        });
    </script>
</body>
</html>
