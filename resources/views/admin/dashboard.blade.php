@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('styles')
    {{-- Keep your existing CSS --}}
    <link rel="stylesheet" href="{{ asset('dashboardcss/guestboard.css') }}">
    <link rel="stylesheet" href="{{ asset('admin.css') }}">


    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@endsection

@section('content')
    <div class="dashboard-container">

        <!-- HEADER -->
        <div class="section-header">
            <div>
                <h2>Admin Dashboard</h2>
                <p style="color: #7f8c8d; margin-left: 20px;">DataCenter Overview</p>
            </div>
            <div><span class="badge badge-info">{{ now()->format('l, d F Y') }}</span></div>
        </div>

        <!-- 1. TOP STATS ROW -->
        <div class="stats-grid">
            <div class="stat-card border-blue">
                <div class="icon"><i class="fas fa-users"></i></div>
                <h3>Total Users</h3>
                <div class="number">{{ $stats['total_users'] }}</div>
            </div>
            <div class="stat-card border-green">
                <div class="icon"><i class="fas fa-server"></i></div>
                <h3>Total Resources</h3>
                <div class="number">{{ $stats['total_resources'] }}</div>
            </div>
            <div class="stat-card border-orange">
                <div class="icon"><i class="fas fa-clock"></i></div>
                <h3>Pending Reservations</h3>
                <div class="number">{{ $stats['pending_reservations'] }}</div>
            </div>
            <div class="stat-card border-red">
                <div class="icon"><i class="fas fa-user-plus"></i></div>
                <h3>Account Requests</h3>
                <div class="number">{{ $stats['pending_accounts'] }}</div>
            </div>
        </div>

        <!-- 2. DEEP DIVE ANALYTICS ROW -->
        <div class="grid-3">

            <!-- A. Resource Distribution Chart -->
            <div class="analytics-panel">
                <div class="panel-title"><i class="fas fa-chart-bar" style="color: #3498db;"></i> Inventory Breakdown</div>

                @foreach ($categories_breakdown as $cat)
                    @php
                        // Calculate percentage relative to total resources
                        $percent =
                            $stats['total_resources'] > 0
                                ? ($cat->resources_count / $stats['total_resources']) * 100
                                : 0;
                        // Dynamic Colors
                        $color = match ($cat->name) {
                            'Serveurs' => '#1abc9c',
                            'Machines Virtuelles' => '#3498db',
                            'Stockage' => '#f1c40f',
                            'Réseau' => '#e74c3c',
                            default => '#95a5a6',
                        };
                    @endphp
                    <div class="bar-chart-row">
                        <div class="bar-label">
                            <span>{{ $cat->name }}</span>
                            <strong>{{ $cat->resources_count }}</strong>
                        </div>
                        <div class="bar-track">
                            <div class="bar-fill"
                                style="width: {{ $percent }}%; background-color: {{ $color }};"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- B. Health & Occupancy -->
            <div class="analytics-panel">
                <div class="panel-title"><i class="fas fa-heartbeat" style="color: #e74c3c;"></i> Infrastructure Health
                </div>

                {{-- Occupancy Bar --}}
                <div style="margin-bottom: 25px;">
                    <div class="bar-label">
                        <span>Overall Live Occupancy</span>
                        <strong>{{ $occupancy_rate }}%</strong>
                    </div>
                    <div class="bar-track" style="height: 15px;">
                        <div class="bar-fill"
                            style="width: {{ $occupancy_rate }}%; background: linear-gradient(90deg, #3498db, #9b59b6);">
                        </div>
                    </div>
                </div>

                <div class="status-grid">
                    <div class="status-box" style="background: #e8f8f5; color: #27ae60;">
                        <h4>{{ $status_breakdown['disponible'] }}</h4>
                        <p>Available</p>
                    </div>
                    <div class="status-box" style="background: #fdedec; color: #e74c3c;">
                        <h4>{{ $status_breakdown['maintenance'] + $status_breakdown['hors_service'] }}</h4>
                        <p>Maintenance</p>
                    </div>
                </div>
            </div>

            <!-- C. Top Users -->
            <div class="analytics-panel">
                <div class="panel-title"><i class="fas fa-trophy" style="color: #f1c40f;"></i> Top Active Users</div>
                <ul class="user-mini-list">
                    @foreach ($top_active_users as $topUser)
                        <li class="user-mini-item">
                            <div style="display: flex; align-items: center;">
                                <div class="user-avatar">{{ substr($topUser->name, 0, 1) }}</div>
                                <div class="user-details">
                                    <strong>{{ $topUser->name }}</strong>
                                    <span>{{ $topUser->role->name }}</span>
                                </div>
                            </div>
                            <span class="reservation-count">{{ $topUser->reservations_count }} Res.</span>
                        </li>
                    @endforeach
                    @if ($top_active_users->isEmpty())
                        <li class="empty-state" style="padding:10px;">No reservation activity yet.</li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- 3. ACTIONS ROW -->
        <div class="grid-2">
            <!-- Account Requests -->
            <div class="table-wrapper">
                <div class="table-header">
                    <h3>Pending Account Requests ({{ $account_requests->count() }})</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Profile</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($account_requests as $req)
                            <tr>
                                <td>{{ $req->name }}</td>
                                <td><span class="badge badge-info">{{ $req->profile }}</span></td>
                                <td>{{ $req->created_at->format('M d') }}</td>
                                <td>
                                    {{-- APPROVE BUTTON --}}
                                    <form action="{{ route('admin.accounts.approve', $req->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="action-btn btn-check" title="Approve"
                                            onclick="return confirm('Approve this user?');">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>

                                    {{-- REJECT BUTTON --}}
                                    <form action="{{ route('admin.accounts.reject', $req->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="action-btn btn-ban" title="Reject"
                                            onclick="return confirm('Reject this request?');">
                                            X
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty-state">No pending requests.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Recent Users -->
            <div class="table-wrapper">
                <div class="table-header">
                    <h3>Recent Users</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Toggle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recent_users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>
                                    @if ($user->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="action-btn {{ $user->is_active ? 'btn-ban' : 'btn-check' }}">
                                            <i class="fas {{ $user->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
