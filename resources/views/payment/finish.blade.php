<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Selesai - Santrix</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="bg-slate-50 font-['Outfit']">

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl text-center">
        <div class="flex justify-center">
            <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center">
                <i data-feather="check" class="w-8 h-8 text-emerald-600"></i>
            </div>
        </div>
        
        <div>
            <h2 class="mt-6 text-3xl font-extrabold text-slate-900">
                Pembayaran Berhasil!
            </h2>
            <p class="mt-2 text-sm text-slate-600">
                Terima kasih telah melakukan pembayaran. Akun Anda sedang diproses dan akan segera aktif.
            </p>
        </div>

        <div class="mt-8 space-y-4">
            <a href="{{ route('owner.dashboard') }}" class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg hover:shadow-indigo-500/30 transition-all">
                Masuk ke Dashboard
            </a>
            
            <a href="{{ url('/') }}" class="w-full flex justify-center py-3 px-4 border border-slate-300 text-sm font-medium rounded-xl text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

@endsection

<script>
    feather.replace();
</script>
</body>
</html>
