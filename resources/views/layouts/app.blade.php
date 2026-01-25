<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AlphaFold DataCenter - @yield('title')</title>
    
    {{-- Global Styles --}}
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/catalog.css') }}">
    
    {{-- Individual Page Styles --}}
    @yield('styles')

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- CRITICAL: NOTIFICATION CSS (This fixes the broken layout) --}}
    <style>
        /* Notification Container */
        .notification-container {
            position: relative;
            margin-right: 25px;
            cursor: pointer;
            display: inline-block; /* Ensures it sits correctly */
        }

        .notification-bell {
            font-size: 1.3rem;
            position: relative;
            color: #555;
            transition: 0.3s;
            padding: 10px;
        }
        
        .notification-container:hover .notification-bell { color: #3498db; }

        .bell-count {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: #e74c3c;
            color: white;
            font-size: 0.65rem;
            padding: 2px 5px;
            border-radius: 50%;
            border: 2px solid white;
            font-weight: bold;
        }

        /* Dropdown Logic (Hidden by default, shown on hover) */
        .notif-dropdown {
            display: none; 
            position: absolute;
            top: 100%; /* Push it below the bell */
            right: -10px;
            width: 320px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            z-index: 1000;
            border: 1px solid #f1f1f1;
            overflow: hidden;
        }

        /* Show Dropdown on Hover */
        .notification-container:hover .notif-dropdown {
            display: block;
        }

        /* Dropdown Content Styling */
        .notif-header {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            font-weight: 700;
            font-size: 0.9rem;
            background-color: #f8f9fa;
            color: #2c3e50;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notif-list {
            max-height: 350px;
            overflow-y: auto;
        }

        .notif-item {
            display: flex;
            padding: 15px;
            border-bottom: 1px solid #f9f9f9;
            text-decoration: none;
            color: #333;
            transition: background 0.2s;
            align-items: flex-start;
        }

        .notif-item:last-child { border-bottom: none; }
        .notif-item:hover { background-color: #fcfcfc; }

        .notif-icon {
            margin-right: 15px;
            font-size: 1.1rem;
            padding-top: 2px;
        }

        .notif-content { flex: 1; }
        .notif-title { font-weight: 600; font-size: 0.85rem; margin: 0 0 3px 0; color: #2c3e50; }
        .notif-msg { font-size: 0.8rem; color: #7f8c8d; margin: 0 0 5px 0; line-height: 1.3; }
        .notif-time { font-size: 0.7rem; color: #aaa; display: block; }

        .empty-notif { padding: 30px; text-align: center; color: #95a5a6; }
        .empty-notif i { font-size: 2rem; margin-bottom: 10px; opacity: 0.3; display: block; }
    </style>
</head>
<body>
    <div class="app-container">
        {{-- Sidebar Section --}}
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo-sphere"></div>
                <h1>AlphaFold DC</h1>
            </div>
            
            <nav class="sidebar-nav">
                
                {{-- 1. COMMON MENU --}}
                <div class="nav-group">
                    <span class="nav-label">MAIN MENU</span>
                    <ul>
                        <li>
                            <a href="{{ route('home') }}" class="{{ Request::is('*/dashboard') || Request::is('dashboard') || Request::is('/') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt" style="width:20px; text-align:center; margin-right:8px;"></i> Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('catalog.index') }}" class="{{ Request::is('catalog*') ? 'active' : '' }}">
                                <i class="fas fa-box" style="width:20px; text-align:center; margin-right:8px;"></i> Resource Catalog
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- 2. ADMIN MENU --}}
                @if(Auth::check() && Auth::user()->role->name === 'admin')
                    <div class="nav-group">
                        <span class="nav-label">ADMINISTRATION</span>
                        <ul>
                            <li>
                                <a href="{{ route('admin.resources.index') }}" class="{{ Request::is('admin/resources*') ? 'active' : '' }}">
                                    <i class="fas fa-server" style="width:20px; text-align:center; margin-right:8px;"></i> Manage Resources
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users.index') }}" class="{{ Request::is('admin/users*') ? 'active' : '' }}">
                                    <i class="fas fa-users-cog" style="width:20px; text-align:center; margin-right:8px;"></i> Manage Users
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.maintenances.index') }}" class="{{ Request::is('admin/maintenances*') ? 'active' : '' }}">
                                    <i class="fas fa-tools" style="width:20px; text-align:center; margin-right:8px;"></i> Maintenances
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.logs.index') }}" class="{{ Request::is('admin/logs*') ? 'active' : '' }}">
                                    <i class="fas fa-history" style="width:20px; text-align:center; margin-right:8px;"></i> Global Logs
                                </a>
                            </li>
                        </ul>
                    </div>
                @endif

                {{-- 3. MANAGER MENU --}}
                @if(Auth::check() && Auth::user()->role->name === 'responsable_technique')
                    <div class="nav-group">
                        <span class="nav-label">MANAGEMENT</span>
                        <ul>
                            <li><a href="#"><i class="fas fa-check-circle"></i> Approve Requests</a></li>
                            <li><a href="#"><i class="fas fa-network-wired"></i> My Resources</a></li>
                        </ul>
                    </div>
                @endif

                {{-- 4. INTERNAL USER MENU --}}
                @if(Auth::check() && Auth::user()->role->name === 'utilisateur_interne')
                    <div class="nav-group">
                        <span class="nav-label">MY RESERVATIONS</span>
                        <ul>
                            <li><a href="#"><i class="fas fa-plus-circle"></i> New Request</a></li>
                            <li><a href="#"><i class="fas fa-list"></i> My History</a></li>
                        </ul>
                    </div>
                @endif

                {{-- 5. GUEST MENU --}}
                @if(Auth::check() && Auth::user()->role->name === 'invite')
                    <div class="nav-group">
                        <span class="nav-label">ACCESS</span>
                        <ul>
                            <li>
                                <a href="{{ route('guest.register.show') }}" class="{{ Request::is('*/register-request') ? 'active' : '' }}" style="color: #0096FF; font-weight: 700;">
                                    <i class="fas fa-id-card"></i> Apply for Access
                                </a>
                            </li>
                        </ul>
                    </div>
                @endif

                <div class="nav-group">
                    <span class="nav-label">SUPPORT</span>
                    <ul>
                        <li><a href="#"><i class="fas fa-life-ring" style="width:20px; text-align:center; margin-right:8px;"></i> Report Issue</a></li>
                        <li>
                            <a href="{{ route('guest.policies') }}" class="{{ Request::is('*/policies') ? 'active' : '' }}">
                                <i class="fas fa-file-contract" style="width:20px; text-align:center; margin-right:8px;"></i> Usage Policies
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </aside>

        {{-- Main Page Content --}}
        <div class="main-wrapper">
            <header class="top-header">
                <div class="header-left">
                    <nav class="breadcrumbs">
                        <a href="{{ route('home') }}">AlphaFold DataCenter</a>
                        <span class="crumb-divider">/</span>
                        <span class="current-page">@yield('title', 'Dashboard')</span>
                    </nav>
                    
                    <form action="{{ route('catalog.index') }}" method="GET" class="header-search">
                        <span class="search-icon">🔍</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search resources...">
                    </form>
                </div>

                <div class="header-right">
                    {{-- Quick Action for Users --}}
                    @if(Auth::check() && Auth::user()->role->name === 'utilisateur_interne')
                        <button class="btn-primary-small">+ New Request</button>
                    @endif
                    
                    {{-- Admin Quick Action --}}
                    @if(Auth::check() && Auth::user()->role->name === 'admin')
                         <a href="{{ route('admin.resources.create') }}" class="btn-primary-small" style="text-decoration:none; line-height:32px;">+ Add Resource</a>
                    @endif
                    
                    {{-- NOTIFICATION DROPDOWN --}}
                    <div class="notification-container">
                        <div class="notification-bell">
                            <i class="fas fa-bell"></i>
                            @if(isset($unreadNotifications) && $unreadNotifications->count() > 0)
                                <span class="bell-count">{{ $unreadNotifications->count() }}</span>
                            @endif
                        </div>

                        {{-- The Dropdown Menu --}}
                        <div class="notif-dropdown">
                            <div class="notif-header">
                                <span>Notifications</span>
                                @if(isset($unreadNotifications) && $unreadNotifications->count() > 0)
                                    <a href="{{ route('notifications.readAll') }}" style="font-size: 0.75rem; color: #3498db; text-decoration: none;">Mark all read</a>
                                @endif
                            </div>
                            
                            <div class="notif-list">
                                @if(isset($unreadNotifications) && $unreadNotifications->count() > 0)
                                    @foreach($unreadNotifications as $notif)
                                        <a href="{{ route('notifications.read', $notif->id) }}" class="notif-item">
                                            <div class="notif-icon">
                                                @if($notif->type == 'reservation_response') <i class="fas fa-calendar-check" style="color: #27ae60;"></i>
                                                @elseif($notif->type == 'conflict') <i class="fas fa-exclamation-triangle" style="color: #e74c3c;"></i>
                                                @elseif($notif->type == 'account') <i class="fas fa-user-plus" style="color: #3498db;"></i>
                                                @elseif($notif->type == 'maintenance') <i class="fas fa-tools" style="color: #f39c12;"></i>
                                                @else <i class="fas fa-info-circle" style="color: #95a5a6;"></i>
                                                @endif
                                            </div>
                                            <div class="notif-content">
                                                <p class="notif-title">{{ $notif->title }}</p>
                                                <p class="notif-msg">{{ Str::limit($notif->message, 50) }}</p>
                                                <span class="notif-time">{{ $notif->created_at->diffForHumans() }}</span>
                                            </div>
                                        </a>
                                    @endforeach
                                @else
                                    <div class="empty-notif">
                                        <i class="fas fa-bell-slash"></i>
                                        <p>No new notifications</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- END NOTIFICATIONS --}}
                    
                    @auth
                        <div class="user-profile">
                            @php
                                $roleColors = [
                                    'admin' => '#e74c3c', // Red
                                    'responsable_technique' => '#f39c12', // Orange
                                    'utilisateur_interne' => '#3498db', // Blue
                                    'invite' => '#95a5a6' // Grey
                                ];
                                $color = $roleColors[Auth::user()->role->name] ?? '#3498db';
                            @endphp
                            <span class="badge" style="background-color: {{ $color }}; color: white;">
                                {{ ucfirst(str_replace('_', ' ', Auth::user()->role->name)) }}
                            </span>

                            <div class="user-identity">
                                <span class="welcome-text">Welcome,</span>
                                <span class="username-display">{{ Auth::user()->name }}</span>
                            </div>

                            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="logout-link">Logout</button>
                            </form>
                        </div>
                    @endauth
                </div>
            </header>

            <main class="content-body">
                {{-- Flash Messages --}}
                @if(session('success'))
                    <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                     <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                        <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                    </div>
                @endif

                <div class="page-card">
                    @yield('content')
                </div>
            </main>

            <footer class="main-footer">
                <div class="footer-section">
                    <p>&copy; 2026 <span>AlphaFold DataCenter</span>. All rights reserved.</p>
                </div>
            </footer>
        </div>
    </div>
</body>
</html>