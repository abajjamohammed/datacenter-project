@extends('layouts.app')

@section('title', 'Global Logs')

@section('styles')
<style>
    .header-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .table-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    
    table { width: 100%; border-collapse: separate; border-spacing: 0; }
    th { padding: 15px; text-align: left; color: #7f8c8d; text-transform: uppercase; font-size: 0.8rem; border-bottom: 2px solid #eee; }
    td { padding: 15px; background: white; border-bottom: 1px solid #f1f1f1; color: #333; font-size: 0.9rem; }
    
    /* Action Badges */
    .log-badge { padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    
    .log-create { background: #e8f8f5; color: #27ae60; border: 1px solid #27ae60; }
    .log-update { background: #ebf5fb; color: #3498db; border: 1px solid #3498db; }
    .log-delete { background: #fdedec; color: #e74c3c; border: 1px solid #e74c3c; }
    .log-auth   { background: #f4ecf7; color: #8e44ad; border: 1px solid #8e44ad; }
    .log-default{ background: #f8f9fa; color: #7f8c8d; border: 1px solid #ccc; }

    .user-avatar { width: 30px; height: 30px; background: #ecf0f1; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem; color: #7f8c8d; margin-right: 10px; font-weight: bold;}
</style>
@endsection

@section('content')
<div class="header-controls">
    <div>
        <h2 style="color: #2c3e50; margin: 0;"><i class="fas fa-history"></i> Global Activity Logs</h2>
        <p style="color: #95a5a6; margin: 5px 0 0; font-size: 0.9rem;">Audit trail of system actions</p>
    </div>
</div>

<div class="table-container">
    {{-- Search --}}
    <form action="{{ route('admin.logs.index') }}" method="GET" style="margin-bottom: 20px; position: relative;">
        <i class="fas fa-search" style="position: absolute; left: 15px; top: 12px; color: #ccc;"></i>
        <input type="text" name="search" value="{{ $search }}" placeholder="Search logs (user, action, description)..." style="padding: 10px 10px 10px 40px; border: 1px solid #eee; border-radius: 30px; width: 100%; max-width: 400px; outline: none;">
    </form>

    <table cellspacing="0">
        <thead>
            <tr>
                <th>User</th>
                <th>Action</th>
                <th>Description</th>
                <th>IP Address</th>
                <th>Date & Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr>
                <td>
                    <div style="display: flex; align-items: center;">
                        <div class="user-avatar">
                            {{ $log->user ? substr($log->user->name, 0, 1) : '?' }}
                        </div>
                        <span style="font-weight: 600;">{{ $log->user->name ?? 'System/Deleted' }}</span>
                    </div>
                </td>
                <td>
                    @php
                        $actionLower = strtolower($log->action);
                        $badgeClass = 'log-default';
                        if(str_contains($actionLower, 'create') || str_contains($actionLower, 'add')) $badgeClass = 'log-create';
                        elseif(str_contains($actionLower, 'update') || str_contains($actionLower, 'edit')) $badgeClass = 'log-update';
                        elseif(str_contains($actionLower, 'delete') || str_contains($actionLower, 'remove')) $badgeClass = 'log-delete';
                        elseif(str_contains($actionLower, 'login') || str_contains($actionLower, 'logout')) $badgeClass = 'log-auth';
                    @endphp
                    <span class="log-badge {{ $badgeClass }}">{{ $log->action }}</span>
                </td>
                <td style="color: #555;">{{ $log->description }}</td>
                <td style="font-family: monospace; color: #7f8c8d;">{{ $log->ip_address }}</td>
                <td style="color: #95a5a6; font-size: 0.85rem;">
                    {{ $log->created_at->format('M d, Y H:i') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 40px; color: #999;">
                    <i class="fas fa-clipboard-list" style="font-size: 2rem; margin-bottom: 10px; opacity: 0.5;"></i><br>
                    No activity logs found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $logs->links() }}
    </div>
</div>
@endsection