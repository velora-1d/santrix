@extends('layouts.app')

@section('title', 'Activity Logs')
@section('page-title', 'Activity Logs')

@section('sidebar-menu')
    @include('owner.partials.sidebar-menu')
@endsection

@section('content')
<div style="background: white; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden;">
    <!-- Table -->
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    <th style="padding: 16px 24px; font-weight: 600;">Timestamp</th>
                    <th style="padding: 16px 24px; font-weight: 600;">User</th>
                    <th style="padding: 16px 24px; font-weight: 600;">Action</th>
                    <th style="padding: 16px 24px; font-weight: 600;">Subject</th>
                    <th style="padding: 16px 24px; font-weight: 600;">Details</th>
                </tr>
            </thead>
            <tbody style="font-size: 0.875rem; color: #1e2937;">
                @forelse($logs as $log)
                <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                    <td style="padding: 16px 24px; color: #64748b;">
                        {{ $log->created_at->format('d M Y H:i') }}
                    </td>
                    <td style="padding: 16px 24px;">
                        @if($log->causer)
                            <div style="font-weight: 500;">{{ $log->causer->name }}</div>
                            <div style="font-size: 0.75rem; color: #9ca3af;">{{ $log->causer->email }}</div>
                        @else
                            <span style="font-size: 0.75rem; font-style: italic; color: #9ca3af;">System</span>
                        @endif
                    </td>
                    <td style="padding: 16px 24px; color: #334155;">
                        {{ $log->description }}
                    </td>
                    <td style="padding: 16px 24px;">
                        @if($log->subject)
                            <span style="color: #4f46e5;">
                                {{ $log->subject->nama ?? 'Pesantren #'.$log->subject_id }}
                            </span>
                        @else
                            <span style="color: #9ca3af;">Deleted</span>
                        @endif
                    </td>
                    <td style="padding: 16px 24px;">
                        @if(!empty($log->properties))
                            <button type="button" onclick="alert(JSON.stringify({{ json_encode($log->properties) }}, null, 2))" style="background: none; border: none; padding: 0; color: #4f46e5; font-size: 0.75rem; cursor: pointer; text-decoration: underline;">
                                View JSON
                            </button>
                        @else
                            <span style="color: #9ca3af;">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 48px; text-align: center;">
                        <i data-feather="activity" style="width: 48px; height: 48px; color: #cbd5e1; margin-bottom: 12px;"></i>
                        <p style="margin: 0; color: #9ca3af; font-size: 0.875rem;">No activity logs yet.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($logs->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid #f1f5f9; background: #f8fafc;">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection
