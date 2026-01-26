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

    {{-- Individual Page Styles (e.g. Guest or Manager Specific) --}}
    @yield('styles')

    {{-- FontAwesome for Sidebar Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- NOTIFICATION CSS --}}
    <style>
        /* Notification Container */
        .notification-container {
            position: relative;
            margin-right: 25px;
            cursor: pointer;
        }

        .notification-bell {
            font-size: 1.3rem;
            position: relative;
            color: #555;
            transition: 0.3s;
            padding: 5px;
        }
        
        .notification-container:hover .notification-bell { color: #3498db; }

        .bell-count {
            position: absolute;
            top: 0;
            right: 0;
            background-color: #e74c3c;
            color: white;
            font-size: 0.65rem;
            padding: 2px 5px;
            border-radius: 50%;
            border: 2px solid white;
            font-weight: bold;
        }

        /* Dropdown Logic (Show on Hover) */
        .notif-dropdown {
            display: none; /* Hidden by default */
            position: absolute;
            top: 40px;
            right: -10px;
            width: 320px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            z-index: 1000;
            border: 1px solid #f1f1f1;
            overflow: hidden;
        }

        /* Show Dropdown on Hover of Container */
        .notification-container:hover .notif-dropdown {
            display: block;
        }

        /* Dropdown Content */
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
                
                {{-- 1. COMMON MENU (Visible to All) --}}
                <div class="nav-group">
                    <span class="nav-label">MAIN MENU</span>
                    <ul>
                        <li>
<<<<<<< HEAD
                            <a href="{{ route('home') }}" class="{{ Request::is('*/dashboard') || Request::is('dashboard') || Request::is('/') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt" style="width:20px; text-align:center; margin-right:8px;"></i> Dashboard
=======
                            <a href="{{ route('home') }}"
                                class="{{ Request::is('*/dashboard') || Request::is('dashboard') || Request::is('/') ? 'active' : '' }}">
                                Dashboard
>>>>>>> 59e178f31f1b698f419b741b42ef769fe6fdd215
                            </a>
                        </li>

                        <li>
<<<<<<< HEAD
                            <a href="{{ route('catalog.index') }}" class="{{ Request::is('catalog*') ? 'active' : '' }}">
                                <i class="fas fa-box" style="width:20px; text-align:center; margin-right:8px;"></i> Resource Catalog
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- 2. ADMIN SPECIFIC MENU --}}
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
                                <a href="#">
                                    <i class="fas fa-history" style="width:20px; text-align:center; margin-right:8px;"></i> Global Logs
                                </a>
                            </li>
                        </ul>
                    </div>
                @endif

                {{-- 3. MANAGER SPECIFIC MENU --}}
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
=======
                            <a href="{{ Auth::check() && Auth::user()->role->name === 'responsable_technique' ? route('manager.resources.index') : route('catalog.index') }}"
                                class="{{ Request::is('catalog*') || Request::is('manager/resources*') ? 'active' : '' }}">

                                {{ Auth::check() && Auth::user()->role->name === 'responsable_technique' ? 'My Resources' : 'Resource Catalog' }}
                            </a>
                        </li>
                        @if (Auth::check() && Auth::user()->role && Auth::user()->role->name != 'responsable_technique')
                        <a href="{{ route('catalog.index') }}"
                            class="{{ Request::is('catalog*') ? 'active' : '' }}">
                            Resource Catalog
                        </a>
                        @endif
                        </li>
                        @if (Auth::check() && Auth::user()->role && Auth::user()->role->name == 'admin')
                        <li><a href="{{ route('activity.logs') }}">Activity Logs</a></li>
                        @endif
                    </ul>
                </div>

                <div class="nav-group">
                    <span class="nav-label">RESERVATIONS</span>
                    <ul>
                        @if (Auth::check() && Auth::user()->role->name === 'utilisateur_interne')
                        {{-- Internal User --}}
                        <li><a href="{{ route('reservations.index') }}"
                                class="{{ Request::routeIs('reservations.index') ? 'active' : '' }}">Reservation
                                History</a></li>
                        @elseif (Auth::check() && Auth::user()->role->name === 'responsable_technique')

                        {{-- Manager --}}
                        <li><a href="{{ route('manager.reservations.index') }}"
                                class="{{ Request::routeIs('manager.reservations.*') ? 'active' : '' }}">Manage
                                Reservations</a></li>
                        {{-- <li><a href="#">My Requests</a></li> --}}
                        @if (Auth::check() && Auth::user()->role && Auth::user()->role->name == 'admin')
                        <li><a href="{{ route('reservations.index') }}"
                                class="{{ Request::routeIs('reservations.index') ? 'active' : '' }}">Reservation
                                History</a></li>
                        @endif
                        @else
                        {{-- Guest --}}
                        <li>
                            <a href="{{ route('guest.register.show') }}"
                                class="{{ Request::is('*/register-request') ? 'active' : '' }}"
                                style="color: #0096FF; font-weight: 700;">
                                Apply for Access
                            </a>
                        </li>
                        @endif
                    </ul>

                </div>
