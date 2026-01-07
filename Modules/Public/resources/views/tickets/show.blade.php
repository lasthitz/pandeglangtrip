@extends('layouts.public')

@section('title', 'Detail Ticket')

@section('content')
<div class="container py-4">
    <div class="mb-3">
        <a href="{{ route('public.home') }}" class="btn btn-outline-secondary btn-sm">&larr; Kembali</a>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm">
                @if($ticket->image_path)
                    <img src="{{ asset($ticket->image_path) }}" class="card-img-top" alt="{{ $ticket->name }}">
                @else
                    <div class="ratio ratio-16x9 bg-body-tertiary d-flex align-items-center justify-content-center">
                        <span class="text-secondary small">No Image</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="d-flex gap-2 flex-wrap mb-2">
                <span class="badge text-bg-primary">Ticket</span>
                @if(is_null($ticket->visit_date))
                    <span class="badge text-bg-warning">Tanggal belum tersedia</span>
                @else
                    <span class="badge text-bg-light border">{{ $ticket->visit_date->format('d M Y') }}</span>
                @endif
            </div>

            <h1 class="h4 mb-2">{{ $ticket->name }}</h1>
            <div class="h5 mb-3">Rp {{ number_format($ticket->price, 0, ',', '.') }}</div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h6">Deskripsi</h2>
                    <p class="text-secondary mb-0">{{ $ticket->description }}</p>

                    <hr>

                    <h2 class="h6">Tanggal Kunjungan</h2>
                    @if(is_null($ticket->visit_date))
                        <p class="text-secondary mb-0">Belum tersedia.</p>
                    @else
                        <p class="text-secondary mb-0">{{ $ticket->visit_date->format('d M Y') }}</p>
                    @endif

                    <div class="mt-3">
                        <button class="btn btn-primary" disabled>Pesan (soon)</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
