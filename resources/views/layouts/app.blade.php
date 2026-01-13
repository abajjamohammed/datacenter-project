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
                <div class="nav-group">
                    <span class="nav-label">MAIN MENU</span>
                    <ul>
                        {{-- Fixed: Wildcard check keeps Dashboard active for all roles --}}
                        <li>
                            <a href="{{ route('home') }}"
                                class="{{ Request::is('*/dashboard') || Request::is('dashboard') || Request::is('/') ? 'active' : '' }}">
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('catalog.index') }}"
                                class="{{ Request::is('catalog*') ? 'active' : '' }}">
                                Resource Catalog
                            </a>
                        </li>
                        <li><a href="#">Activity Logs</a></li>
                    </ul>
                </div>

                <div class="nav-group">
                    <span class="nav-label">RESERVATIONS</span>
                    <ul>
                        @if (Auth::check() && Auth::user()->role->name === 'utilisateur_interne')
                            <li><a href="#">My Requests</a></li>
                            <li><a href="{{ route('reservations.index') }}"
                                    class="{{ Request::routeIs('reservations.index') ? 'active' : '' }}">Reservation
                                    History</a></li>
                        @else
                            {{-- Specific style for Guests to Apply for Access --}}
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

                <div class="nav-group">
                    <span class="nav-label">SUPPORT</span>
                    <ul>
                        <li>
                            <a href="{{ route('incidents.create') }}" class="{{ Request::routeIs('incidents.create') ? 'active' : '' }}">
                                Report Technical Issue</a>
                        </li>
                        <li>
                            <a href="{{ route('guest.policies') }}"
                                class="{{ Request::is('*/policies') ? 'active' : '' }}">Usage Policies</a>
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
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search resources...">
                    </form>
                </div>

                <div class="header-right">

                    <div class="notification-bell">
                        🔔<span class="bell-count">0</span>
                   </div>

                    @auth
                        {{-- User Profile: Flex container ensures space for logout --}}
                        <div class="user-profile">
                            <span class="badge">{{ Auth::user()->role->name }}</span>

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
                {{-- This card mirrors the professional white background --}}
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