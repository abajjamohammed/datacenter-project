@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('styles')
    {{-- Keep your existing CSS --}}
    <link rel="stylesheet" href="{{ asset('dashboardcss/guestboard.css') }}">

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* LAYOUT UTILS */
        .dashboard-container {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
            background-color: #f8f9fa;
            min-height: 80vh;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        /* HEADERS */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-header h2 {
            font-size: 1.5rem;
            color: #2c3e50;
            border-left: 5px solid #3498db;
            padding-left: 15px;
        }

        /* CARDS */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
            border-top: 4px solid #ddd;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card .icon {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 2.5rem;
            opacity: 0.1;
        }

        .stat-card h3 {
            font-size: 0.9rem;
            color: #7f8c8d;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .stat-card .number {
            font-size: 2.2rem;
            font-weight: 700;
            color: #2c3e50;
        }

        /* Colors */
        .border-blue {
            border-color: #3498db;
        }

        .border-green {
            border-color: #2ecc71;
        }

        .border-orange {
            border-color: #f39c12;
        }

        .border-red {
            border-color: #e74c3c;
        }

        /* ANALYTICS PANEL */
        .analytics-panel {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .panel-title {
            font-size: 1.1rem;
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* CSS BAR CHART */
        .bar-chart-row {
            margin-bottom: 15px;
        }

        .bar-label {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            margin-bottom: 5px;
            color: #555;
        }

        .bar-track {
            background: #f1f2f6;
            height: 12px;
            border-radius: 6px;
            overflow: hidden;
            width: 100%;
        }

        .bar-fill {
            height: 100%;
            border-radius: 6px;
            transition: width 1s ease;
        }

        /* STATUS PILLS */
        .status-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .status-box {
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .status-box h4 {
            font-size: 2rem;
            margin: 0;
        }

        .status-box p {
            margin: 5px 0 0;
            font-size: 0.8rem;
            text-transform: uppercase;
            opacity: 0.8;
        }

        /* USER LIST SMALL */
        .user-mini-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .user-mini-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f1f1f1;
        }

        .user-mini-item:last-child {
            border-bottom: none;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            background: #3498db;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 12px;
            font-size: 0.9rem;
        }

        .user-details strong {
            display: block;
            font-size: 0.9rem;
            color: #2c3e50;
        }

        .user-details span {
            font-size: 0.75rem;
            color: #95a5a6;
        }

        .reservation-count {
            background: #e8f6f3;
            color: #1abc9c;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: bold;
        }

        /* TABLES */
        .table-wrapper {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .table-header {
            padding: 15px 20px;
            background-color: #fff;
            border-bottom: 1px solid #eee;
        }

        .table-header h3 {
            margin: 0;
            color: #2c3e50;
            font-size: 1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #f8f9fa;
        }

        th,
        td {
            padding: 12px 20px;
            text-align: left;
            font-size: 0.9rem;
            border-bottom: 1px solid #f1f1f1;
        }

        th {
            font-weight: 600;
            color: #7f8c8d;
            text-transform: uppercase;
            font-size: 0.75rem;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .badge-success {
            background-color: #e8f8f5;
            color: #27ae60;
        }

        .badge-danger {
            background-color: #fdedec;
            color: #e74c3c;
        }

        .badge-info {
            background-color: #ebf5fb;
            color: #3498db;
        }

        .action-btn {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.75rem;
            cursor: pointer;
            border: none;
            text-decoration: none;
            display: inline-block;
        }

        .btn-check {
            background: #e8f8f5;
            color: #27ae60;
        }

        .btn-ban {
            background: #fdedec;
            color: #e74c3c;
        }

        .empty-state {
            padding: 30px;
            text-align: center;
            color: #95a5a6;
        }

        .action-btn {
            border: none;
            cursor: pointer;
            background: transparent;
            font-size: 1.1em;
            padding: 0 5px;
        }

        .btn-check {
            color: #2ecc71;
        }

        .btn-ban {
            color: #e74c3c;
        }
    </style>
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