>>>>>>> 59e178f31f1b698f419b741b42ef769fe6fdd215

                <div class="nav-group">
                    <span class="nav-label">SUPPORT</span>
                    <ul>
<<<<<<< HEAD
                        <li><a href="#"><i class="fas fa-life-ring" style="width:20px; text-align:center; margin-right:8px;"></i> Report Issue</a></li>
                        <li>
                            <a href="{{ route('guest.policies') }}" class="{{ Request::is('*/policies') ? 'active' : '' }}">
                                <i class="fas fa-file-contract" style="width:20px; text-align:center; margin-right:8px;"></i> Usage Policies
=======
                        <li>
                            {{-- Report Issue (User) OR Manage Incidents (Manager) --}}
                            <a href="{{ Auth::check() && Auth::user()->role->name === 'responsable_technique' ? route('manager.incidents.index') : route('incidents.create') }}"
                                class="{{ Request::routeIs('incidents.create') || Request::routeIs('manager.incidents.*') ? 'active' : '' }}">

                                {{ Auth::check() && Auth::user()->role->name === 'responsable_technique' ? 'Manage Incidents' : 'Report Technical Issue' }}
                                @auth {{-- Only show to logged-in users --}}
                                @if (Auth::check() && Auth::user()->role && Auth::user()->role->name == 'utilisateur_interne')
                        <li>
                            <a href="{{ route('incidents.create') }}"
                                class="{{ Request::routeIs('incidents.create') ? 'active' : '' }}">
                                Report Technical Issue</a>
                        </li>
                        @endif
                        @endauth
                        <li>
                            <a href="{{ route('policies.show') }}"
                                class="{{ Request::routeIs('policies.show') ? 'active' : '' }}">
                                Usage Policies
>>>>>>> 59e178f31f1b698f419b741b42ef769fe6fdd215
                            </a>
                        </li>

                        {{-- Moderation link ONLY for Manager --}}
                        @if (Auth::check() && Auth::user()->role->name === 'responsable_technique')
                        <li>
                            <a href="{{ route('manager.moderation.index') }}"
                                class="{{ Request::routeIs('manager.moderation.*') ? 'active' : '' }}">
                                Moderation
                            </a>
                        </li>
                        @endif

                        {{-- <li>
                            <a href="{{ route('policies.show') }}"
                        class="{{ Request::routeIs('policies.show') ? 'active' : '' }}">
                        Usage Policies
                        </a>
                        </li> --}}
                    </ul>

                </div>
            </nav>
        </aside>

        {{-- Main Page Content --}}
        <div class="main-wrapper">
            <header class="top-header">
                <div class="header-left">
                    <nav class="breadcrumbs">
                        <span class="breadcrumb-static">AlphaFold DataCenter</span>
                        <span class="crumb-divider">/</span>
                        <span class="current-page">@yield('title', 'Dashboard')</span>
                    </nav>

                    <form action="{{ route('catalog.index') }}" method="GET" class="header-search">
                        <span class="search-icon">🔍</span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search resources...">
                    </form>
                </div>

                <div class="header-right">
<<<<<<< HEAD
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
                            {{-- Role Badge --}}
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
=======

                    <div class="notification-bell">
                        🔔<span class="bell-count">0</span>
                    </div>

                    @auth
                    {{-- Used for logged-in Users --}}
                    <div class="user-profile">
                        <span class="badge">{{ Auth::user()->role->name }}</span>
>>>>>>> 59e178f31f1b698f419b741b42ef769fe6fdd215

                        <div class="user-identity">
                            <span class="welcome-text">Welcome,</span>
                            <span class="username-display">{{ Auth::user()->name }}</span>
                        </div>

                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="logout-link">Logout</button>
                        </form>
                    </div>
                    @else
                    {{-- Show this for Guests --}}
                    <div class="user-profile">
                        <div class="user-identity">
                            <span class="welcome-text">Mode:</span>
                            <span class="username-display">Guest Access</span>
                        </div>
                        <a href="{{ route('login') }}" class="logout-link" style="text-decoration: none;">Logout</a>
                    </div>
                    @endauth
                </div>
            </header>

            <main class="content-body">
                {{-- Flash Messages for Success/Error --}}
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
                <div class="footer-section left">
                    <p>&copy; 2026 <strong>AlphaFold DataCenter</strong></p>
                    <p class="location-text">📍 City Center, Place des Nations, Tanger, Maroc</p>
                </div>

                <div class="footer-section center">
                    <ul class="footer-links">
                        {{-- <li><a href="{{ route('policies.show') }}">Usage Policies</a></li> --}}
                        <li><a href="{{ route('policies.show') }}">Usage Policies</a></li>
                        <li><span class="contact-info">Contact: +212 5 39 33 00 00</span></li>
                    </ul>
                </div>

                <div class="footer-section right">
                    <span class="version-tag">Build: 1.0.4-STABLE</span>
                </div>
            </footer>
        </div>
    </div>
</body>

</html>

</html>