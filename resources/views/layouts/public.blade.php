<!doctype html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'PandeglangTrip')</title>

    {{-- Bootstrap 5.3 CDN (CSS) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Custom CSS (1 file) --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="{{ url('/') }}">PandeglangTrip</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNavbar"
                aria-controls="publicNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="publicNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Home</a>
                </li>
            </ul>

            <div class="d-flex gap-2 align-items-center">
                {{-- Theme toggle --}}
                <button id="themeToggle" type="button" class="btn btn-outline-primary btn-sm"
                        aria-label="Toggle theme">
                    <span id="themeToggleText">Dark</span>
                </button>

                {{-- Auth links (no scope change: hanya UI link sederhana) --}}
                @auth
                    <a class="btn btn-primary btn-sm" href="{{ url('/dashboard') }}">Dashboard</a>
                @else
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('login') }}">Login</a>
                    <a class="btn btn-primary btn-sm" href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<main class="flex-grow-1">
    <div class="container py-4">
        @yield('content')
    </div>
</main>

<footer class="border-top bg-body-tertiary">
    <div class="container py-3 small text-muted d-flex justify-content-between align-items-center">
        <span>© {{ date('Y') }} PandeglangTrip</span>
        <span class="d-none d-sm-inline">Bootstrap 5.3</span>
    </div>
</footer>

{{-- Bootstrap 5.3 CDN (JS Bundle) --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- Global Dark/Light Mode (persist localStorage) --}}
<script>
(function () {
    const STORAGE_KEY = 'pt_theme';
    const root = document.documentElement;
    const btn = document.getElementById('themeToggle');
    const txt = document.getElementById('themeToggleText');

    function applyTheme(theme) {
        root.setAttribute('data-bs-theme', theme);
        if (txt) txt.textContent = (theme === 'dark') ? 'Light' : 'Dark';
    }

    const saved = localStorage.getItem(STORAGE_KEY);
    const initial = (saved === 'dark' || saved === 'light') ? saved : 'light';
    applyTheme(initial);

    if (btn) {
        btn.addEventListener('click', function () {
            const current = root.getAttribute('data-bs-theme') || 'light';
            const next = (current === 'dark') ? 'light' : 'dark';
            localStorage.setItem(STORAGE_KEY, next);
            applyTheme(next);
        });
    }
})();
</script>
</body>
</html>
