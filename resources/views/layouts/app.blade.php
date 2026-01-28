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
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">

    {{-- Individual Page Styles --}}
    @yield('styles')

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
                            @php
                                // Determine the correct route based on the user's status/role
$dashboardRoute = route('guest.dashboard'); // Default for guests

if (Auth::check()) {
    $role = Auth::user()->role->name;
    if ($role === 'admin') {
        $dashboardRoute = route('admin.dashboard');
    } elseif ($role === 'responsable_technique') {
        $dashboardRoute = route('manager.dashboard');
    } elseif ($role === 'utilisateur_interne') {
        $dashboardRoute = route('user.dashboard');
                                    }
                                }
                            @endphp

                            <a href="{{ $dashboardRoute }}"
                                class="{{ Request::is('*/dashboard') || Request::is('dashboard') || Request::is('/') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt"
                                    style="width:20px; text-align:center; margin-right:8px;"></i>
                                Dashboard
                            </a>
                        </li>
                        @if (!Auth::check() || (Auth::user()->role->name !== 'admin' && Auth::user()->role->name !== 'responsable_technique'))
                            <li>
                                <a href="{{ route('catalog.index') }}"
                                    class="{{ Request::is('catalog*') ? 'active' : '' }}">
                                    <i class="fas fa-box" style="width:20px; text-align:center; margin-right:8px;"></i>
                                    Resource Catalog
                                </a>
                            </li>
                        @elseif (Auth::check() && Auth::user()->role->name === 'responsable_technique')
                            <li>
                                <a href="{{ route('manager.resources.index') }}"
                                    class="{{ Request::is('manager/resources*') ? 'active' : '' }}">
                                    <i class="fas fa-network-wired"
                                        style="width:20px; text-align:center; margin-right:8px;"></i>
                                    My Resources
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="nav-group">
                    @if (Auth::check() && Auth::user()->role->name !== 'admin')
                        <span class="nav-label">RESERVATIONS</span>
                    @endif
                    <ul>
                        @if (Auth::check() && Auth::user()->role->name === 'utilisateur_interne')
                            {{--  <li><a href="#">My Requests</a></li> --}}
                            <li><a href="{{ route('reservations.index') }}"
                                    class="{{ Request::routeIs('reservations.index') ? 'active' : '' }}">Reservation
                                    History</a></li>
                        @elseif (!Auth::check() || (Auth::check() && Auth::user()->role->name === 'invite'))
                            {{-- Specific style for Guests to Apply for Access --}}
                            <li>
                                <a href="{{ route('auth.register') }}"
                                    class="{{ Request::is('*/register-request') ? 'active' : '' }}">
                                    Apply for Access
                                </a>
                            </li>
                        @elseif (Auth::check() && Auth::user()->role->name === 'responsable_technique')
                            <li>
                                <a href="{{ route('manager.reservations.index') }}"
                                    class="{{ Request::is('manager/reservations*') ? 'active' : '' }}">
                                    <i class="fas fa-check-circle"
                                        style="width:20px; text-align:center; margin-right:8px;"></i>
                                    Approve Requests
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>


                {{-- ADMIN SECTION --}}
                @if (Auth::check() && Auth::user()->role?->name === 'admin')
                    <div class="nav-group">
                        <span class="nav-label">ADMINISTRATION</span>
                        <ul>
                            <li>
                                <a href="{{ route('admin.users.index') }}"
                                    class="{{ Request::is('admin/users*') ? 'active' : '' }}">
                                    <i class="fas fa-users-cog"
                                        style="width:20px; text-align:center; margin-right:8px;"></i>
                                    Manage Users
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.resources.index') }}"
                                    class="{{ Request::is('admin/resources*') ? 'active' : '' }}">
                                    <i class="fas fa-server"
                                        style="width:20px; text-align:center; margin-right:8px;"></i>
                                    System Resources
                                </a>
                            </li>
                            <li>
                                {{-- UPDATED LINK HERE --}}
                                <a href="{{ route('admin.logs.index') }}"
                                    class="{{ Request::is('admin/logs*') ? 'active' : '' }}">
                                    <i class="fas fa-history"
                                        style="width:20px; text-align:center; margin-right:8px;"></i> Global Logs
                                </a>
                            </li>
                        </ul>
                    </div>
                @endif

                <div class="nav-group">
                    <span class="nav-label">SUPPORT</span>
                    <ul>
                        @if (Auth::check() && Auth::user()->role?->name === 'utilisateur_interne')
                            <li>
                                <a href="{{ route('incidents.create') }}"
                                    class="{{ Request::routeIs('incidents.create') ? 'active' : '' }}">
                                    Report Technical Issue</a>
                            </li>
                        @endif
                        @if (Auth::check() && Auth::user()->role?->name === 'responsable_technique')
                            <li>
                                <a href="{{ route('manager.incidents.index') }}"
                                    class="{{ Request::is('manager/incidents*') ? 'active' : '' }}">
                                    <i class="fas fa-tools"></i>
                                    Technical Tickets
                                </a>
                            </li>
                        @endif
                        @if (Auth::check() && Auth::user()->role?->name === 'admin')
                            <li>
                                <a href="{{ route('admin.maintenances.index') }}"
                                    class="{{ Request::is('admin/maintenances*') ? 'active' : '' }}">
                                    <i class="fas fa-tools"></i>
                                    Maintenances</a>
                            </li>
                        @endif

                        <li>
                            <a href="{{ route('policies.show') }}"
                                class="{{ Request::routeIs('policies.show') ? 'active' : '' }}">
                                Usage Policies
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
                    @if (!Auth::check() || (Auth::user()->role->name !== 'admin' && Auth::user()->role->name !== 'responsable_technique'))
                        <form action="{{ route('catalog.index') }}" method="GET" class="header-search">
                            <span class="search-icon">🔍</span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search resources...">
                        </form>
                    @endif
                </div>

                <div class="header-right">
                    {{-- Admin Quick Action --}}
                    @if (Auth::check() && Auth::user()->role->name === 'admin')
                        <a href="{{ route('admin.resources.create') }}" class="btn-primary-small"
                            style="text-decoration:none; line-height:32px;">+ Add Resource</a>
                    @endif

                    {{-- NOTIFICATION DROPDOWN --}}
                    <div class="notification-container">
                        <div class="notification-bell">
                            <i class="fas fa-bell"></i>
                            @if (isset($unreadNotifications) && $unreadNotifications->count() > 0)
                                <span class="bell-count">{{ $unreadNotifications->count() }}</span>
                            @endif
                        </div>

                        {{-- The Dropdown Menu --}}
                        <div class="notif-dropdown">
                            <div class="notif-header">
                                <span>Notifications</span>
                                @if (isset($unreadNotifications) && $unreadNotifications->count() > 0)
                                    <a href="{{ route('notifications.readAll') }}"
                                        style="font-size: 0.75rem; color: #3498db; text-decoration: none;">Mark all
                                        read</a>
                                @endif
                            </div>

                            <div class="notif-list">
                                @if (isset($unreadNotifications) && $unreadNotifications->count() > 0)
                                    @foreach ($unreadNotifications as $notif)
                                        <a href="{{ route('notifications.read', $notif->id) }}" class="notif-item">
                                            <div class="notif-icon">
                                                @if ($notif->type == 'reservation_response')
                                                    <i class="fas fa-calendar-check" style="color: #27ae60;"></i>
                                                @elseif($notif->type == 'conflict')
                                                    <i class="fas fa-exclamation-triangle"
                                                        style="color: #e74c3c;"></i>
                                                @elseif($notif->type == 'account')
                                                    <i class="fas fa-user-plus" style="color: #3498db;"></i>
                                                @elseif($notif->type == 'maintenance')
                                                    <i class="fas fa-tools" style="color: #f39c12;"></i>
                                                @else
                                                    <i class="fas fa-info-circle" style="color: #95a5a6;"></i>
                                                @endif
                                            </div>
                                            <div class="notif-content">
                                                <p class="notif-title">{{ $notif->title }}</p>
                                                <p class="notif-msg">{{ Str::limit($notif->message, 50) }}</p>
                                                <span
                                                    class="notif-time">{{ $notif->created_at->diffForHumans() }}</span>
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
                                    'invite' => '#95a5a6', // Grey
                                ];
                                $color = $roleColors[Auth::user()->role?->name] ?? '#3498db';
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
                {{-- This card mirrors the professional white background --}}
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
