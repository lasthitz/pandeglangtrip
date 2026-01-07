@extends('layouts.public')

@section('title', 'Detail Tour')

@section('content')
<div class="container py-4">
    <div class="mb-3">
        <a href="{{ route('public.home') }}" class="btn btn-outline-secondary btn-sm">&larr; Kembali</a>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm">
                @if($tour->image_path)
                    <img src="{{ asset($tour->image_path) }}" class="card-img-top" alt="{{ $tour->name }}">
                @else
                    <div class="ratio ratio-16x9 bg-body-tertiary d-flex align-items-center justify-content-center">
                        <span class="text-secondary small">No Image</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-12 col-lg-6">
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

            <h1 class="h4 mb-2">{{ $tour->name }}</h1>
            <div class="h5 mb-3">Rp {{ number_format($tour->price_per_person, 0, ',', '.') }} / orang</div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h6">Deskripsi</h2>
                    <p class="text-secondary">{{ $tour->description }}</p>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <h2 class="h6">Tanggal</h2>
                            @if(is_null($tour->start_date) || is_null($tour->end_date))
                                <p class="text-secondary mb-0">Belum tersedia.</p>
                            @else
                                <p class="text-secondary mb-0">
                                    {{ $tour->start_date->format('d M Y') }} - {{ $tour->end_date->format('d M Y') }}
                                </p>
                            @endif
                        </div>

                        <div class="col-12 col-md-6">
                            <h2 class="h6">Guide</h2>
                            <p class="text-secondary mb-0">{{ $tour->guide_name ?? 'Belum ditentukan.' }}</p>
                        </div>
                    </div>

                    <hr>

                    <h2 class="h6">Itinerary</h2>
                    @if($tour->itinerary)
                        <div class="bg-body-tertiary border rounded p-3" style="white-space: pre-line;">
                            {{ $tour->itinerary }}
                        </div>
                    @else
                        <p class="text-secondary mb-0">Belum tersedia.</p>
                    @endif

                    <div class="mt-3">
                        <button class="btn btn-success" disabled>Pesan (soon)</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
