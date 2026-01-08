@extends('layouts.public')

<div class="alert alert-info">
  auth()->check(): <b>{{ auth()->check() ? 'true' : 'false' }}</b> |
  session()->getId(): <b>{{ session()->getId() }}</b>
</div>


@section('content')
<div class="container py-4">

  <div class="mb-3">
    <h3 class="mb-1">{{ $ticket->name ?? $ticket->title ?? $ticket->nama ?? 'Detail Ticket' }}</h3>
    <div class="text-muted">
      Rp {{ number_format((int)($ticket->price ?? $ticket->harga ?? 0), 0, ',', '.') }}
    </div>
  </div>

  <div class="card mb-3">
    <div class="card-body">
      <div class="text-muted">
        {{ $ticket->description ?? $ticket->deskripsi ?? '-' }}
      </div>
    </div>
  </div>

  {{-- CTA Booking (T6) --}}
  @php
    $isLoggedIn = auth()->check();
    $isUserRole = $isLoggedIn && (auth()->user()->role === 'user');

    $availableDate = $ticket->available_date ?? null;
    $isDateAvailable = true;

    if ($availableDate) {
      try {
        $isDateAvailable = \Carbon\Carbon::parse($availableDate)->startOfDay()
          ->greaterThanOrEqualTo(now()->startOfDay());
      } catch (\Throwable $e) {
        $isDateAvailable = true;
      }
    }
  @endphp

  <div class="card">
    <div class="card-body">
      <h5 class="mb-3">Pesan Ticket</h5>

      @if(!$isLoggedIn)
       <a href="{{ route('login', ['redirect' => url()->current()]) }}" class="btn btn-primary">
  Login untuk Pesan
</a>
      @elseif(!$isUserRole)
        <div class="alert alert-warning mb-0">Booking hanya untuk role <b>user</b>.</div>
      @else
        @if(!$isDateAvailable)
          <button class="btn btn-secondary" disabled>Tidak tersedia (tanggal lewat)</button>
        @else
          <form method="POST" action="{{ url('/booking/ticket/' . $ticket->id) }}" class="row g-2 align-items-end">
            @csrf

            <div class="col-12 col-md-3">
              <label class="form-label">Qty</label>
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
              <button class="btn btn-primary w-100" type="submit">Pesan (Dummy PAID)</button>
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

</div>
@endsection
