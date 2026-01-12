@extends('layouts.app')

@section('title', 'Schedule Maintenance')

@section('content')
<div style="max-width: 700px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #2c3e50; margin:0;">Schedule Maintenance</h2>
        <a href="{{ route('admin.maintenances.index') }}" style="color: #7f8c8d; text-decoration: none;">&larr; Back to Schedule</a>
    </div>

    <form action="{{ route('admin.maintenances.store') }}" method="POST">
        @csrf

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Select Resource</label>
            <select name="resource_id" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; background: white;">
                <option value="">-- Choose a resource --</option>
                @foreach($resources as $res)
                    <option value="{{ $res->id }}">
                        {{ $res->name }} ({{ $res->resource_status }})
                    </option>
                @endforeach
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Start Date & Time</label>
                <input type="datetime-local" name="start_date" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">End Date & Time</label>
                <input type="datetime-local" name="end_date" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
        </div>

        <div style="margin-bottom: 30px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Reason for Maintenance</label>
            <textarea name="description" rows="4" placeholder="e.g. Firmware upgrade and fan replacement" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; resize: vertical;"></textarea>
        </div>

        <div style="text-align: right;">
            <button type="submit" class="btn-primary-small" style="padding: 12px 30px; font-size: 1rem;">
                <i class="fas fa-calendar-check"></i> Schedule Maintenance
            </button>
        </div>
    </form>
</div>
@endsection