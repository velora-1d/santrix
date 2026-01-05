@extends('owner.layouts.app')

@section('title', 'Edit Paket')
@section('subtitle', 'Ubah detail paket berlangganan.')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('owner.packages.update', $package->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-slate-900 border-b pb-2">Informasi Paket</h3>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700">Nama Paket</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $package->name) }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-slate-700">Slug (Unique ID)</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $package->slug) }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="duration_months" class="block text-sm font-medium text-slate-700">Durasi (Bulan)</label>
                        <input type="number" name="duration_months" id="duration_months" value="{{ old('duration_months', $package->duration_months) }}" required min="1" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700">Deskripsi Singkat</label>
                        <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $package->description) }}</textarea>
                    </div>
                </div>

                <!-- Pricing & Options -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-slate-900 border-b pb-2">Harga</h3>

                    <div>
                        <label for="price" class="block text-sm font-medium text-slate-700">Harga Jual (Final)</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="price" id="price" value="{{ old('price', $package->price) }}" required class="block w-full pl-12 sm:text-sm border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="0.00">
                        </div>
                    </div>

                    <div>
                        <label for="discount_price" class="block text-sm font-medium text-slate-700">Harga Coret (Asli)</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="discount_price" id="discount_price" value="{{ old('discount_price', $package->discount_price) }}" class="block w-full pl-12 sm:text-sm border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="0.00">
                        </div>
                    </div>

                    <div class="pt-4 space-y-4">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="is_featured" name="is_featured" type="checkbox" {{ $package->is_featured ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_featured" class="font-medium text-slate-700">Featured Package</label>
                            </div>
                        </div>

                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-slate-700">Urutan Tampilan</label>
                            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $package->sort_order) }}" class="mt-1 block w-20 rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div class="space-y-4 pt-6 border-t border-slate-200">
                <h3 class="text-lg font-medium text-slate-900">Fitur Paket</h3>
                <div class="border rounded-md overflow-hidden">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider w-10">Include</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama Fitur</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @if(is_array($package->features))
                                @foreach($package->features as $index => $feature)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <input type="checkbox" name="feature_included[{{ $index }}]" value="1" {{ isset($feature['included']) && $feature['included'] ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                        <input type="text" name="feature_names[]" value="{{ $feature['name'] }}" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                            
                            <!-- Add more slots if less than 10 -->
                            @php $currentCount = is_array($package->features) ? count($package->features) : 0; @endphp
                            @for($i=0; $i < max(3, 12 - $currentCount); $i++)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <input type="checkbox" name="feature_included[{{ $currentCount + $i }}]" value="1" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                    <input type="text" name="feature_names[]" placeholder="Fitur tambahan..." class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-xs">
                                </td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-end pt-6 border-t border-slate-200">
                <a href="{{ route('owner.packages.index') }}" class="bg-white py-2 px-4 border border-slate-300 rounded-md shadow-sm text-sm font-medium text-slate-700 hover:bg-slate-50 mr-3">
                    Batal
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
