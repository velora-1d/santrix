@extends('owner.layouts.app')

@section('title', 'Buat Paket Baru')
@section('subtitle', 'Rancang paket berlangganan baru untuk tenant.')

@section('content')
<div class="max-w-5xl mx-auto pb-12">
    <!-- Breadcrumb & Back -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('owner.packages.index') }}" class="flex items-center text-slate-500 hover:text-indigo-600 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Batal
        </a>
    </div>

    <form action="{{ route('owner.packages.store') }}" method="POST" class="space-y-8">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Card: Basic Details -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                        <div class="bg-indigo-100 p-2 rounded-lg text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Detail Paket</h3>
                    </div>
                    <div class="p-6 grid gap-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Nama Paket</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all text-sm py-2.5 px-3" placeholder="Contoh: Basic Plan">
                            </div>
                            <div>
                                <label for="slug" class="block text-sm font-semibold text-slate-700 mb-1">Slug (ID Unik)</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-slate-300 bg-slate-50 text-slate-500 sm:text-sm">#</span>
                                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required class="flex-1 block w-full rounded-none rounded-r-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3" placeholder="basic-3">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi & Highlight</label>
                            <textarea name="description" id="description" rows="3" class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all text-sm py-2.5 px-3" placeholder="Jelaskan keunggulan paket ini...">{{ old('description') }}</textarea>
                            <p class="mt-1.5 text-xs text-slate-500">Akan ditampilkan di halaman depan di bawah harga.</p>
                        </div>
                    </div>
                </div>

                <!-- Card: Features -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                        <div class="bg-emerald-100 p-2 rounded-lg text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Fitur & Layanan</h3>
                        <span class="ml-auto text-xs font-medium text-slate-500 bg-slate-100 px-2 py-1 rounded-full">Centang untuk aktifkan</span>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-2">
                            @foreach($defaultFeatures as $index => $feature)
                            <div class="group flex items-center justify-between p-3 rounded-xl border border-dashed border-slate-200 hover:border-indigo-300 hover:bg-slate-50 transition-all">
                                <div class="flex-1 mr-4">
                                    <input type="text" name="feature_names[]" value="{{ $feature['name'] }}" class="block w-full border-0 bg-transparent p-0 text-sm font-medium text-slate-700 focus:ring-0 placeholder-slate-400" readonly>
                                    <input type="hidden" name="feature_fixed_names[]" value="{{ $feature['name'] }}">
                                </div>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="feature_included[{{ $index }}]" value="1" {{ $feature['included'] ? 'checked' : '' }} class="sr-only peer">
                                    <div class="relative w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                </label>
                            </div>
                            @endforeach

                            <!-- Extra Slots -->
                            @for($i=0; $i<3; $i++)
                            <div class="group flex items-center justify-between p-3 rounded-xl border border-dashed border-slate-200 hover:border-indigo-300 hover:bg-slate-50 transition-all">
                                <div class="flex-1 mr-4">
                                    <input type="text" name="feature_names[]" placeholder="Fitur Tambahan..." class="block w-full border-0 bg-transparent p-0 text-sm font-medium text-slate-700 focus:ring-0 placeholder-slate-400">
                                </div>
                                <label class="inline-flex items-center cursor-pointer opacity-50 group-hover:opacity-100 transition-opacity">
                                    <input type="checkbox" name="feature_included[{{ count($defaultFeatures) + $i }}]" value="1" class="sr-only peer">
                                    <div class="relative w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                </label>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Pricing & Meta -->
            <div class="space-y-8">
                <!-- Card: Pricing -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                        <div class="bg-amber-100 p-2 rounded-lg text-amber-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Harga & Durasi</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label for="price" class="block text-sm font-semibold text-slate-700 mb-1">Harga Jual (Net)</label>
                            <div class="relative rounded-xl shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-slate-500 font-bold">Rp</span>
                                </div>
                                <input type="number" name="price" id="price" value="{{ old('price') }}" required class="block w-full pl-10 rounded-xl border-slate-300 focus:ring-indigo-500 focus:border-indigo-500 text-lg font-bold text-slate-800 py-3" placeholder="0">
                            </div>
                        </div>

                        <div class="relative">
                            <label for="discount_price" class="block text-sm font-semibold text-slate-700 mb-1">
                                Harga Coret <span class="text-xs font-normal text-red-500 ml-1">(Optional)</span>
                            </label>
                            <div class="relative rounded-xl shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-red-300 font-bold">Rp</span>
                                </div>
                                <input type="number" name="discount_price" id="discount_price" value="{{ old('discount_price') }}" class="block w-full pl-10 rounded-xl border-red-200 focus:ring-red-500 focus:border-red-500 text-md text-slate-600 line-through decoration-red-400 py-2.5 bg-red-50/30" placeholder="0">
                            </div>
                            <p class="mt-1 text-xs text-slate-400">Harga asli sebelum diskon.</p>
                        </div>

                        <div>
                            <label for="duration_months" class="block text-sm font-semibold text-slate-700 mb-1">Durasi Langganan</label>
                            <div class="relative rounded-xl shadow-sm">
                                <input type="number" name="duration_months" id="duration_months" value="{{ old('duration_months') }}" required min="1" class="block w-full rounded-xl border-slate-300 focus:ring-indigo-500 focus:border-indigo-500 py-2.5 px-3">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-slate-500 text-sm font-medium">Bulan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Settings -->
                <div class="bg-slate-900 rounded-2xl shadow-lg border border-slate-800 overflow-hidden text-white">
                    <div class="p-6 space-y-6">
                        <div class="flex items-center justify-between">
                            <div class="flex flex-col">
                                <label for="is_featured" class="text-sm font-bold text-white">Featured Package</label>
                                <span class="text-xs text-slate-400">Highlight paket ini sebagai "Best Value"</span>
                            </div>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="is_featured" name="is_featured" class="sr-only peer">
                                <div class="relative w-11 h-6 bg-slate-700 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-500"></div>
                            </label>
                        </div>

                        <div>
                            <label for="sort_order" class="block text-sm font-bold text-white mb-1">Urutan Tampilan</label>
                            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" class="block w-full rounded-xl border-slate-700 bg-slate-800 text-white focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 sm:text-sm">
                        </div>
                    </div>
                    <div class="bg-slate-800/50 px-6 py-4 border-t border-slate-800 flex justify-end">
                        <button type="submit" class="w-full inline-flex justify-center items-center py-3 px-4 border border-transparent shadow-sm text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all hover:shadow-lg hover:shadow-indigo-500/30">
                            Buat Paket
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
