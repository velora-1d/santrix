<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil - SANTRIX</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-linear-to-br from-indigo-600 to-violet-700 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full p-8 text-center">
        
        <!-- Success Icon -->
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i data-feather="check-circle" class="w-10 h-10 text-green-500"></i>
        </div>
        
        <h1 class="text-2xl font-bold text-slate-900 mb-2">Pendaftaran Berhasil! 🎉</h1>
        <p class="text-slate-500 mb-8">Akun pesantren Anda sudah aktif dengan masa trial.</p>
        
        <!-- Info Box -->
        <div class="bg-slate-50 rounded-2xl p-6 text-left mb-8">
            <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                <i data-feather="info" class="w-4 h-4 text-indigo-600"></i>
                Informasi Login Anda
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">Subdomain / URL Dashboard</label>
                    <div class="mt-1 flex items-center gap-2">
                        <code class="flex-1 bg-white border border-slate-200 rounded-lg px-3 py-2 text-indigo-600 font-mono text-sm">
                            {{ $subdomain }}.santrix.my.id
                        </code>
                        <button onclick="copyToClipboard('https://{{ $subdomain }}.santrix.my.id')" class="p-2 bg-indigo-100 rounded-lg text-indigo-600 hover:bg-indigo-200 transition-colors">
                            <i data-feather="copy" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
                
                <div>
                    <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">Email Login</label>
                    <p class="mt-1 font-semibold text-slate-900">{{ $email }}</p>
                </div>
                
                <div>
                    <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">Masa Trial</label>
                    <p class="mt-1 font-semibold text-slate-900">{{ $trialDays }} Hari (Berakhir: {{ $trialEndsAt }})</p>
                </div>
            </div>
        </div>
        
        <!-- CTA Buttons -->
        <div class="space-y-3">
            <a href="https://{{ $subdomain }}.santrix.my.id/login" class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transition-all">
                <i data-feather="log-in" class="w-5 h-5"></i>
                Login ke Dashboard
            </a>
            <a href="{{ route('landing') }}" class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl transition-all">
                Kembali ke Beranda
            </a>
        </div>
        
        <!-- Footer Note -->
        <p class="text-xs text-slate-400 mt-6">
            Simpan informasi ini. Link login juga sudah dikirim ke email Anda.
        </p>
    </div>

    <script>
        feather.replace();
        
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Link berhasil disalin!');
            });
        }
    </script>
</body>
</html>
