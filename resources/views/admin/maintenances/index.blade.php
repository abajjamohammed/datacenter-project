@extends('layouts.app')

@section('title', 'Maintenance Schedule')

@section('styles')
<style>
    .header-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .table-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
    th { background: #f8f9fa; color: #555; text-transform: uppercase; font-size: 0.85rem; }
    
    .status-badge { padding: 5px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; }
    .status-active { background: #fdedec; color: #e74c3c; animation: pulse 2s infinite; }
    .status-future { background: #ebf5fb; color: #3498db; }
    .status-past { background: #f2f4f6; color: #95a5a6; }

    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.6; } 100% { opacity: 1; } }
</style>
@endsection

@section('content')
<div class="header-controls">
    <h2 style="color: #2c3e50;"><i class="fas fa-tools"></i> Maintenance Schedule</h2>
    <a href="{{ route('admin.maintenances.create') }}" class="btn-primary-small" style="text-decoration: none; padding: 10px 20px;">
        + Schedule Maintenance
    </a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Resource</th>
                <th>Status</th>
                <th>Duration</th>
                <th>Reason</th>
                <th>Scheduled By</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($maintenances as $maintenance)
                @php
                    $now = now();
                    if($now >= $maintenance->start_date && $now <= $maintenance->end_date) {
                        $status = 'Active Now';
                        $class = 'status-active';
                    } elseif ($now < $maintenance->start_date) {
                        $status = 'Upcoming';
                        $class = 'status-future';
                    } else {
                        $status = 'Completed';
                        $class = 'status-past';
                    }
                @endphp
            <tr>
                <td>
                    <strong>{{ $maintenance->resource->name }}</strong><br>
                    <span style="font-size: 0.8rem; color: #7f8c8d;">{{ $maintenance->resource->location }}</span>
                </td>
                <td><span class="status-badge {{ $class }}">{{ $status }}</span></td>
                <td>
                    <div style="font-size: 0.9rem;">
                        <i class="fas fa-play" style="color: #27ae60; font-size: 0.7rem;"></i> {{ $maintenance->start_date->format('M d, H:i') }}<br>
                        <i class="fas fa-stop" style="color: #e74c3c; font-size: 0.7rem;"></i> {{ $maintenance->end_date->format('M d, H:i') }}
                    </div>
                </td>
                <td style="max-width: 300px;">{{ $maintenance->description }}</td>
                <td>{{ $maintenance->creator->name }}</td>
                <td>
                    <form action="{{ route('admin.maintenances.destroy', $maintenance->id) }}" method="POST" onsubmit="return confirm('Cancel this maintenance?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background:none; border:none; cursor:pointer; color: #e74c3c;" title="Cancel / Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 30px; color: #999;">
                    No maintenance periods scheduled.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 20px;">{{ $maintenances->links() }}</div>
</div>
@endsection