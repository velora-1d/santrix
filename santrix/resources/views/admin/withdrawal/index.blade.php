@extends('layouts.app')

@section('title', 'Pencairan Dana')
@section('page-title', 'Withdrawal Saldo')

@section('sidebar-menu')
    @include('admin.partials.sidebar-menu')
@endsection

@section('content')
<div style="padding: 24px; max-width: 1400px; margin: 0 auto;">

    <!-- Alert Messages -->
    @if(session('success'))
        <div style="background: #ecfdf5; color: #047857; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; border: 1px solid #a7f3d0;">
            <i data-feather="check-circle" style="width: 24px; height: 24px;"></i>
            <span style="font-weight: 600;">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fef2f2; color: #b91c1c; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; border: 1px solid #fecaca;">
            <i data-feather="alert-circle" style="width: 24px; height: 24px;"></i>
            <span style="font-weight: 600;">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Header with WD Button -->
    <div style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px; color: white; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -30px; right: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -40px; left: 30%; width: 100px; height: 100px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
        
        <div style="position: relative; z-index: 1; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 24px;">
            <div>
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
                    <div style="width: 56px; height: 56px; background: rgba(255,255,255,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                        <i data-feather="download" style="width: 28px; height: 28px;"></i>
                    </div>
                    <div>
                        <h1 style="font-size: 1.75rem; font-weight: 800; margin: 0;">Saldo Payment Gateway</h1>
                        <p style="font-size: 0.875rem; opacity: 0.9; margin: 4px 0 0 0;">Kelola pencairan dana dari hasil pembayaran Syahriah</p>
                    </div>
                </div>
                <div style="display: flex; align-items: baseline; gap: 8px;">
                    <span style="font-size: 0.875rem; font-weight: 500;">Rp</span>
                    <span style="font-size: 2.5rem; font-weight: 900;">{{ number_format($pesantren->saldo_pg ?? 0, 0, ',', '.') }}</span>
                </div>
                <p style="font-size: 0.8rem; opacity: 0.8; margin-top: 8px;">Siap dicairkan ke rekening terdaftar.</p>
            </div>
            
            <!-- BIG Withdraw Button -->
            @if($pesantren->saldo_pg >= 50000 && $pesantren->bank_name)
            <button onclick="document.getElementById('withdrawModal').showModal()" style="background: white; color: #6366f1; padding: 16px 32px; border: none; border-radius: 14px; font-weight: 800; font-size: 1.125rem; cursor: pointer; display: flex; align-items: center; gap: 10px; box-shadow: 0 8px 24px rgba(0,0,0,0.2); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                <i data-feather="arrow-down-circle" style="width: 24px; height: 24px;"></i>
                Tarik Dana Sekarang
            </button>
            @else
            <div style="background: rgba(255,255,255,0.15); padding: 16px 24px; border-radius: 14px; border: 1px dashed rgba(255,255,255,0.4);">
                <p style="margin: 0; font-size: 0.875rem; opacity: 0.9;">
                    @if(!$pesantren->bank_name)
                        ⚠️ Tambahkan rekening dulu
                    @else
                        ⚠️ Saldo minimal Rp 50.000
                    @endif
                </p>
            </div>
            @endif
        </div>
    </div>

    <!-- Bank Info & Form -->
    <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); margin-bottom: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div>
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0;">Rekening Penerima</h3>
                <p style="font-size: 0.875rem; color: #64748b; margin: 4px 0 0 0;">Dana akan ditransfer ke rekening ini</p>
            </div>
            @if($pesantren->bank_name)
            <button onclick="toggleBankForm()" style="background: #f1f5f9; color: #6366f1; padding: 10px 20px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i data-feather="edit-2" style="width: 16px; height: 16px;"></i>
                Ubah Rekening
            </button>
            @endif
        </div>

        @if($pesantren->bank_name)
        <!-- Display Current Bank Info -->
        <div id="bankDisplay" style="display: flex; align-items: center; gap: 20px; padding: 24px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 14px; border: 1px solid #e2e8f0;">
            <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                <i data-feather="credit-card" style="width: 28px; height: 28px; color: white;"></i>
            </div>
            <div style="flex: 1;">
                <p style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0;">{{ $pesantren->bank_name }}</p>
                <p style="font-size: 1.125rem; color: #475569; margin: 6px 0 0 0; font-family: monospace; letter-spacing: 1px;">{{ $pesantren->account_number }}</p>
                <p style="font-size: 0.9rem; color: #64748b; margin: 6px 0 0 0;">a.n. <strong>{{ $pesantren->account_name }}</strong></p>
            </div>
            <div style="background: #d1fae5; color: #047857; padding: 8px 16px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">
                ✓ Terverifikasi
            </div>
        </div>
        @endif

        <!-- Bank Form (Hidden by default if bank exists) -->
        <div id="bankForm" style="display: {{ $pesantren->bank_name ? 'none' : 'block' }};">
            @if(!$pesantren->bank_name)
            <div style="background: #fef3c7; border: 1px solid #fde68a; border-radius: 12px; padding: 16px 20px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
                <i data-feather="alert-triangle" style="width: 24px; height: 24px; color: #f59e0b;"></i>
                <p style="color: #92400e; margin: 0;">Lengkapi data rekening untuk bisa melakukan penarikan dana.</p>
            </div>
            @endif

            <form action="{{ route('admin.withdrawal.update-bank') }}" method="POST" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                @csrf
                <div>
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 0.875rem;">Nama Bank</label>
                    <select id="bankSelect" onchange="toggleCustomBank()" style="width: 100%; padding: 14px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 1rem; background: white;">
                        <option value="">Pilih Bank...</option>
                        <option value="BCA" {{ $pesantren->bank_name == 'BCA' ? 'selected' : '' }}>BCA</option>
                        <option value="BNI" {{ $pesantren->bank_name == 'BNI' ? 'selected' : '' }}>BNI</option>
                        <option value="BRI" {{ $pesantren->bank_name == 'BRI' ? 'selected' : '' }}>BRI</option>
                        <option value="Mandiri" {{ $pesantren->bank_name == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                        <option value="BSI" {{ $pesantren->bank_name == 'BSI' ? 'selected' : '' }}>BSI (Bank Syariah Indonesia)</option>
                        <option value="CIMB Niaga" {{ $pesantren->bank_name == 'CIMB Niaga' ? 'selected' : '' }}>CIMB Niaga</option>
                        <option value="Permata" {{ $pesantren->bank_name == 'Permata' ? 'selected' : '' }}>Permata</option>
                        <option value="BTN" {{ $pesantren->bank_name == 'BTN' ? 'selected' : '' }}>BTN</option>
                        <option value="Danamon" {{ $pesantren->bank_name == 'Danamon' ? 'selected' : '' }}>Danamon</option>
                        <option value="OCBC NISP" {{ $pesantren->bank_name == 'OCBC NISP' ? 'selected' : '' }}>OCBC NISP</option>
                        <option value="SEABANK" {{ $pesantren->bank_name == 'SEABANK' ? 'selected' : '' }}>SEABANK</option>
                        <option value="Jago" {{ $pesantren->bank_name == 'Jago' ? 'selected' : '' }}>Bank Jago</option>
                        <option value="Muamalat" {{ $pesantren->bank_name == 'Muamalat' ? 'selected' : '' }}>Bank Muamalat</option>
                        <option value="BPD" {{ $pesantren->bank_name == 'BPD' ? 'selected' : '' }}>Bank BPD</option>
                        @php
                            $knownBanks = ['BCA', 'BNI', 'BRI', 'Mandiri', 'BSI', 'CIMB Niaga', 'Permata', 'BTN', 'Danamon', 'OCBC NISP', 'SEABANK', 'Jago', 'Muamalat', 'BPD'];
                            $isCustomBank = $pesantren->bank_name && !in_array($pesantren->bank_name, $knownBanks);
                        @endphp
                        <option value="__custom__" {{ $isCustomBank ? 'selected' : '' }}>🏦 Lainnya (Tulis Manual)</option>
                    </select>
                    <input type="hidden" name="bank_name" id="bankNameInput" value="{{ $pesantren->bank_name }}" required>
                    <input type="text" id="customBankInput" placeholder="Ketik nama bank..." value="{{ $isCustomBank ? $pesantren->bank_name : '' }}" style="width: 100%; padding: 14px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 1rem; margin-top: 10px; display: {{ $isCustomBank ? 'block' : 'none' }};">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 0.875rem;">Nomor Rekening</label>
                    <input type="text" name="account_number" value="{{ $pesantren->account_number }}" required placeholder="Contoh: 1234567890" style="width: 100%; padding: 14px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 1rem;">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 0.875rem;">Nama Pemilik Rekening</label>
                    <input type="text" name="account_name" value="{{ $pesantren->account_name }}" required placeholder="Nama sesuai buku tabungan" style="width: 100%; padding: 14px 16px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 1rem;">
                </div>
                <div style="grid-column: span 3; display: flex; gap: 12px; justify-content: flex-end;">
                    @if($pesantren->bank_name)
                    <button type="button" onclick="toggleBankForm()" style="background: #f1f5f9; color: #64748b; padding: 12px 24px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">Batal</button>
                    @endif
                    <button type="submit" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; padding: 12px 32px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        💾 Simpan Rekening
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- History Table -->
    <div style="background: white; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); overflow: hidden;">
        <div style="padding: 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0;">Riwayat Penarikan</h3>
                <p style="font-size: 0.875rem; color: #64748b; margin: 4px 0 0 0;">Semua permintaan pencairan dana Anda</p>
            </div>
            <div style="background: #f5f3ff; color: #7c3aed; padding: 6px 16px; border-radius: 20px; font-size: 0.875rem; font-weight: 600;">
                {{ $withdrawals->total() }} Transaksi
            </div>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th style="padding: 14px 24px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal</th>
                        <th style="padding: 14px 24px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Jumlah</th>
                        <th style="padding: 14px 24px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                        <th style="padding: 14px 24px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Rekening Tujuan</th>
                        <th style="padding: 14px 24px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Catatan Admin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $item)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 16px 24px;">
                            <p style="font-weight: 600; color: #1e293b; margin: 0;">{{ $item->created_at->format('d M Y') }}</p>
                            <p style="font-size: 0.75rem; color: #94a3b8; margin: 2px 0 0 0;">{{ $item->created_at->format('H:i') }} WIB</p>
                        </td>
                        <td style="padding: 16px 24px;">
                            <span style="font-size: 1rem; font-weight: 700; color: #1e293b;">Rp {{ number_format($item->amount, 0, ',', '.') }}</span>
                        </td>
                        <td style="padding: 16px 24px;">
                            @if($item->status == 'pending')
                                <span style="background: #fef3c7; color: #92400e; padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">⏳ Menunggu</span>
                            @elseif($item->status == 'approved')
                                <span style="background: #d1fae5; color: #047857; padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">✓ Berhasil</span>
                            @else
                                <span style="background: #fee2e2; color: #b91c1c; padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">✕ Ditolak</span>
                            @endif
                        </td>
                        <td style="padding: 16px 24px;">
                            <p style="color: #475569; margin: 0;">{{ $item->bank_name }}</p>
                            <p style="font-size: 0.875rem; color: #94a3b8; margin: 2px 0 0 0;">{{ $item->account_number }}</p>
                        </td>
                        <td style="padding: 16px 24px;">
                            <p style="color: #64748b; font-style: italic; margin: 0;">{{ $item->admin_note ?? '-' }}</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 60px 24px; text-align: center;">
                            <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                                <i data-feather="inbox" style="width: 40px; height: 40px; color: #94a3b8;"></i>
                            </div>
                            <p style="font-size: 1rem; font-weight: 600; color: #64748b; margin: 0 0 4px 0;">Belum Ada Riwayat</p>
                            <p style="font-size: 0.875rem; color: #94a3b8; margin: 0;">Anda belum pernah melakukan penarikan dana.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($withdrawals->hasPages())
        <div style="padding: 16px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0;">
            {{ $withdrawals->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Withdrawal Modal -->
<dialog id="withdrawModal" style="border: none; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,0.25); padding: 0; max-width: 480px; width: 90%;">
    <div style="background: white; padding: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h3 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin: 0;">Tarik Dana</h3>
            <button onclick="document.getElementById('withdrawModal').close()" style="background: #f1f5f9; border: none; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                <i data-feather="x" style="width: 20px; height: 20px; color: #64748b;"></i>
            </button>
        </div>

        <form action="{{ route('admin.withdrawal.store') }}" method="POST">
            @csrf
            
            <div style="background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); border: 1px solid #c7d2fe; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                <p style="color: #4338ca; font-size: 0.875rem; margin: 0 0 4px 0;">Saldo Tersedia</p>
                <p style="font-size: 1.5rem; font-weight: 800; color: #4f46e5; margin: 0;">Rp {{ number_format($pesantren->saldo_pg ?? 0, 0, ',', '.') }}</p>
            </div>

            <div style="background: #f8fafc; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                <p style="font-size: 0.75rem; color: #64748b; margin: 0 0 4px 0;">Transfer ke:</p>
                <p style="font-weight: 700; color: #1e293b; margin: 0;">{{ $pesantren->bank_name }} - {{ $pesantren->account_number }}</p>
                <p style="font-size: 0.875rem; color: #64748b; margin: 2px 0 0 0;">a.n. {{ $pesantren->account_name }}</p>
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Jumlah Penarikan</label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #64748b; font-weight: 500;">Rp</span>
                    <input type="number" name="amount" min="50000" max="{{ $pesantren->saldo_pg ?? 0 }}" required placeholder="Masukkan jumlah" style="width: 100%; padding: 14px 16px 14px 48px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 1rem; font-weight: 600;">
                </div>
                <p style="font-size: 0.75rem; color: #64748b; margin: 8px 0 0 0;">Minimal Rp 50.000</p>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="button" onclick="document.getElementById('withdrawModal').close()" style="flex: 1; background: #f1f5f9; color: #64748b; padding: 14px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer;">Batal</button>
                <button type="submit" style="flex: 1; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; padding: 14px; border: none; border-radius: 12px; font-weight: 600; cursor: pointer;">Kirim Permintaan</button>
            </div>
        </form>
    </div>
</dialog>

<style>
    dialog::backdrop {
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
    }
</style>

<script>
function toggleBankForm() {
    const display = document.getElementById('bankDisplay');
    const form = document.getElementById('bankForm');
    if (display.style.display === 'none') {
        display.style.display = 'flex';
        form.style.display = 'none';
    } else {
        display.style.display = 'none';
        form.style.display = 'block';
    }
}

function toggleCustomBank() {
    const select = document.getElementById('bankSelect');
    const customInput = document.getElementById('customBankInput');
    const hiddenInput = document.getElementById('bankNameInput');
    
    if (select.value === '__custom__') {
        customInput.style.display = 'block';
        customInput.required = true;
        customInput.focus();
        hiddenInput.value = customInput.value;
    } else {
        customInput.style.display = 'none';
        customInput.required = false;
        hiddenInput.value = select.value;
    }
}

// Sync custom bank input to hidden field
document.getElementById('customBankInput')?.addEventListener('input', function() {
    document.getElementById('bankNameInput').value = this.value;
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleCustomBank();
});
</script>
@endsection
