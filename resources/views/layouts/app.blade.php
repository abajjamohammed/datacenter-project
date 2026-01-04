<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AlphaFold DataCenter - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    @stack('extra-css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="app-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo-sphere"></div>
                <h1>AlphaFold DC</h1>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-group">
                    <span class="nav-label">MAIN MENU</span>
                    <ul>
                        <li><a href="/" class="{{ Request::is('/') ? 'active' : '' }}">Dashboard</a></li>
                        <li><a href="#">Resource Catalog</a></li>
                        <li><a href="#">Activity Logs</a></li>
                    </ul>
                </div>

                <div class="nav-group">
                    <span class="nav-label">RESERVATIONS</span>
                    <ul>
                        <li><a href="#">My Requests</a></li>
                        <li><a href="#">Reservation History</a></li>
                    </ul>
                </div>

                <div class="nav-group">
                    <span class="nav-label">SUPPORT</span>
                    <ul>
                        <li><a href="#">Report Issue</a></li>
                        <li><a href="#">Usage Policies</a></li>
                    </ul>
                </div>
            </nav>
        </aside>

        <div class="main-wrapper">
            <header class="top-header">
                <div class="header-left">
                    <nav class="breadcrumbs">
                        <a href="#">AlphaFold DataCenter</a>
                        <span class="crumb-divider">/</span>
                        <span class="current-page">@yield('title', 'Dashboard')</span>
                    </nav>
                    
                    <div class="header-search">
                        <span class="search-icon">🔍</span>
                        <input type="text" placeholder="Search resources or IDs...">
                    </div>
                </div>

                <div class="header-right">
                    <button class="btn-primary-small">+ New Request</button>
                    <div class="notification-bell">
                        🔔<span class="bell-count">0</span>
                    </div>
                    <div class="user-profile">
                        <span class="user-role">Guest</span>
                        <span class="user-name" id="guest-id-display">Guest Loading...</span>
                    </div>
                </div>
            </header>

            <main class="content-body">
                <div class="page-card">
                    @yield('content')
                </div>
            </main>

            <footer class="main-footer">
                <div class="footer-section">
                    <p>&copy; 2026 <span>AlphaFold DataCenter</span>. All rights reserved.</p>
                </div>
                
                <div class="footer-links">
                    <div class="link-group">
                        <a href="#">About Us</a>
                        <span class="separator">|</span>
                        <a href="#">Contact Support</a>
                        <span class="separator">|</span>
                        <a href="#">Privacy Policy</a>
                        <span class="separator">|</span>
                        <a href="#">Terms of Use</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Generate random 4-digit number
            const randomNum = Math.floor(1000 + Math.random() * 9000);
            const guestDisplay = document.getElementById('guest-id-display');
            if (guestDisplay) {
                guestDisplay.innerText = "Guest" + randomNum;
            }
        });
    </script>
</body>
</html>