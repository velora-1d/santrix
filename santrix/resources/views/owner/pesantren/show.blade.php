@extends('layouts.app')

@section('title', 'Tenant Details')
@section('page-title', 'Tenant Details')

@section('sidebar-menu')
    @include('owner.partials.sidebar-menu')
@endsection

@section('content')
<div style="max-width: 1200px; margin: 0 auto; display: grid; gap: 24px;">

    <!-- Back Link -->
    <div>
        <a href="{{ route('owner.pesantren.index') }}" style="text-decoration: none; color: #64748b; font-size: 0.875rem; display: flex; align-items: center; gap: 8px;">
            <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i>
            Back to List
        </a>
    </div>

    <!-- Header Card -->
    <div style="background: white; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); padding: 32px; overflow: hidden; position: relative;">
        <!-- Background Pattern -->
        <div style="position: absolute; right: 0; top: 0; width: 30%; height: 100%; background: linear-gradient(to left, #eff6ff, transparent); pointer-events: none;"></div>

        <div style="position: relative; z-index: 10; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 24px;">
            <div style="display: flex; align-items: center; gap: 24px;">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border-radius: 16px; color: white; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 700;">
                    {{ substr($pesantren->nama, 0, 1) }}
                </div>
                <div>
                    <h1 style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #1e2937;">{{ $pesantren->nama }}</h1>
                    <div style="margin-top: 8px; font-size: 0.875rem; color: #64748b; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                         @php
                            $mainDomain = str_replace(['owner.', 'www.'], '', request()->getHost());
                            $tenantUrl = request()->getScheme() . '://' . $pesantren->subdomain . '.' . $mainDomain;
                        @endphp
                        <a href="{{ $tenantUrl }}" target="_blank" style="background: #f1f5f9; padding: 4px 8px; border-radius: 6px; color: #4f46e5; text-decoration: none; font-family: monospace; display: flex; align-items: center; gap: 4px;">
                            {{ $pesantren->subdomain }}
                            <i data-feather="external-link" style="width: 12px; height: 12px;"></i>
                        </a>
                        <span>•</span>
                        <span>Joined {{ $pesantren->created_at->format('F Y') }}</span>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('owner.pesantren.edit', $pesantren->id) }}" style="padding: 8px 16px; background: white; border: 1px solid #e2e8f0; border-radius: 8px; color: #475569; font-weight: 600; font-size: 0.875rem; text-decoration: none;">
                    Edit Subscription
                </a>
                
                <form action="{{ route('owner.pesantren.suspend', $pesantren->id) }}" method="POST" onsubmit="return confirm('Change status?');">
                    @csrf
                    @if($pesantren->status === 'suspended')
                        <button type="submit" style="padding: 8px 16px; background: #059669; color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 0.875rem; cursor: pointer;">
                            Reactivate Tenant
                        </button>
                    @else
                        <button type="submit" style="padding: 8px 16px; background: #fef2f2; border: 1px solid #fca5a5; color: #dc2626; border-radius: 8px; font-weight: 600; font-size: 0.875rem; cursor: pointer;">
                            Suspend Access
                        </button>
                    @endif
                </form>

                <form action="{{ route('owner.pesantren.destroy', $pesantren->id) }}" method="POST" onsubmit="return confirm('PERINGATAN: Tindakan ini tidak dapat dibatalkan.\n\nApakah Anda yakin ingin MENGHAPUS PERMANEN data pesantren ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="padding: 8px 16px; background: white; border: 1px solid #fecaca; color: #b91c1c; border-radius: 8px; font-weight: 600; font-size: 0.875rem; cursor: pointer;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='white'">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px;">
        <div style="background: white; padding: 24px; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <p style="margin: 0 0 8px 0; font-size: 0.875rem; font-weight: 500; color: #64748b;">Current Package</p>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 1.25rem; font-weight: 700; color: #1e2937; text-transform: capitalize;">{{ $pesantren->package }}</span>
                <span style="padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; {{ $pesantren->package == 'enterprise' ? 'background: #f3e8ff; color: #7e22ce;' : 'background: #dbeafe; color: #1d4ed8;' }}">
                    Active
                </span>
            </div>
        </div>
        <div style="background: white; padding: 24px; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <p style="margin: 0 0 8px 0; font-size: 0.875rem; font-weight: 500; color: #64748b;">Expires On</p>
            <span style="font-size: 1.25rem; font-weight: 700; color: #1e2937;">{{ $pesantren->expired_at ? $pesantren->expired_at->format('d M Y') : '-' }}</span>
        </div>
        <div style="background: white; padding: 24px; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <p style="margin: 0 0 8px 0; font-size: 0.875rem; font-weight: 500; color: #64748b;">Total Santri</p>
            <span style="font-size: 1.25rem; font-weight: 700; color: #1e2937;">{{ $pesantren->santri_count }}</span>
        </div>
        <div style="background: white; padding: 24px; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <p style="margin: 0 0 8px 0; font-size: 0.875rem; font-weight: 500; color: #64748b;">Total Billing Paid</p>
            <span style="font-size: 1.25rem; font-weight: 700; color: #1e2937;">Rp {{ number_format($pesantren->invoices->where('status', 'paid')->sum('amount'), 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Admin & Details Split -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
        
        <!-- Admin Info -->
        <div style="background: white; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); padding: 24px; height: fit-content;">
            <h3 style="margin: 0 0 16px 0; font-size: 1.125rem; font-weight: 700; color: #1e2937;">Tenant Administrator</h3>
            @if($pesantren->admin)
                <div style="display: flex; align-items: center; gap: 16px;">
                     <div style="width: 48px; height: 48px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #64748b;">
                        {{ substr($pesantren->admin->name, 0, 2) }}
                    </div>
                    <div>
                        <p style="margin: 0; font-weight: 600; color: #1e2937;">{{ $pesantren->admin->name }}</p>
                        <p style="margin: 4px 0 0; font-size: 0.875rem; color: #64748b;">{{ $pesantren->admin->email }}</p>
                    </div>
                </div>
            @else
                <p style="color: #9ca3af; font-style: italic;">No admin user assigned.</p>
            @endif
        </div>

        <!-- Content Tabs -->
        <div style="background: white; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden;">
            <div style="border-bottom: 1px solid #f1f5f9; display: flex;">
                 <button onclick="showTab('subscriptions')" id="tab-subscriptions" style="flex: 1; padding: 16px; background: white; border: none; border-bottom: 2px solid #4f46e5; color: #4f46e5; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                    Subscription History
                </button>
                <button onclick="showTab('invoices')" id="tab-invoices" style="flex: 1; padding: 16px; background: white; border: none; border-bottom: 2px solid transparent; color: #64748b; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                    Invoices
                </button>
            </div>

            <div style="padding: 24px;">
                 <!-- Subscriptions -->
                <div id="content-subscriptions">
                    <table style="width: 100%; text-align: left; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 0.75rem; text-transform: uppercase;">
                                <th style="padding: 12px; font-weight: 600;">Package</th>
                                <th style="padding: 12px; font-weight: 600;">Period</th>
                                <th style="padding: 12px; font-weight: 600;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                             @forelse($pesantren->subscriptions as $sub)
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 12px; font-weight: 500; font-size: 0.875rem;">{{ $sub->package_name }}</td>
                                <td style="padding: 12px; font-size: 0.875rem; color: #64748b;">
                                     {{ $sub->start_date?->format('d M y') ?? '-' }} - {{ $sub->end_date?->format('d M y') ?? '-' }}
                                </td>
                                <td style="padding: 12px;">
                                    <span style="font-size: 0.75rem; padding: 2px 8px; border-radius: 4px; {{ $sub->status == 'active' ? 'background: #dcfce7; color: #15803d;' : 'background: #f1f5f9; color: #64748b;' }}">
                                        {{ $sub->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" style="padding: 24px; text-align: center; color: #9ca3af; font-size: 0.875rem;">No history available</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Invoices -->
                <div id="content-invoices" style="display: none;">
                    <table style="width: 100%; text-align: left; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 0.75rem; text-transform: uppercase;">
                                <th style="padding: 12px; font-weight: 600;">Invoice #</th>
                                <th style="padding: 12px; font-weight: 600;">Amount</th>
                                <th style="padding: 12px; font-weight: 600;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                             @forelse($pesantren->invoices as $inv)
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 12px; font-family: monospace; font-size: 0.875rem;">{{ $inv->invoice_number }}</td>
                                <td style="padding: 12px; font-size: 0.875rem; font-weight: 500;">Rp {{ number_format($inv->amount, 0, ',', '.') }}</td>
                                <td style="padding: 12px;">
                                    <span style="font-size: 0.75rem; padding: 2px 8px; border-radius: 4px; {{ $inv->status == 'paid' ? 'background: #dcfce7; color: #15803d;' : 'background: #fef3c7; color: #b45309;' }}">
                                        {{ $inv->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" style="padding: 24px; text-align: center; color: #9ca3af; font-size: 0.875rem;">No invoices generated</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function showTab(tabName) {
    const tabs = ['subscriptions', 'invoices'];
    tabs.forEach(t => {
        document.getElementById('content-' + t).style.display = (t === tabName) ? 'block' : 'none';
        
        const btn = document.getElementById('tab-' + t);
        if (t === tabName) {
            btn.style.color = '#4f46e5';
            btn.style.borderBottomColor = '#4f46e5';
        } else {
            btn.style.color = '#64748b';
            btn.style.borderBottomColor = 'transparent';
        }
    });
}
</script>
@endsection
