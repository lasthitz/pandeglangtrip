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

@php
    $role = strtolower(auth()->user()?->role ?? '');
    $isUser = auth()->check() && $role === 'user';
    $isPengelola = auth()->check() && $role === 'pengelola';
    $isAdmin     = auth()->check() && $role === 'admin';

@endphp

<nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="{{ url('/') }}">PandeglangTrip</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNavbar"
                aria-controls="publicNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="publicNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-lg-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active fw-semibold' : '' }}" href="{{ url('/') }}">
                        Home
                    </a>
                </li>

                {{-- Anchor: aman dari page mana pun --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/#tickets') }}">Tickets</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/#tours') }}">Tours</a>
                </li>
                 @if($isUser)
        <li class="nav-item">
            <a class="nav-link {{ request()->is('my-bookings*') ? 'active fw-semibold' : '' }}"
               href="{{ url('/my-bookings') }}">
                My Bookings
            </a>
        </li>
    @endif
            </ul>

            <div class="d-flex gap-2 align-items-center">
    {{-- Theme toggle --}}
    <button id="themeToggle" type="button" class="btn btn-outline-primary btn-sm" aria-label="Toggle theme">
        <span id="themeToggleText">Dark</span>
    </button>

    @guest
        <a class="btn btn-outline-secondary btn-sm" href="{{ route('login') }}">Login</a>
        <a class="btn btn-primary btn-sm" href="{{ route('register') }}">Register</a>
    @endguest

    @auth
        {{-- ADMIN / PENGELOLA: dashboard button + logout --}}
        @if($isAdmin)
            <a class="btn btn-outline-warning btn-sm" href="{{ url('/admin/tickets') }}">
                Admin Dashboard
            </a>

            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">Logout</button>
            </form>

        @elseif($isPengelola)
            <a class="btn btn-outline-info btn-sm" href="{{ url('/panel/dashboard') }}">
                Dashboard Pengelola
            </a>

            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">Logout</button>
            </form>

        @elseif($isUser)
            {{-- USER: dropdown nama + change password + logout --}}
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                    {{ auth()->user()->name }}
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="px-3 py-2">
                        <div class="fw-semibold">{{ auth()->user()->name }}</div>
                        <div class="text-muted small">{{ auth()->user()->email }}</div>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <a class="dropdown-item" href="{{ route('account.password.edit') }}">
                            Change Password
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        @else
            {{-- fallback kalau ada role lain --}}
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">Logout</button>
            </form>
        @endif
    @endauth
</div>

</nav>

<main class="flex-grow-1">
    <div class="container py-4">
        {{-- optional polish: status/error tanpa nambah fitur --}}
        @if (session('status'))
            <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
                <span class="badge text-bg-success">OK</span>
                <div class="mb-0">{{ session('status') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <div class="fw-semibold mb-1">Terjadi error:</div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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
