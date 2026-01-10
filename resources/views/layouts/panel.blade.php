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

@php
    $role = strtolower(auth()->user()?->role ?? '');
    $isPengelola = auth()->check() && $role === 'pengelola';
    $isAdmin = auth()->check() && $role === 'admin';

    // helper active class (simple, no new feature)
    $ptIs = fn ($pattern) => request()->is($pattern) ? 'active fw-semibold' : '';
@endphp

{{-- Topbar --}}
<nav class="navbar bg-body-tertiary border-bottom">
    <div class="container-fluid">
        <div class="d-flex align-items-center gap-2">
            <a class="text-decoration-none fw-semibold text-body" href="{{ url('/') }}">PandeglangTrip</a>
            <span class="text-muted small">|</span>
            <span class="small text-muted">@yield('panel_label', 'Panel')</span>
        </div>

        <div class="d-flex align-items-center gap-2">
            {{-- Theme toggle --}}
            <button id="themeToggle" type="button" class="btn btn-outline-primary btn-sm" aria-label="Toggle theme">
                <span id="themeToggleText">Dark</span>
            </button>

            {{-- Sesuai blueprint: jangan tampilkan nama user --}}
            @auth
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                </form>
            @endauth
        </div>
    </div>
</nav>

<div class="d-flex panel-wrapper">
    {{-- Sidebar (final per role, tetap 1 layout) --}}
    <aside class="panel-sidebar border-end bg-body-tertiary">
        <div class="p-3">
            <div class="small text-uppercase text-muted mb-2">Menu</div>

            <div class="list-group list-group-flush">

                {{-- Pengelola --}}
                @if($isPengelola)
                    <a class="list-group-item list-group-item-action {{ $ptIs('panel/dashboard') }}"
                       href="{{ url('/panel/dashboard') }}">
                        Dashboard KPI
                    </a>

                    <a class="list-group-item list-group-item-action {{ $ptIs('panel/tickets*') }}"
                       href="{{ url('/panel/tickets') }}">
                        Tickets CRUD
                    </a>

                    <a class="list-group-item list-group-item-action {{ $ptIs('panel/tours*') }}"
                       href="{{ url('/panel/tours') }}">
                        Tours CRUD
                    </a>

                    <a class="list-group-item list-group-item-action {{ $ptIs('panel/bookings') }}"
                       href="{{ url('/panel/bookings') }}">
                        Bookings Masuk
                    </a>
                @endif

                {{-- Admin --}}
                @if($isAdmin)
                    <a class="list-group-item list-group-item-action {{ $ptIs('admin/tickets*') }}"
                       href="{{ url('/admin/tickets') }}">
                        Approval Tickets
                    </a>

                    <a class="list-group-item list-group-item-action {{ $ptIs('admin/tours*') }}"
                       href="{{ url('/admin/tours') }}">
                        Approval Tours
                    </a>
                @endif

                {{-- Common (all roles that use panel layout) --}}
                <a class="list-group-item list-group-item-action {{ $ptIs('account/password') }}"
                   href="{{ url('/account/password') }}">
                    Change Password
                </a>

            </div>

            <hr class="my-3">

            <div class="small text-muted">
                Role: <span class="badge text-bg-secondary text-uppercase">{{ $role ?: 'unknown' }}</span>
            </div>

            <a class="btn btn-outline-primary btn-sm w-100 mt-2" href="{{ url('/') }}">
                Back to Public
            </a>
        </div>
    </aside>

    {{-- Content --}}
    <main class="flex-grow-1">
        <div class="container-fluid py-4">

            {{-- polish: status/error tanpa nambah fitur --}}
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
