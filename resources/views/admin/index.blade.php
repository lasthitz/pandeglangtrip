@extends('layouts.panel')

@section('title', 'Admin')
@section('panel_label', 'Admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Admin Area (Dummy)</h1>
        <span class="badge text-bg-primary">T3 Dummy</span>
    </div>

    <div class="alert alert-info mb-0">
        Halaman ini hanya untuk verifikasi akses role <b>admin</b> dan layout panel pada TAB T4.
        Tidak ada fitur bisnis.
    </div>
@endsection
