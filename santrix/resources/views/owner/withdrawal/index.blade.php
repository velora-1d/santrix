@extends('layouts.app')

@section('title', 'Investigasi Pencairan')
@section('page-title', 'Investigasi Pencairan')

@section('sidebar-menu')
    @include('owner.partials.sidebar-menu')
@endsection

@section('content')
<div style="background: white; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden;">
    <div style="padding: 24px; border-bottom: 1px solid #f1f5f9; background: #f8fafc;">
        <h3 style="margin: 0; font-size: 1.125rem; font-weight: 700; color: #1e2937;">Daftar Permintaan</h3>
    </div>
        
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    <th style="padding: 16px 24px; font-weight: 600;">Pesantren</th>
                    <th style="padding: 16px 24px; font-weight: 600;">Tanggal</th>
                    <th style="padding: 16px 24px; font-weight: 600;">Jumlah</th>
                    <th style="padding: 16px 24px; font-weight: 600;">Rekening Tujuan</th>
                    <th style="padding: 16px 24px; font-weight: 600;">Status / Aksi</th>
                </tr>
            </thead>
            <tbody style="font-size: 0.875rem; color: #1e2937;">
                @forelse($withdrawals as $item)
                <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                    <td style="padding: 16px 24px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: #e0e7ff; color: #4338ca; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;">
                                {{ substr($item->pesantren->nama ?? '?', 0, 1) }}
                            </div>
                            <div>
                                <div style="font-weight: 500;">{{ $item->pesantren->nama ?? 'Unknown' }}</div>
                                <div style="font-size: 0.75rem; color: #9ca3af;">{{ $item->pesantren->nspp ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 16px 24px; color: #64748b;">
                        {{ $item->created_at->format('d M Y H:i') }}
                    </td>
                    <td style="padding: 16px 24px; font-weight: 600; color: #1e2937;">
                        Rp {{ number_format($item->amount, 0, ',', '.') }}
                    </td>
                    <td style="padding: 16px 24px; color: #64748b;">
                        <div style="font-weight: 500; color: #1e2937;">{{ $item->bank_name }}</div>
                        <div style="font-size: 0.75rem;">{{ $item->account_number }} ({{ $item->account_name }})</div>
                    </td>
                    <td style="padding: 16px 24px;">
                        @if($item->status == 'pending')
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <!-- Approve Form -->
                                <form action="{{ route('owner.withdrawal.update', $item->id) }}" method="POST" onsubmit="return confirm('Setujui pencairan ini?');">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" style="padding: 6px 12px; background: #059669; color: white; border: none; border-radius: 6px; font-size: 0.75rem; font-weight: 600; cursor: pointer;">
                                        Setujui
                                    </button>
                                </form>

                                <!-- Reject Button -->
                                <button onclick="openRejectModal('{{ $item->id }}', '{{ $item->pesantren->nama }}', '{{ number_format($item->amount) }}')" 
                                    style="padding: 6px 12px; background: #dc2626; color: white; border: none; border-radius: 6px; font-size: 0.75rem; font-weight: 600; cursor: pointer;">
                                    Tolak
                                </button>
                            </div>
                        @elseif($item->status == 'approved')
                            <div>
                                <span style="padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background: #dcfce7; color: #15803d;">
                                    Berhasil
                                </span>
                                <div style="font-size: 0.65rem; color: #9ca3af; margin-top: 4px;">{{ $item->updated_at->format('d/m/Y') }}</div>
                            </div>
                        @else
                            <div>
                                <span style="padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background: #fee2e2; color: #b91c1c;">
                                    Ditolak
                                </span>
                                <div style="font-size: 0.65rem; color: #9ca3af; margin-top: 4px;">Note: {{ $item->admin_note }}</div>
                            </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 48px; text-align: center;">
                        <i data-feather="inbox" style="width: 48px; height: 48px; color: #cbd5e1; margin-bottom: 12px;"></i>
                        <p style="margin: 0; color: #9ca3af; font-size: 0.875rem;">Belum ada permintaan pencairan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($withdrawals->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid #f1f5f9; background: #f8fafc;">
        {{ $withdrawals->links() }}
    </div>
    @endif
</div>

<!-- Reject Modal -->
<dialog id="rejectModal" style="border: none; border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); padding: 0; max-width: 450px; width: 90%;">
    <div style="background: white; padding: 24px;">
        <h3 style="margin: 0 0 16px 0; font-size: 1.125rem; font-weight: 700; color: #1e2937;">Tolak Permintaan</h3>
        <p style="margin-bottom: 16px; font-size: 0.875rem; color: #475569;">Saldo akan dikembalikan ke Pesantren <span id="rejectPesantrenName" style="font-weight: 700;"></span>.</p>
        
        <form id="rejectForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="rejected">
            
            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 4px;">Alasan Penolakan</label>
                <textarea name="admin_note" rows="3" required style="width: 100%; padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 8px; outline: none; font-size: 0.875rem;" placeholder="Contoh: Nama rekening tidak sesuai..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" onclick="document.getElementById('rejectModal').close()" style="padding: 8px 16px; background: white; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem; font-weight: 500; color: #475569; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 8px 16px; background: #dc2626; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 500; color: white; cursor: pointer;">Tolak Permintaan</button>
            </div>
        </form>
    </div>
</dialog>

<script>
    function openRejectModal(id, pesantrenName, amount) {
        document.getElementById('rejectPesantrenName').innerText = pesantrenName;
        const form = document.getElementById('rejectForm');
        form.action = "/owner/withdrawal/" + id; 
        
        document.getElementById('rejectModal').showModal();
    }
</script>

<style>
    dialog::backdrop {
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(2px);
    }
</style>
@endsection
