<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->number }} - Santrix</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-50 font-['Outfit'] min-h-screen py-10 px-4">

    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-indigo-600 px-8 py-6 flex justify-between items-center text-white">
            <div>
                <h1 class="text-2xl font-bold">INVOICE</h1>
                <p class="text-indigo-100 text-sm">#{{ $invoice->number }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-indigo-100">Jatuh Tempo</p>
                <p class="font-semibold">{{ \Carbon\Carbon::parse($invoice->due_date)->translatedFormat('d F Y') }}</p>
            </div>
        </div>

        <div class="p-8">
            <!-- Status & Amount -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <p class="text-slate-500 text-sm uppercase tracking-wide font-bold mb-1">Total Tagihan</p>
                    <div class="text-4xl font-extrabold text-slate-900">
                        Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                    </div>
                </div>
                <div>
                    @if($invoice->status == 'paid')
                        <span class="px-4 py-2 bg-emerald-100 text-emerald-700 font-bold rounded-lg border border-emerald-200">
                            LUNAS
                        </span>
                    @elseif($invoice->status == 'pending')
                        <span class="px-4 py-2 bg-amber-100 text-amber-700 font-bold rounded-lg border border-amber-200">
                            BELUM DIBAYAR
                        </span>
                    @else
                        <span class="px-4 py-2 bg-red-100 text-red-700 font-bold rounded-lg border border-red-200">
                            BATAL / KEDALUWARSA
                        </span>
                    @endif
                </div>
            </div>

            <!-- Item Details -->
            <div class="border rounded-xl overflow-hidden mb-8">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-slate-700">Deskripsi</th>
                            <th class="px-6 py-4 font-semibold text-slate-700 text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr>
                            <td class="px-6 py-4">
                                <p class="font-medium text-slate-900">{{ $invoice->description }}</p>
                                <p class="text-sm text-slate-500">Langganan Santrix Periode {{ \Carbon\Carbon::parse($invoice->period_start)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($invoice->period_end)->format('d M Y') }}</p>
                            </td>
                            <td class="px-6 py-4 text-right font-medium text-slate-900">
                                Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Payment Action -->
            <div class="bg-indigo-50 rounded-xl p-6 text-center">
                @if($invoice->status == 'pending')
                    <h3 class="font-bold text-indigo-900 mb-2">Metode Pembayaran</h3>
                    <p class="text-indigo-700 text-sm mb-6">Silakan selesaikan pembayaran agar akun Anda segera aktif.</p>
                    
                    @if($paymentUrl)
                        <a href="{{ $paymentUrl }}" class="inline-block w-full md:w-auto px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg hover:shadow-indigo-500/30 transition-all transform hover:-translate-y-1">
                            Bayar Sekarang via Duitku
                        </a>
                    @else
                        <div class="text-red-500 font-medium">
                            Gagal memuat link pembayaran. Silakan hubungi admin.
                        </div>
                    @endif

                @else
                    <p class="text-emerald-700 font-bold">Terima kasih! Pembayaran Anda telah diterima.</p>
                    <a href="{{ route('landing') }}" class="inline-block mt-4 text-indigo-600 hover:text-indigo-800 font-medium underline">
                        Kembali ke Halaman Utama
                    </a>
                @endif
            </div>
            
            <div class="mt-8 text-center text-slate-400 text-sm">
                &copy; {{ date('Y') }} Santrix. System generated invoice.
            </div>
        </div>
    </div>

</body>
</html>
