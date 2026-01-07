<!doctype html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel') — PandeglangTrip</title>

    {{-- Bootstrap 5.3 CDN (CSS) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Custom CSS (1 file) --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>

{{-- Topbar --}}
<nav class="navbar bg-body-tertiary border-bottom">
    <div class="container-fluid">
        <div class="d-flex align-items-center gap-2">
            <span class="fw-semibold">PandeglangTrip</span>
            <span class="text-muted small">|</span>
            <span class="small text-muted">@yield('panel_label', 'Panel')</span>
        </div>

        <div class="d-flex align-items-center gap-2">
            {{-- Theme toggle --}}
            <button id="themeToggle" type="button" class="btn btn-outline-primary btn-sm"
                    aria-label="Toggle theme">
                <span id="themeToggleText">Dark</span>
            </button>

            {{-- Basic user dropdown (no scope change, only display) --}}
            @auth
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        {{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item" type="submit">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
        </div>
    </div>
</nav>

<div class="d-flex panel-wrapper">
    {{-- Sidebar (static) --}}
    <aside class="panel-sidebar border-end bg-body-tertiary">
        <div class="p-3">
            <div class="small text-uppercase text-muted mb-2">Menu</div>

            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action" href="{{ url('/dashboard') }}">
                    Dashboard
                </a>

                {{-- Link dummy sesuai T3 (tanpa ubah auth flow/middleware) --}}
                <a class="list-group-item list-group-item-action" href="{{ url('/panel') }}">
                    Panel Pengelola
                </a>
                <a class="list-group-item list-group-item-action" href="{{ url('/admin') }}">
                    Admin
                </a>
                <a class="list-group-item list-group-item-action" href="{{ url('/account') }}">
                    Account
                </a>
            </div>
        </div>
    </aside>

    {{-- Content --}}
    <main class="flex-grow-1">
        <div class="container-fluid py-4">
            @yield('content')
        </div>
    </main>
</div>

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
