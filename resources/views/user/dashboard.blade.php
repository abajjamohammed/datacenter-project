@extends('layouts.app')
{{--created by mohammed 11/01--}}
@section('title', 'My Dashboard')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboardcss/guestboard.css') }}">
@endsection

@section('content')
    <div class="dashboard-wrapper">
        <div class="page-header">
            <h1>Welcome, <span>{{ Auth::user()->name }}</span></h1>
            <p class="subtitle">  Here is what's happening with your resources today.</p>
        </div>

        {{-- 1. Statistics Cards --}}
        <div class="stats-container">

            {{-- Card: Pending --}}
            <div class="catalog-style-card stat-card stat-pending">
                <div class="card-accent-line"></div>
                <h1 class="stat-value">{{ $stats['pending'] }}</h1>
                <p class="subtitle">Pending Requests</p>
            </div>

            {{-- Card: Approved --}}
            <div class="catalog-style-card stat-card stat-approved">
                <div class="card-accent-line"></div>
                <h1 class="stat-value">{{ $stats['approved'] }}</h1>
                <p class="subtitle">Approved / Active</p>
            </div>

            {{-- Card: Total --}}
            <div class="catalog-style-card stat-card stat-total">
                <div class="card-accent-line"></div>
                <h1 class="stat-value">{{ $stats['total'] }}</h1>
                <p class="subtitle">Total History</p>
            </div>
        </div>

        {{-- 2. Main Grid --}}
        <div class="dashboard-main-grid">

            {{-- Left Col: Recent Activity --}}
            <div class="catalog-style-card">
                <div class="card-accent-line"></div>
                <div class="card-content">
                    <h2>Recent Activity</h2>

                    @if ($recentActivity->isEmpty())
                        <p class="description">No recent activity found.</p>
                        <a href="{{ route('catalog.index') }}" class="btn-catalog-primary">Browse Catalog</a>
                    @else
                        <ul class="activity-list">
                            @foreach ($recentActivity as $item)
                                <li class="activity-item">
                                    <div>
                                        <span class="activity-name">{{ $item->resource->name }}</span>
                                        <span class="activity-time">{{ $item->created_at->diffForHumans() }}</span>
                                    </div>

                                    {{-- Status Mini-Badge --}}
                                    @php
                                        $badgeClass = match ($item->reservation_status) {
                                            'approved' => 'status-available',
                                            'rejected' => 'status-unavailable',
                                            default => 'status-maintenance', // Reusing your existing badge classes
                                        };
                                    @endphp
                                    <span class="status-badge {{ $badgeClass }}">
                                        {{ ucfirst($item->reservation_status) }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="view-all-link">
                            <a href="{{ route('reservations.index') }}">View All History &rarr;</a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right Col: Next Up Widget --}}
            <div class="catalog-style-card card-next-up">
                <div class="card-content">
                    <div class="next-up-title">Next Up</div>

                    @if ($upcoming)
                        <div class="next-up-resource">{{ $upcoming->resource->name }}</div>
                        <p class="description">
                            Starts: {{ $upcoming->start_date->format('M d, H:i') }}
                        </p>
                        <div class="info-box" style="background: white;">
                            <small>Location: {{ $upcoming->resource->location }}</small>
                        </div>
                    @else
                        <p class="description">No upcoming approved reservations.</p>
                        <br>
                        <a href="{{ route('catalog.index') }}" class="btn-catalog-primary"
                            style="width: 100%; display: block; text-align: center;">Book Now</a>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
