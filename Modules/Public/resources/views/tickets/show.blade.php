@extends('layouts.public')

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

  {{-- Image (public detail) --}}
  @php
    // source of truth: image_path (T9 upload)
    $img = !empty($ticket->image_path) ? asset('storage/' . $ticket->image_path) : null;
  @endphp

  <div class="card mb-3">
    <div class="card-body">
      @if($img)
        <img
          src="{{ $img }}"
          alt="{{ $ticket->name }}"
          class="img-thumbnail w-100"
          style="height: 280px; object-fit: cover;"
        >
      @else
        <div class="pt-img-placeholder" style="height: 280px;">
          No Image
        </div>
      @endif
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
              <button class="btn btn-primary w-100" type="submit">Pesan</button>
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
    use App\Models\Ticket;

    $user = auth()->user();

    $canReview = false;
    if ($user) {
      $canReview = Booking::query()
        ->where('user_id', $user->id)
        ->where('status', 'PAID')
        ->where('bookable_type', Ticket::class)
        ->where('bookable_id', $ticket->id)
        ->exists();
    }

    // load reviews
    $reviews = $ticket->reviews()->with('user')->latest()->get();
  @endphp

  <hr class="my-4">

  <h5 class="mb-3">Ulasan</h5>

  {{-- List reviews (public) --}}
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

  {{-- Form review (hanya kalau login + pernah booking item) --}}
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

    <form method="POST" action="{{ route('reviews.store', ['type' => 'ticket', 'id' => $ticket->id]) }}">
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

</div>
@endsection
