@extends('layouts.panel')

@section('title', 'Dashboard KPI')
@section('panel_label', 'Pengelola')

@section('content')
<div class="mb-3">
    <h1 class="h4 mb-1">Dashboard KPI</h1>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total Pemesanan (PAID)</div>
                <div class="h3">{{ number_format($total_pemesanan) }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total Destinasi Aktif</div>
                <div class="h3">{{ number_format($total_destinasi_aktif) }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total Pendapatan</div>
                <div class="h3">
                    Rp {{ number_format($total_pendapatan, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
