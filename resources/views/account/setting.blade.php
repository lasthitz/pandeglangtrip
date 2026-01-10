@extends('layouts.public')

@section('title', 'Account Settings')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="h4 mb-1">Account Settings</h1>
            <div class="text-secondary">Kelola informasi akun & keamanan.</div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Profile summary --}}
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h6 mb-3">Informasi Akun</h2>

                    <div class="mb-2">
                        <div class="text-muted small">Nama</div>
                        <div class="fw-semibold">{{ $user->name }}</div>
                    </div>

                    <div class="mb-2">
                        <div class="text-muted small">Email</div>
                        <div class="fw-semibold">{{ $user->email }}</div>
                    </div>

                    <div class="mt-3">
                        <span class="badge text-bg-primary text-uppercase">USER</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Security --}}
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h6 mb-2">Keamanan</h2>
                    <div class="text-secondary small mb-3">
                        Ubah password untuk menjaga keamanan akun kamu.
                    </div>

                    <a href="{{ route('account.password.edit') }}" class="btn btn-outline-secondary">
                        Change Password
                    </a>
                </div>
            </div>

            {{-- Optional: quick links --}}
            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <h2 class="h6 mb-2">Quick Links</h2>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ url('/') }}" class="btn btn-outline-primary btn-sm">Back to Home</a>
                        <a href="{{ url('/my-bookings') }}" class="btn btn-outline-primary btn-sm">My Bookings</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
