@extends('layouts.public')

@section('title', ($tour->name ?? 'Detail Tour') . ' — PandeglangTrip')

@section('content')
@php
  $isLoggedIn = auth()->check();
  $isUserRole = $isLoggedIn && (auth()->user()->role === 'user');

  $startDate = $tour->start_date ?? null;
  $endDate   = $tour->end_date ?? null;

  $isDateAvailable = true;
  try {
    if ($startDate) {
      $isDateAvailable = \Carbon\Carbon::parse($startDate)->startOfDay()
        ->greaterThanOrEqualTo(now()->startOfDay());
    }
  } catch (\Throwable $e) {
    $isDateAvailable = true;
  }

  $pricePerPerson = (int) ($tour->price_per_person ?? 0);

  // source of truth: image_path (T10 upload)
  $img = !empty($tour->image_path) ? asset('storage/' . $tour->image_path) : null;
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

{{-- ===================== --}}
{{-- T10 — Guide + Itinerary + Image (Read-only Public) --}}
{{-- ===================== --}}

@if($img)
  <img
    src="{{ $img }}"
    alt="{{ $tour->name }}"
    class="img-thumbnail w-100"
    style="height: 280px; object-fit: cover;"
  >
@else
  <div class="pt-img-placeholder" style="height: 280px;">
    No Image
  </div>
@endif

@if(!empty($tour->guide_name))
  <div class="card mb-3 mt-3">
    <div class="card-body">
      <h2 class="h6 mb-2">Guide</h2>
      <div>{{ $tour->guide_name }}</div>
    </div>
  </div>
@endif

@if(!empty($tour->itinerary))
  <div class="card mb-3">
    <div class="card-body">
      <h2 class="h6 mb-2">Itinerary</h2>
      <div class="text-muted small">Read-only</div>
      <div class="mt-2">{!! nl2br(e($tour->itinerary)) !!}</div>
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
              Pesan
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

{{-- ===================== --}}
{{-- T7 — Review Section   --}}
{{-- ===================== --}}
@php
  use App\Models\Booking;
  use App\Models\Tour;

  $user = auth()->user();

  $canReview = false;
  if ($user) {
    $canReview = Booking::query()
      ->where('user_id', $user->id)
      ->where('status', 'PAID')
      ->where('bookable_type', Tour::class)
      ->where('bookable_id', $tour->id)
      ->exists();
  }

  $reviews = $tour->reviews()->with('user')->latest()->get();
@endphp

<hr class="my-4">

<h5 class="mb-3">Ulasan</h5>

@if($reviews->count() === 0)
  <p class="text-muted mb-0">Belum ada ulasan.</p>
@else
  <div class="d-flex flex-column gap-3">
    @foreach($reviews as $r)
      <div class="border rounded p-3">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <strong>{{ $r->user->name ?? $r->user->email }}</strong>
            <div class="small text-muted">{{ $r->created_at?->format('d M Y H:i') }}</div>
          </div>
          <span class="badge bg-secondary">Rating: {{ $r->rating }}/5</span>
        </div>

        <div class="mt-2">
          {{ $r->comment }}
        </div>
      </div>
    @endforeach
  </div>
@endif

@if(auth()->check() && $canReview)
  <hr class="my-4">

  <h6 class="mb-2">Tulis Ulasan</h6>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('reviews.store', ['type' => 'tour', 'id' => $tour->id]) }}">
    @csrf

    <div class="mb-2">
      <label class="form-label">Rating</label>
      <select name="rating" class="form-select" required>
        <option value="">-- pilih --</option>
        @for($i=1; $i<=5; $i++)
          <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }}</option>
        @endfor
      </select>
    </div>

    <div class="mb-2">
      <label class="form-label">Komentar</label>
      <textarea name="comment" class="form-control" rows="3" required>{{ old('comment') }}</textarea>
    </div>

    <button class="btn btn-primary" type="submit">Kirim Ulasan</button>
  </form>
@endif

@endsection
