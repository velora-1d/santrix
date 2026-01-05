@extends('owner.layouts.app')

@section('title', 'Tenant Details')
@section('subtitle', 'Manage subscription and settings for ' . $pesantren->nama)

@section('content')
<div class="space-y-6">
    <!-- Back Link -->
    <a href="{{ route('owner.pesantren.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-indigo-600 transition-colors">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to List
    </a>

    <!-- Header Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 relative overflow-hidden">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center relative z-10">
            <div class="flex items-center">
                <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-indigo-500/30">
                    {{ substr($pesantren->nama, 0, 1) }}
                </div>
                <div class="ml-6">
                    <h1 class="text-2xl font-bold text-slate-800">{{ $pesantren->nama }}</h1>
                    <div class="flex items-center mt-2 text-sm text-slate-500">
                        <span class="font-mono bg-slate-100 px-2 py-1 rounded text-slate-600 select-all">{{ $pesantren->subdomain }}</span>
                        <span class="mx-2">•</span>
                        <span>Joined {{ $pesantren->created_at->format('F Y') }}</span>
                    </div>
                </div>
            </div>
            <div class="mt-6 md:mt-0 flex gap-3">
                <a href="{{ route('owner.pesantren.edit', $pesantren->id) }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors shadow-sm">
                    Edit Subscription
                </a>
                
                <form action="{{ route('owner.pesantren.suspend', $pesantren->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to change the status of this tenant?');">
                    @csrf
                    @if($pesantren->status === 'suspended')
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-500/30">
                            Reactivate Tenant
                        </button>
                    @else
                        <button type="submit" class="px-4 py-2 bg-red-50 border border-red-100 text-red-600 font-medium rounded-lg hover:bg-red-100 transition-colors">
                            Suspend Access
                        </button>
                    @endif
                </form>
            </div>
        </div>
        <!-- Background Pattern -->
        <div class="absolute right-0 top-0 w-64 h-full bg-gradient-to-l from-indigo-50 to-transparent pointer-events-none"></div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-sm font-medium text-slate-500 mb-1">Current Package</p>
            <div class="flex justify-between items-center">
                <span class="text-xl font-bold text-slate-800 capitalize">{{ $pesantren->package }}</span>
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $pesantren->package == 'enterprise' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                    Active
                </span>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-sm font-medium text-slate-500 mb-1">Expires On</p>
            <span class="text-xl font-bold text-slate-800">{{ $pesantren->expired_at ? $pesantren->expired_at->format('d M Y') : '-' }}</span>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-sm font-medium text-slate-500 mb-1">Total Santri</p>
            <span class="text-xl font-bold text-slate-800">{{ $pesantren->santri_count }}</span>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-sm font-medium text-slate-500 mb-1">Total Billing</p>
            <span class="text-xl font-bold text-slate-800">Rp {{ number_format($pesantren->invoices->where('status', 'paid')->sum('amount'), 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Admin Info -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Tenant Administrator</h3>
        @if($pesantren->admin)
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold">
                    {{ substr($pesantren->admin->name, 0, 2) }}
                </div>
                <div class="ml-4">
                    <p class="font-medium text-slate-800">{{ $pesantren->admin->name }}</p>
                    <p class="text-sm text-slate-500">{{ $pesantren->admin->email }}</p>
                </div>
            </div>
        @else
            <p class="text-slate-400 italic">No admin user assigned to this tenant.</p>
        @endif
    </div>

    <!-- Content Tabs -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="border-b border-slate-100">
            <nav class="flex px-6" aria-label="Tabs">
                <button class="border-b-2 border-indigo-500 py-4 px-6 text-sm font-medium text-indigo-600">
                    Subscription History
                </button>
                <button class="border-b-2 border-transparent py-4 px-6 text-sm font-medium text-slate-500 hover:text-slate-700 hover:border-slate-300">
                    Invoices
                </button>
            </nav>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 divide-y lg:divide-y-0 lg:divide-x divide-slate-100">
            <!-- Subscription Table -->
            <div class="p-6">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide mb-4">Package History</h3>
                <div class="overflow-hidden rounded-xl border border-slate-100">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Package</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Period</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse($pesantren->subscriptions as $sub)
                            <tr>
                                <td class="px-4 py-3 text-sm font-medium text-slate-800 capitalize">{{ $sub->package_name }}</td>
                                <td class="px-4 py-3 text-xs text-slate-500">
                                    {{ $sub->start_date?->format('d M y') ?? '-' }} - {{ $sub->end_date?->format('d M y') ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $sub->status == 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-50 text-slate-600' }}">
                                        {{ $sub->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="px-4 py-4 text-center text-sm text-slate-400">No history available</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Invoices Table -->
            <div class="p-6">
                 <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide mb-4">Recent Invoices</h3>
                 <div class="overflow-hidden rounded-xl border border-slate-100">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Invoice #</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                            </tr>
                        </thead>
                         <tbody class="bg-white divide-y divide-slate-100">
                            @forelse($pesantren->invoices as $inv)
                            <tr>
                                <td class="px-4 py-3 text-xs font-mono text-slate-600">{{ $inv->invoice_number }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-slate-800">Rp {{ number_format($inv->amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $inv->status == 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                        {{ $inv->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="px-4 py-4 text-center text-sm text-slate-400">No invoices generated</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                 </div>
            </div>
        </div>
    </div>
</div>
@endsection
