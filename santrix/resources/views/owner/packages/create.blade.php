@extends('layouts.app')

@section('title', 'Buat Paket Baru')
@section('page-title', 'Buat Paket Baru')

@section('sidebar-menu')
    @include('owner.partials.sidebar-menu')
@endsection

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <a href="{{ route('owner.packages.index') }}" style="text-decoration: none; color: #64748b; font-size: 0.875rem; display: flex; align-items: center; gap: 8px;">
            <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i>
            Kembali ke Daftar Paket
        </a>
    </div>

    <form action="{{ route('owner.packages.store') }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 32px;">
            
            <!-- Left Column -->
            <div style="flex: 2; min-width: 0;">
                <!-- Details Card -->
                <div style="background: white; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 32px;">
                    <div style="padding: 24px; border-bottom: 1px solid #f1f5f9; background: #f8fafc;">
                        <h3 style="margin: 0; font-size: 1.125rem; font-weight: 700; color: #1e2937;">Detail Paket</h3>
                    </div>
                    <div style="padding: 24px; display: grid; gap: 24px;">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px;">
                            <div>
                                <label for="name" style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.875rem; color: #374151;">Nama Paket</label>
                                <input type="text" name="name" id="name" required style="width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; outline: none; transition: border-color 0.2s;" placeholder="Contoh: Basic Plan">
                            </div>
                            <div>
                                <label for="slug" style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.875rem; color: #374151;">Slug (ID Unik)</label>
                                <input type="text" name="slug" id="slug" required style="width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; outline: none; background: #f8fafc;" placeholder="basic-3">
                            </div>
                        </div>
                        <div>
                            <label for="description" style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.875rem; color: #374151;">Deskripsi</label>
                            <textarea name="description" id="description" rows="3" style="width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; outline: none;" placeholder="Deskripsi paket..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Features Card -->
                <div style="background: white; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden;">
                    <div style="padding: 24px; border-bottom: 1px solid #f1f5f9; background: #f8fafc; display: flex; justify-content: space-between; align-items: center;">
                        <h3 style="margin: 0; font-size: 1.125rem; font-weight: 700; color: #1e2937;">Fitur & Layanan</h3>
                        <span style="font-size: 0.75rem; background: #e2e8f0; padding: 4px 8px; border-radius: 12px; color: #475569;">Centang untuk aktifkan</span>
                    </div>
                    <div style="padding: 24px;">
                        @foreach($defaultFeatures as $index => $feature)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; border: 1px dashed #e2e8f0; border-radius: 8px; margin-bottom: 12px;">
                            <div style="flex: 1;">
                                <input type="text" name="feature_names[]" value="{{ $feature['name'] }}" style="border: none; background: transparent; width: 100%; font-weight: 500; color: #334155; outline: none;" readonly>
                                <input type="hidden" name="feature_fixed_names[]" value="{{ $feature['name'] }}">
                            </div>
                            <label style="cursor: pointer; display: flex; align-items: center;">
                                <input type="checkbox" name="feature_included[{{ $index }}]" value="1" {{ $feature['included'] ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #4f46e5;">
                            </label>
                        </div>
                        @endforeach
                        
                        <!-- Extra Slots -->
                        @for($i=0; $i<3; $i++)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; border: 1px dashed #e2e8f0; border-radius: 8px; margin-bottom: 12px;">
                            <div style="flex: 1;">
                                <input type="text" name="feature_names[]" placeholder="Fitur Tambahan..." style="border: none; background: transparent; width: 100%; color: #334155; outline: none;">
                            </div>
                            <label style="cursor: pointer; display: flex; align-items: center;">
                                <input type="checkbox" name="feature_included[{{ count($defaultFeatures) + $i }}]" value="1" style="width: 18px; height: 18px; accent-color: #4f46e5;">
                            </label>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div style="flex: 1; min-width: 0;">
                <!-- Pricing Card -->
                <div style="background: white; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 32px;">
                    <div style="padding: 24px; border-bottom: 1px solid #f1f5f9; background: #f8fafc;">
                        <h3 style="margin: 0; font-size: 1.125rem; font-weight: 700; color: #1e2937;">Harga & Durasi</h3>
                    </div>
                    <div style="padding: 24px; display: grid; gap: 24px;">
                        <div>
                            <label for="price" style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.875rem; color: #374151;">Harga Jual (Net)</label>
                            <div style="position: relative;">
                                <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-weight: 600; color: #64748b;">Rp</span>
                                <input type="number" name="price" id="price" required style="width: 100%; padding: 12px 12px 12px 40px; border: 1px solid #e2e8f0; border-radius: 8px; font-weight: 700; font-size: 1.125rem; outline: none;">
                            </div>
                        </div>

                         <div>
                            <label for="discount_price" style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.875rem; color: #374151;">Harga Coret (Optional)</label>
                            <div style="position: relative;">
                                <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-weight: 600; color: #ef4444;">Rp</span>
                                <input type="number" name="discount_price" id="discount_price" style="width: 100%; padding: 12px 12px 12px 40px; border: 1px solid #fecaca; background: #fef2f2; border-radius: 8px; font-weight: 500; font-size: 1rem; color: #ef4444; outline: none; text-decoration: line-through;">
                            </div>
                        </div>

                        <div>
                            <label for="duration_months" style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.875rem; color: #374151;">Durasi (Bulan)</label>
                            <input type="number" name="duration_months" id="duration_months" required min="1" style="width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; outline: none;">
                        </div>
                    </div>
                </div>

                <!-- Settings Card -->
                <div style="background: #1e293b; border-radius: 16px; border: 1px solid #0f172a; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); overflow: hidden; color: white;">
                    <div style="padding: 24px; display: grid; gap: 24px;">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <label for="is_featured" style="display: block; font-weight: 700; margin-bottom: 4px;">Featured Package</label>
                                <div style="font-size: 0.75rem; color: #94a3b8;">Highlight sebagai "Best Value"</div>
                            </div>
                             <label style="cursor: pointer;">
                                <input type="checkbox" name="is_featured" value="1" style="width: 20px; height: 20px; accent-color: #4f46e5;">
                            </label>
                        </div>
                         <div>
                            <label for="sort_order" style="display: block; font-weight: 700; margin-bottom: 8px;">Urutan Tampilan</label>
                            <input type="number" name="sort_order" value="0" style="width: 100%; padding: 8px 12px; border: 1px solid #334155; background: #0f172a; border-radius: 8px; color: white; outline: none;">
                        </div>
                        
                        <button type="submit" style="width: 100%; padding: 12px; background: #4f46e5; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#4338ca'" onmouseout="this.style.background='#4f46e5'">
                            Simpan Paket
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
