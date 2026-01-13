@extends('layouts.app')

@section('title', 'Data Pesantren')
@section('page-title', 'Data Pesantren')

@section('sidebar-menu')
    @include('owner.partials.sidebar-menu')
@endsection

@section('content')
<div style="margin-bottom: 24px;">
    @if(session('success'))
        <div style="padding: 16px; background: #dcfce7; border: 1px solid #86efac; border-radius: 12px; color: #166534; margin-bottom: 16px; display: flex; align-items: center; gap: 12px;">
            <i data-feather="check-circle" style="width: 20px; height: 20px;"></i>
            <span style="font-weight: 500;">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div style="padding: 16px; background: #fee2e2; border: 1px solid #fecaca; border-radius: 12px; color: #991b1b; margin-bottom: 16px;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                <i data-feather="alert-circle" style="width: 20px; height: 20px;"></i>
                <span style="font-weight: 600;">Gagal melakukan operasi:</span>
            </div>
            <ul style="margin: 0; padding-left: 32px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<div style="background: white; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden;">
    <!-- Filters & Search -->
    <div style="padding: 24px; border-bottom: 1px solid #f1f5f9; display: flex; flex-wrap: wrap; gap: 16px; justify-content: space-between; align-items: center;">
        <form action="{{ route('owner.pesantren.index') }}" method="GET" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center; width: 100%; max-width: 800px;">
            <div style="position: relative; flex: 1; min-width: 200px;">
                <i data-feather="search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #9ca3af;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tenant..." style="padding: 10px 10px 10px 36px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem; width: 100%; outline: none;">
            </div>
            
            <div style="display: flex; gap: 8px;">
                <select name="status" onchange="this.form.submit()" style="padding: 10px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem; background: white; color: #475569; cursor: pointer; outline: none;">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>

                <select name="package" onchange="this.form.submit()" style="padding: 10px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem; background: white; color: #475569; cursor: pointer; outline: none;">
                    <option value="">All Packages</option>
                    @foreach($packages as $pkg)
                        <option value="{{ $pkg->slug }}" {{ request('package') == $pkg->slug ? 'selected' : '' }}>{{ $pkg->name }}</option>
                    @endforeach
                </select>
            </div>
        </form>
        
                <button form="bulkDeleteForm" id="bulkDeleteBtn" type="button" onclick="confirmAction('bulkDeleteForm', 'Hapus PERMANEN semua pesantren yang dipilih? Aksi ini tidak dapat dibatalkan!', 'Yakin ingin menghapus?', 'Ya, Hapus!', '#ef4444')" style="display: none; padding: 10px 20px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none; border-radius: 10px; color: white; font-size: 0.875rem; font-weight: 600; cursor: pointer; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3); transition: all 0.2s; align-items: center;">
                    <i data-feather="trash-2" style="width: 14px; height: 14px; margin-right: 8px;"></i>
                    <span>Hapus Terpilih</span>
                </button>

                <button style="padding: 10px 20px; background: white; border: 1px solid #e2e8f0; border-radius: 8px; color: #475569; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                    Export Data
                </button>
            </div>
        
            <!-- Table -->
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                            <th style="padding: 16px 24px;">
                                <input type="checkbox" id="selectAll" onclick="toggleAll(this)" style="width: 16px; height: 16px; cursor: pointer;">
                            </th>
                            <th style="padding: 16px 24px; font-weight: 600;">Pesantren</th>
                    <th style="padding: 16px 24px; font-weight: 600;">Subdomain</th>
                    <th style="padding: 16px 24px; font-weight: 600;">Admin</th>
                    <th style="padding: 16px 24px; font-weight: 600;">Package</th>
                    <th style="padding: 16px 24px; font-weight: 600;">Status</th>
                    <th style="padding: 16px 24px; font-weight: 600;">Expired On</th>
                    <th style="padding: 16px 24px; font-weight: 600; text-align: right;">Action</th>
                </tr>
            </thead>
                <tbody style="font-size: 0.875rem; color: #1e2937;">
                    @forelse($pesantrens as $p)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                        <td style="padding: 16px 24px;">
                            <input type="checkbox" name="ids[]" value="{{ $p->id }}" form="bulkDeleteForm" class="bulk-checkbox" onchange="toggleBulkBtn()" style="width: 16px; height: 16px; cursor: pointer;">
                        </td>
                        <td style="padding: 16px 24px;">
                        <div style="font-weight: 500;">{{ $p->nama }}</div>
                        <div style="font-size: 0.75rem; color: #9ca3af;">#{{ $p->id }}</div>
                    </td>
                    <td style="padding: 16px 24px;">
                        @php $mainDomain = str_replace(['owner.', 'www.'], '', request()->getHost()); @endphp
                        <a href="{{ request()->getScheme() }}://{{ $p->subdomain }}.{{ $mainDomain }}" target="_blank" style="color: #4f46e5; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center;">
                            {{ $p->subdomain }}
                            <i data-feather="external-link" style="width: 12px; height: 12px; margin-left: 4px;"></i>
                        </a>
                    </td>
                    <td style="padding: 16px 24px;">
                        @if($p->admin)
                            <div>{{ $p->admin->name }}</div>
                            <div style="font-size: 0.75rem; color: #9ca3af;">{{ $p->admin->email }}</div>
                        @else
                            <span style="font-style: italic; color: #9ca3af;">No admin</span>
                        @endif
                    </td>
                    <td style="padding: 16px 24px;">
                        <span style="padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; text-transform: capitalize; background: #f3f4f6; color: #374151;">
                            {{ $p->package }}
                        </span>
                    </td>
                    <td style="padding: 16px 24px;">
                        @php
                            $isExpired = $p->expired_at && $p->expired_at < now();
                            $bg = '#dcfce7'; $color = '#15803d'; $label = 'Active'; // default
                            
                            if($p->status == 'suspended') {
                                $bg = '#fee2e2'; $color = '#b91c1c'; $label = 'Suspended';
                            } elseif($isExpired) {
                                $bg = '#fef3c7'; $color = '#b45309'; $label = 'Expired';
                            }
                        @endphp
                        <span style="padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background: {{ $bg }}; color: {{ $color }};">
                            {{ $label }}
                        </span>
                    </td>
                    <td style="padding: 16px 24px; color: #64748b;">
                        {{ $p->expired_at ? $p->expired_at->format('d M Y') : '-' }}
                    </td>
                    <td style="padding: 16px 24px; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 8px;">
                            <a href="{{ route('owner.pesantren.show', $p->id) }}" style="padding: 6px 12px; background: white; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.75rem; font-weight: 600; color: #475569; text-decoration: none; display: inline-block;">
                                Detail
                            </a>
                            <form action="{{ route('owner.pesantren.destroy', $p->id) }}" method="POST" onsubmit="return confirmDelete(event, 'Hapus permanen pesantren ini? Data tidak dapat dikembalikan!');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="padding: 6px 12px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; font-size: 0.75rem; font-weight: 600; color: #b91c1c; cursor: pointer;">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="8" style="padding: 48px; text-align: center;">
                        <i data-feather="inbox" style="width: 48px; height: 48px; color: #cbd5e1; margin-bottom: 12px;"></i>
                        <h3 style="margin: 0; color: #1e2937; font-size: 1rem; font-weight: 600;">No tenants found</h3>
                        <p style="margin: 4px 0 0; color: #9ca3af; font-size: 0.875rem;">Try adjusting search or filters.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Bulk Delete Form (Standalone) -->
    <form id="bulkDeleteForm" action="{{ route('owner.pesantren.bulk-destroy') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Pagination -->
    @if($pesantrens->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid #f1f5f9; background: #f8fafc;">
        {{ $pesantrens->appends(request()->query())->links() }}
    </div>
    @endif
</div>
</div>

<script>
    function toggleAll(source) {
        checkboxes = document.querySelectorAll('.bulk-checkbox');
        for(var i=0, n=checkboxes.length;i<n;i++) {
            checkboxes[i].checked = source.checked;
        }
        toggleBulkBtn();
    }

    function toggleBulkBtn() {
        const checkboxes = document.querySelectorAll('.bulk-checkbox:checked');
        const btn = document.getElementById('bulkDeleteBtn');
        if(checkboxes.length > 0) {
            btn.style.display = 'inline-flex';
        } else {
            btn.style.display = 'none';
        }
    }
</script>
@endsection
