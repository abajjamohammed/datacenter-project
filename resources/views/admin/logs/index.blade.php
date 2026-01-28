@extends('layouts.app')

@section('title', 'Global Logs')

@section('styles')
    <link rel="stylesheet" href="{{ asset('admin.css') }}">
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
            <input type="text" name="search" value="{{ $search }}"
                placeholder="Search logs (user, action, description)..."
                style="padding: 10px 10px 10px 40px; border: 1px solid #eee; border-radius: 30px; width: 100%; max-width: 400px; outline: none;">
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
                                if (str_contains($actionLower, 'create') || str_contains($actionLower, 'add')) {
                                    $badgeClass = 'log-create';
                                } elseif (
                                    str_contains($actionLower, 'update') ||
                                    str_contains($actionLower, 'edit') ||
                                    str_contains($actionLower, 'status')
                                ) {
                                    $badgeClass = 'log-update';
                                } elseif (
                                    str_contains($actionLower, 'delete') ||
                                    str_contains($actionLower, 'remove')
                                ) {
                                    $badgeClass = 'log-delete';
                                } elseif (str_contains($actionLower, 'login') || str_contains($actionLower, 'logout')) {
                                    $badgeClass = 'log-auth';
                                }
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
                            <i class="fas fa-clipboard-list"
                                style="font-size: 2rem; margin-bottom: 10px; opacity: 0.5;"></i><br>
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
