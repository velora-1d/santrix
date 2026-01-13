<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil - Menunggu Pembayaran</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="bg-slate-50 font-['Outfit'] min-h-screen flex items-center justify-center p-4">

    <div class="max-w-2xl w-full bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-indigo-600 px-8 py-10 text-center text-white">
            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-feather="check" class="w-10 h-10 text-white"></i>
            </div>
            <h1 class="text-3xl font-bold mb-2">Pendaftaran Berhasil!</h1>
            <p class="text-indigo-100">Akun Pesantren <span class="font-bold text-white">{{ $subdomain }}.santrix.my.id</span> telah dibuat.</p>
        </div>

        <!-- Content -->
        <div class="p-8">
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 mb-8 text-center">
                <h3 class="font-bold text-amber-900 mb-2">Selesaikan Pembayaran</h3>
                <p class="text-amber-700 text-sm mb-4">Silakan transfer biaya langganan sebesar:</p>
                <div class="text-4xl font-extrabold text-amber-600 mb-6">
                    Rp {{ $amount }}
                </div>
                
                @if($bank)
                <div class="bg-white rounded-lg border border-amber-200 p-4 max-w-sm mx-auto">
                    <p class="text-xs text-slate-500 uppercase tracking-wide font-bold mb-1">Bank Transfer</p>
                    <p class="font-bold text-slate-800 text-lg">{{ $bank }}</p>
                </div>
                @endif
            </div>

            <div class="space-y-4">
                <a href="{{ $invoiceUrl }}" target="_blank" class="block w-full text-center py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-indigo-500/30">
                    <i data-feather="file-text" class="w-4 h-4 inline mr-2"></i>
                    Lihat & Konfirmasi Invoice
                </a>

                <a href="{{ route('landing') }}" class="block w-full text-center py-4 text-slate-500 hover:text-slate-700 font-medium">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <script>
        feather.replace();
    </script>
</body>
</html>
