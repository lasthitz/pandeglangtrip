@extends('layouts.public')

@section('title', 'PandeglangTrip - Home')

@section('content')
<div class="container py-4">
    <div class="mb-3">
        <h1 class="h4 mb-1">Jelajahi PandeglangTrip</h1>
        <p class="text-secondary mb-0">Temukan tiket wisata dan paket tur terbaik di Pandeglang.</p>
    </div>

    {{-- TICKETS --}}
    <div class="mt-4" id="tickets">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h2 class="h5 mb-0">Tickets</h2>
            <span class="badge text-bg-secondary">Ticket</span>
        </div>

        @if($tickets->isEmpty())
            <div class="alert alert-light border mb-0">Belum ada ticket yang tersedia.</div>
        @else
            <div class="row g-3">
                @foreach($tickets as $ticket)
                    @php
                        // source of truth: image_path (T9 upload)
                        $ticketImg = !empty($ticket->image_path) ? asset('storage/' . $ticket->image_path) : null;
                    @endphp

                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            {{-- Image / Placeholder (konsisten tinggi) --}}
                            @if($ticketImg)
                                <img
                                    src="{{ $ticketImg }}"
                                    class="card-img-top"
                                    alt="{{ $ticket->name }}"
                                    style="height: 180px; object-fit: cover;"
                                >
                            @else
                                <div class="pt-img-placeholder" style="height: 180px;">
                                    No Image
                                </div>
                            @endif

                            <div class="card-body d-flex flex-column">
                                <div class="d-flex gap-2 flex-wrap mb-2">
                                    <span class="badge text-bg-primary">Ticket</span>

                                    @if(is_null($ticket->visit_date))
                                        <span class="badge text-bg-warning">Tanggal belum tersedia</span>
                                    @else
                                        <span class="badge text-bg-light border">
                                            {{ $ticket->visit_date->format('d M Y') }}
                                        </span>
                                    @endif
                                </div>

                                <h3 class="h6 mb-1">{{ $ticket->name }}</h3>
                                <div class="text-secondary small mb-2">
                                    Rp {{ number_format($ticket->price, 0, ',', '.') }}
                                </div>

                                <p class="text-secondary small flex-grow-1 mb-3">
                                    {{ \Illuminate\Support\Str::limit($ticket->description, 110) }}
                                </p>

                                <div class="d-flex gap-2">
                                    <a href="{{ route('public.tickets.show', $ticket) }}" class="btn btn-outline-primary btn-sm">
                                        Lihat Detail
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- TOURS --}}
    <div class="mt-5" id="tours">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h2 class="h5 mb-0">Tours</h2>
            <span class="badge text-bg-secondary">Tour</span>
        </div>

        @if($tours->isEmpty())
            <div class="alert alert-light border mb-0">Belum ada tour yang tersedia.</div>
        @else
            <div class="row g-3">
                @foreach($tours as $tour)
                    @php
                        // source of truth: image_path (T10 upload)
                        $tourImg = !empty($tour->image_path) ? asset('storage/' . $tour->image_path) : null;
                    @endphp

                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            {{-- Image / Placeholder (konsisten tinggi) --}}
                            @if($tourImg)
                                <img
                                    src="{{ $tourImg }}"
                                    class="card-img-top"
                                    alt="{{ $tour->name }}"
                                    style="height: 180px; object-fit: cover;"
                                >
                            @else
                                <div class="pt-img-placeholder" style="height: 180px;">
                                    No Image
                                </div>
                            @endif

                            <div class="card-body d-flex flex-column">
                                <div class="d-flex gap-2 flex-wrap mb-2">
                                    <span class="badge text-bg-success">Tour</span>

                                    @if(is_null($tour->start_date) || is_null($tour->end_date))
                                        <span class="badge text-bg-warning">Tanggal belum tersedia</span>
                                    @else
                                        <span class="badge text-bg-light border">
                                            {{ $tour->start_date->format('d M Y') }} - {{ $tour->end_date->format('d M Y') }}
                                        </span>
                                    @endif
                                </div>

                                <h3 class="h6 mb-1">{{ $tour->name }}</h3>
                                <div class="text-secondary small mb-2">
                                    Rp {{ number_format($tour->price_per_person, 0, ',', '.') }} / orang
                                </div>

                                <p class="text-secondary small flex-grow-1 mb-3">
                                    {{ \Illuminate\Support\Str::limit($tour->description, 110) }}
                                </p>

                                <div class="d-flex gap-2">
                                    <a href="{{ route('public.tours.show', $tour) }}" class="btn btn-outline-success btn-sm">
                                        Lihat Detail
                                    </a>

                                    
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
