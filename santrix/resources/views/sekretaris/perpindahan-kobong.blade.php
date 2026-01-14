@extends('layouts.app')

@section('title', 'Rotasi Kobong Massal')
@section('page-title', 'Rotasi Kobong Massal')

@section('sidebar-menu')
    @include('sekretaris.partials.sidebar-menu')
@endsection

@section('content')
    <!-- Navigation Tabs -->
    <div style="background: white; padding: 4px; border-radius: 12px; display: inline-flex; gap: 4px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <a href="{{ route('sekretaris.perpindahan') }}" style="padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 500; text-decoration: none; color: #64748b; transition: all 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
            Perpindahan Santri
        </a>
        <a href="{{ route('sekretaris.perpindahan-kobong') }}" style="padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; background: #6366f1; color: white;">
            Rotasi Kobong
        </a>
    </div>

    @if(session('success'))
        <div style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 12px rgba(67, 233, 123, 0.3);">
            <i data-feather="check-circle" style="width: 24px; height: 24px;"></i>
            <span style="font-weight: 600;">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div style="background: linear-gradient(135deg, #ff6a00 0%, #ee0979 100%); color: white; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 12px rgba(238, 9, 121, 0.3);">
            <i data-feather="alert-circle" style="width: 24px; height: 24px;"></i>
            <span style="font-weight: 600;">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 16px; padding: 24px 32px; margin-bottom: 24px; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3); position: relative; overflow: hidden;">
        <div style="position: absolute; top: -30px; right: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="display: flex; align-items: center; gap: 16px; position: relative; z-index: 1;">
            <div style="background: rgba(255,255,255,0.2); width: 56px; height: 56px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i data-feather="box" style="width: 28px; height: 28px; color: white;"></i>
            </div>
            <div>
                <h2 style="font-size: 1.5rem; font-weight: 700; color: white; margin: 0 0 4px 0;">Rotasi Kobong</h2>
                <p style="color: rgba(255,255,255,0.9); font-size: 0.875rem; margin: 0;">Pindahkan santri dari satu kobong ke kobong lain secara massal</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
        <form method="POST" action="{{ route('sekretaris.perpindahan-kobong.process') }}">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 24px;">
                <!-- Source -->
                <div style="background: #f9fafb; padding: 20px; border-radius: 12px; border: 1px solid #e5e7eb;">
                    <h3 style="font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        <span style="background: #ef4444; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">1</span>
                        Dari Kobong (Sumber)
                    </h3>
                    
                    <div style="margin-bottom: 16px;">
                        <label style="font-size: 12px; font-weight: 600; color: #4b5563; margin-bottom: 6px; display: block;">Asrama</label>
                        <select id="asrama_asal" style="width: 100%; height: 40px; border: 1px solid #d1d5db; border-radius: 8px; padding: 0 12px;">
                            <option value="">Pilih Asrama</option>
                            @foreach($asramaList as $asrama)
                                <option value="{{ $asrama->id }}">{{ $asrama->nama_asrama }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label style="font-size: 12px; font-weight: 600; color: #4b5563; margin-bottom: 6px; display: block;">Kobong</label>
                        <select name="kobong_asal_id" id="kobong_asal" style="width: 100%; height: 40px; border: 1px solid #d1d5db; border-radius: 8px; padding: 0 12px;" required>
                            <option value="">Pilih Asrama Dulu</option>
                        </select>
                    </div>
                </div>

                <!-- Destination -->
                <div style="background: #f9fafb; padding: 20px; border-radius: 12px; border: 1px solid #e5e7eb;">
                    <h3 style="font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        <span style="background: #10b981; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">2</span>
                        Ke Kobong (Tujuan)
                    </h3>
                    
                    <div style="margin-bottom: 16px;">
                        <label style="font-size: 12px; font-weight: 600; color: #4b5563; margin-bottom: 6px; display: block;">Asrama</label>
                        <select id="asrama_tujuan" style="width: 100%; height: 40px; border: 1px solid #d1d5db; border-radius: 8px; padding: 0 12px;">
                            <option value="">Pilih Asrama</option>
                            @foreach($asramaList as $asrama)
                                <option value="{{ $asrama->id }}">{{ $asrama->nama_asrama }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label style="font-size: 12px; font-weight: 600; color: #4b5563; margin-bottom: 6px; display: block;">Kobong</label>
                        <select name="kobong_tujuan_id" id="kobong_tujuan" style="width: 100%; height: 40px; border: 1px solid #d1d5db; border-radius: 8px; padding: 0 12px;" required>
                            <option value="">Pilih Asrama Dulu</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Santri List -->
            <div id="santri_section" style="display: none;">
                <h3 style="font-size: 14px; font-weight: 700; color: #374151; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                    <span style="background: #3b82f6; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">3</span>
                    Pilih Santri yang Akan Dipindahkan
                </h3>

                <div style="border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; margin-bottom: 24px;">
                    <div style="background: #f3f4f6; padding: 10px 16px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                        <label style="font-size: 13px; font-weight: 600; color: #374151; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" id="select_all" checked> Pilih Semua Santri
                        </label>
                        <span id="santri_count_badge" style="background: white; padding: 2px 10px; border-radius: 10px; font-size: 11px; font-weight: 600; color: #6b7280;">0 Santri</span>
                    </div>
                    <div style="max-height: 400px; overflow-y: auto;" id="santri_list_container">
                        <!-- Santri items injected here -->
                    </div>
                </div>

                <div style="text-align: right;">
                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin memindahkan santri terpilih?')" style="padding: 12px 24px; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; box-shadow: 0 4px 12px rgba(67, 233, 123, 0.3);">
                        Proses Perpindahan
                    </button>
                </div>
            </div>

        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const routeKobongByAsrama = "{{ route('sekretaris.api.kobong-by-asrama', 0) }}";
        const routeSantriByKobong = "{{ route('sekretaris.api.santri-by-kobong', 0) }}"; // NEW API needed or reuse existing
        // Wait, SekreatrisBulkController has getSantriByKobong($kobongId) method?
        // Let's check controller. Yes: getSantriByKobong($kobongId) (Lines 496 controller)
        // I need to register route for it: secretary.api.santri-by-kobong
        
        // Asrama Asal Change
        document.getElementById('asrama_asal').addEventListener('change', function() {
            loadKobong(this.value, 'kobong_asal');
            document.getElementById('santri_section').style.display = 'none';
        });

        // Asrama Tujuan Change
        document.getElementById('asrama_tujuan').addEventListener('change', function() {
            loadKobong(this.value, 'kobong_tujuan');
        });

        // Kobong Asal Change -> Load Santri
        document.getElementById('kobong_asal').addEventListener('change', function() {
            if (this.value) {
                loadSantri(this.value);
            } else {
                document.getElementById('santri_section').style.display = 'none';
            }
        });

        function loadKobong(asramaId, targetId) {
            const select = document.getElementById(targetId);
            if (!asramaId) {
                select.innerHTML = '<option value="">Pilih Asrama Dulu</option>';
                return;
            }

            fetch(routeKobongByAsrama.replace('/0', '/' + asramaId))
                .then(res => res.json())
                .then(data => {
                    select.innerHTML = '<option value="">Pilih Kobong</option>' + 
                        data.map(k => `<option value="${k.id}">Kobong ${k.nomor_kobong}</option>`).join('');
                });
        }

        function loadSantri(kobongId) {
            const container = document.getElementById('santri_list_container');
            container.innerHTML = '<div style="padding: 20px; text-align: center;">Loading...</div>';
            document.getElementById('santri_section').style.display = 'block';

            // Route definition logic needs to be verified. 
            // Assuming route('sekretaris.api.santri-by-kobong', ':id') exists
            // If not, I must add it.
            const url = routeSantriByKobong.replace('/0', '/' + kobongId);
            
            fetch(url)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('santri_count_badge').innerText = data.length + ' Santri';
                    
                    if (data.length === 0) {
                        container.innerHTML = '<div style="padding: 20px; text-align: center; color: #6b7280;">Tidak ada santri di kobong ini</div>';
                        return;
                    }

                    let html = '';
                    data.forEach(s => {
                        html += `
                            <div style="padding: 10px 16px; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; gap: 12px; background: white;">
                                <input type="checkbox" name="santri_ids[]" value="${s.id}" class="santri-cb" checked style="width: 16px; height: 16px;">
                                <div>
                                    <div style="font-size: 13px; font-weight: 600; color: #1f2937;">${s.nama_santri}</div>
                                    <div style="font-size: 11px; color: #6b7280;">${s.nis}</div>
                                </div>
                            </div>
                        `;
                    });
                    container.innerHTML = html;
                });
        }

        // Select All Toggle
        const selectAll = document.getElementById('select_all');
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                document.querySelectorAll('.santri-cb').forEach(cb => cb.checked = this.checked);
            });
        }
    });
</script>
@endpush
