@extends('layouts.public')

@section('title', ($tour->name ?? 'Detail Tour') . ' — PandeglangTrip')

@section('content')
@php
  $isLoggedIn = auth()->check();
  $isUserRole = $isLoggedIn && (auth()->user()->role === 'user');

  // Tanggal berdasarkan kolom yang bener dari migration/model lu
  $startDate = $tour->start_date ?? null;
  $endDate   = $tour->end_date ?? null;

  // Rule sederhana: kalau start_date ada dan sudah lewat → disable booking
  $isDateAvailable = true;
  try {
    if ($startDate) {
      $isDateAvailable = \Carbon\Carbon::parse($startDate)->startOfDay()
        ->greaterThanOrEqualTo(now()->startOfDay());
    }
  } catch (\Throwable $e) {
    $isDateAvailable = true;
  }

  // Harga tour per orang
  $pricePerPerson = (int) ($tour->price_per_person ?? 0);
@endphp

<div class="mb-3">
  <h1 class="h3 fw-semibold mb-1">{{ $tour->name }}</h1>

  <div class="text-muted d-flex flex-wrap gap-2 align-items-center">
    <span>
      Rp {{ number_format($tour->price_per_person ?? 0, 0, ',', '.') }} / orang

    </span>

    @if($startDate)
      <span class="badge text-bg-secondary">
        Start: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}
      </span>
    @endif

    @if($endDate)
      <span class="badge text-bg-secondary">
        End: {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
      </span>
    @endif
  </div>
</div>

@if(!empty($tour->description))
  <div class="card mb-3">
    <div class="card-body">
      <p class="mb-0">{{ $tour->description }}</p>
    </div>
  </div>
@endif

<div class="card">
  <div class="card-body">
    <h2 class="h5 mb-3">Pesan Tour</h2>

    @if(!$isLoggedIn)
      <a href="{{ route('login') }}" class="btn btn-primary">
        Login untuk Pesan
      </a>

    @elseif(!$isUserRole)
      <div class="alert alert-warning mb-0">
        Booking hanya untuk role <b>user</b>.
      </div>

    @else
      @if(!$isDateAvailable)
        <button class="btn btn-secondary" disabled>
          Tidak tersedia (tanggal start sudah lewat)
        </button>
      @elseif($pricePerPerson <= 0)
        <div class="alert alert-danger mb-0">
          Harga tour belum valid (price_per_person = 0). Cek data tour di DB.
        </div>
      @else
        <form method="POST" action="{{ url('/booking/tour/' . $tour->id) }}" class="row g-2 align-items-end">
          @csrf

          <div class="col-12 col-md-3">
            <label class="form-label">Jumlah Orang</label>
            <input type="number" name="qty" class="form-control" min="1" value="1" required>
          </div>

          <div class="col-12 col-md-4">
            <label class="form-label">Metode Pembayaran</label>
            <select name="payment_method" class="form-select" required>
              <option value="QRIS">QRIS</option>
              <option value="DANA">DANA</option>
              <option value="OVO">OVO</option>
              <option value="GOPAY">GOPAY</option>
              <option value="TRANSFER_BANK">TRANSFER BANK</option>
            </select>
          </div>

          <div class="col-12 col-md-5">
            <button class="btn btn-primary w-100" type="submit">
              Pesan (Dummy PAID)
            </button>
          </div>
        </form>

        @if ($errors->any())
          <div class="alert alert-danger mt-3 mb-0">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
      @endif
    @endif
  </div>
</div>
@endsection
