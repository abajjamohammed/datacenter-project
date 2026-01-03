<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <header style="background: #2c3e50; color: white; padding: 10px;">
        <h2>Data Center Manager</h2>
    </header>

    <main>
        @yield('content')  </main>

    <footer style="margin-top: 20px; text-align: center;">
        <p>&copy; 2026 Data Center App</p>
    </footer>
</body>
</html>