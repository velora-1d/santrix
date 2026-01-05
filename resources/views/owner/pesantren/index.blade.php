@extends('owner.layouts.app')

@section('title', 'Data Pesantren')
@section('subtitle', 'Manage all registered tenants and their subscriptions.')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <!-- Filters & Search -->
    <div class="p-6 border-b border-slate-100 bg-white flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('owner.pesantren.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tenant..." class="pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-full md:w-64 placeholder-slate-400">
                <div class="absolute left-3 top-2.5 text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
            
            <div class="flex gap-2">
                <select name="status" onchange="this.form.submit()" class="pl-3 pr-8 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-600 bg-white">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>

                <select name="package" onchange="this.form.submit()" class="pl-3 pr-8 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-600 bg-white">
                    <option value="">All Packages</option>
                    @foreach($packages as $pkg)
                        <option value="{{ $pkg->slug }}" {{ request('package') == $pkg->slug ? 'selected' : '' }}>{{ $pkg->name }}</option>
                    @endforeach
                </select>
            </div>
        </form>
        
        <div class="flex items-center gap-2">
            <button class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-50 transition-colors">
                Export Data
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                    <th class="px-6 py-4">Pesantren</th>
                    <th class="px-6 py-4">Subdomain</th>
                    <th class="px-6 py-4">Admin</th>
                    <th class="px-6 py-4">Package</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Expired At</th>
                    <th class="px-6 py-4">Created</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($pesantrens as $p)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-medium text-slate-800">{{ $p->nama }}</div>
                        <div class="text-xs text-slate-500">ID: #{{ $p->id }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $mainDomain = str_replace(['owner.', 'www.'], '', request()->getHost());
                        @endphp
                        <a href="{{ request()->getScheme() }}://{{ $p->subdomain }}.{{ $mainDomain }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
                            {{ $p->subdomain }}
                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        @if($p->admin)
                            <div class="text-sm text-slate-700">{{ $p->admin->name }}</div>
                            <div class="text-xs text-slate-400">{{ $p->admin->email }}</div>
                        @else
                            <span class="text-xs text-slate-400 italic">No admin</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $p->package == 'enterprise' ? 'bg-purple-100 text-purple-800' : ($p->package == 'advance' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }} capitalize">
                            {{ $p->package }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $isExpired = $p->expired_at && $p->expired_at < now();
                            $isExpiringSoon = $p->expired_at && $p->expired_at >= now() && $p->expired_at <= now()->addDays(14);
                            
                            if($p->status == 'suspended') {
                                $statusClass = 'bg-red-100 text-red-800';
                                $statusLabel = 'Suspended';
                            } elseif($isExpired) {
                                $statusClass = 'bg-amber-100 text-amber-800';
                                $statusLabel = 'Expired';
                            } elseif($isExpiringSoon) {
                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                $statusLabel = 'Expiring Soon';
                            } else {
                                $statusClass = 'bg-emerald-100 text-emerald-800';
                                $statusLabel = 'Active';
                            }
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ $p->expired_at ? $p->expired_at->format('d M Y') : '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-500">
                        {{ $p->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('owner.pesantren.show', $p->id) }}" class="inline-flex items-center px-3 py-1.5 border border-slate-200 shadow-sm text-xs font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Detail
                            </a>
                            <form action="{{ route('owner.pesantren.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pesantren ini secara permanen?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-red-200 shadow-sm text-xs font-medium rounded-lg text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="mx-auto w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                        </div>
                        <h3 class="text-slate-900 font-medium">No tenants found</h3>
                        <p class="text-slate-500 text-sm mt-1">Try adjusting your search or filters.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($pesantrens->hasPages())
    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
        {{ $pesantrens->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
