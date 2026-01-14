@extends('layouts.app')

@section('title', 'Edit Subscription')
@section('page-title', 'Edit Subscription')

@section('sidebar-menu')
    @include('owner.partials.sidebar-menu')
@endsection

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="background: white; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); padding: 32px; overflow: hidden;">
        <h3 style="margin: 0 0 24px 0; font-size: 1.25rem; font-weight: 700; color: #1e2937;">Update Subscription</h3>
        
        <form action="{{ route('owner.pesantren.update', $pesantren->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Package Selection -->
            <div style="margin-bottom: 24px;">
                <label style="display: block; margin-bottom: 12px; font-weight: 600; font-size: 0.875rem; color: #374151;">Subscription Package</label>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                    
                    @foreach(['basic' => 'Foundational features', 'advance' => 'Extended limits', 'enterprise' => 'Full access'] as $key => $desc)
                    <label style="cursor: pointer; position: relative;">
                        <input type="radio" name="package" value="{{ $key }}" {{ $pesantren->package == $key ? 'checked' : '' }} style="position: absolute; opacity: 0;" onchange="updateSelection(this)">
                        <div id="card-{{ $key }}" style="padding: 16px; border-radius: 12px; border: 2px solid {{ $pesantren->package == $key ? '#4f46e5' : '#e2e8f0' }}; background: {{ $pesantren->package == $key ? '#eef2ff' : 'white' }}; transition: all 0.2s;">
                            <h3 style="margin: 0; font-size: 1rem; font-weight: 700; color: {{ $pesantren->package == $key ? '#4338ca' : '#1e2937' }}; text-transform: capitalize;">{{ $key }}</h3>
                            <p style="margin: 4px 0 0; font-size: 0.75rem; color: #64748b;">{{ $desc }}</p>
                        </div>
                    </label>
                    @endforeach

                </div>
            </div>

            <!-- Expiry Date -->
            <div style="margin-bottom: 24px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.875rem; color: #374151;">Expiry Date</label>
                <input type="date" name="expired_at" value="{{ $pesantren->expired_at ? \Carbon\Carbon::parse($pesantren->expired_at)->format('Y-m-d') : '' }}" style="width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; outline: none; transition: border-color 0.2s;" class="datepicker-input">
                <p style="margin: 4px 0 0; font-size: 0.75rem; color: #64748b;">Set date when subscription ends.</p>
            </div>

            <!-- Bank Details -->
            <div id="bank-section" style="background: #eef2ff; border: 1px solid #c7d2fe; border-radius: 12px; padding: 24px; margin-bottom: 24px; display: none;">
                <h4 style="margin: 0 0 16px 0; font-size: 0.875rem; font-weight: 700; color: #312e81; display: flex; align-items: center; gap: 8px;">
                    <i data-feather="credit-card" style="width: 16px; height: 16px;"></i>
                    Rekening Pencairan Dana (Wajib untuk Advance)
                </h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #3730a3; margin-bottom: 4px;">Nama Bank</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', $pesantren->bank_name) }}" placeholder="Contoh: BCA, BSI" style="width: 100%; padding: 8px 12px; border: 1px solid #c7d2fe; rounded: 8px; font-size: 0.875rem; outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #3730a3; margin-bottom: 4px;">No. Rekening</label>
                        <input type="text" name="account_number" value="{{ old('account_number', $pesantren->account_number) }}" style="width: 100%; padding: 8px 12px; border: 1px solid #c7d2fe; rounded: 8px; font-size: 0.875rem; outline: none;">
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #3730a3; margin-bottom: 4px;">Atas Nama</label>
                        <input type="text" name="account_name" value="{{ old('account_name', $pesantren->account_name) }}" style="width: 100%; padding: 8px 12px; border: 1px solid #c7d2fe; rounded: 8px; font-size: 0.875rem; outline: none;">
                    </div>
                </div>
                <p style="margin: 12px 0 0; font-size: 0.75rem; color: #4338ca;">*Dana dari Payment Gateway Syahriah akan dicairkan ke rekening ini.</p>
            </div>

            <!-- Status -->
            <div style="margin-bottom: 32px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.875rem; color: #374151;">Tenant Status</label>
                <select name="status" style="width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; outline: none; background: white;">
                    <option value="active" {{ $pesantren->status == 'active' ? 'selected' : '' }}>Active (Allow Access)</option>
                    <option value="suspended" {{ $pesantren->status == 'suspended' ? 'selected' : '' }}>Suspended (Block Access)</option>
                </select>
            </div>

            <!-- Actions -->
            <div style="padding-top: 24px; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 12px;">
                <a href="{{ route('owner.pesantren.show', $pesantren->id) }}" style="padding: 10px 20px; text-decoration: none; color: #475569; font-weight: 500; font-size: 0.875rem; background: white; border: 1px solid #e2e8f0; border-radius: 8px;">
                    Cancel
                </a>
                <button type="submit" style="padding: 10px 20px; background: #4f46e5; color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 0.875rem; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);">
                    Update Subscription
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateSelection(radio) {
        // Reset all styling
        ['basic', 'advance', 'enterprise'].forEach(key => {
            const card = document.getElementById('card-' + key);
            card.style.borderColor = '#e2e8f0';
            card.style.background = 'white';
            card.querySelector('h3').style.color = '#1e2937';
        });

        // Apply active styling
        const activeCard = document.getElementById('card-' + radio.value);
        if(activeCard) {
            activeCard.style.borderColor = '#4f46e5';
            activeCard.style.background = '#eef2ff';
            activeCard.querySelector('h3').style.color = '#4338ca';
        }

        toggleBank();
    }

    function toggleBank() {
        const selectedRadio = document.querySelector('input[name="package"]:checked');
        const bankSection = document.getElementById('bank-section');
        
        if (!selectedRadio) return;
        
        const selected = selectedRadio.value;
        const hasExistingData = '{{ $pesantren->bank_name }}' !== '';

        if(selected === 'advance' || selected === 'enterprise' || hasExistingData) {
             bankSection.style.display = 'block';
        } else {
             bankSection.style.display = 'none';
        }
    }
    
    // Init on load
    document.addEventListener('DOMContentLoaded', toggleBank);
</script>
@endsection
