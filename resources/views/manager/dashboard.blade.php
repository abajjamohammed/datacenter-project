@extends('layouts.app')

@section('title', 'Technical Manager Dashboard')

@section('styles')
    {{-- Using the same CSS folder structure we fixed --}}
    <link rel="stylesheet" href="{{ asset('dashboardcss/guestboard.css') }}">
@endsection

@section('content')
<div class="manager-dashboard">
    <h2>Overview</h2>
    
    <!-- Stats Cards -->
    <div style="display: flex; gap: 20px; margin-bottom: 30px;">
        <div class="stat-card" style="background:#e0f2fe; padding:20px; border-radius:10px; flex:1;">
            <h3>Resources Managed</h3>
            <p style="font-size: 2rem; font-weight:bold;">{{ $totalResources }}</p>
            <a href="{{ route('manager.resources.index') }}">Manage Fleet &rarr;</a>
        </div>
        <div class="stat-card" style="background:#fef3c7; padding:20px; border-radius:10px; flex:1;">
            <h3>Pending Requests</h3>
            <p style="font-size: 2rem; font-weight:bold; color:#b45309;">{{ $pendingReservations }}</p>
            <a href="{{ route('manager.reservations.index') }}">Process Requests &rarr;</a>
        </div>
        <div class="stat-card" style="background:#fee2e2; padding:20px; border-radius:10px; flex:1;">
            <h3>Active Incidents</h3>
            <p style="font-size: 2rem; font-weight:bold; color:#b91c1c;">{{ $activeIncidents }}</p>
        </div>
    </div>

    <h3>Urgent: Pending Reservations</h3>
    @if($recentRequests->count() > 0)
        <table class="resource-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Resource</th>
                    <th>Requested Dates</th>
                    <th>Justification</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentRequests as $req)
                <tr>
                    <td>{{ $req->user->name }}</td>
                    <td>{{ $req->resource->name }}</td>
                    <td>{{ $req->start_date->format('M d') }} - {{ $req->end_date->format('M d') }}</td>
                    <td>{{ Str::limit($req->justification, 30) }}</td>
                    <td>
                        <a href="{{ route('manager.reservations.index') }}" class="btn-primary-small">Review</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No pending requests. Good job!</p>
    @endif
</div>
@endsection

