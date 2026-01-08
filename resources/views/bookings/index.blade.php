@extends('layouts.public')

@section('content')
<div class="container py-4">

  <div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="mb-0">My Bookings</h3>
  </div>

  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  <div class="card">
    <div class="card-body">

      @if($bookings->count() === 0)
        <div class="text-muted">Belum ada booking.</div>
      @else
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead>
              <tr>
                <th>Booking Code</th>
                <th>Item</th>
                <th class="text-end">Qty</th>
                <th class="text-end">Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Tanggal</th>
              </tr>
            </thead>
            <tbody>
              @foreach($bookings as $b)
                @php
                  $bookable = $b->bookable;
                  $type = $bookable ? class_basename($bookable) : '-';
                  // nama item bisa beda-beda antar project, jadi dibuat fallback
                  $name = $bookable->name ?? $bookable->title ?? $bookable->nama ?? '(Item tidak ditemukan)';
                @endphp
                <tr>
                  <td><code>{{ $b->booking_code }}</code></td>
                  <td>
                    <div class="fw-semibold">{{ $type }}</div>
                    <div class="text-muted">{{ $name }}</div>
                  </td>
                  <td class="text-end">{{ $b->qty }}</td>
                  <td class="text-end">Rp {{ number_format($b->total_price, 0, ',', '.') }}</td>
                  <td>{{ $b->payment_method }}</td>
                  <td>
                    <span class="badge text-bg-success">PAID</span>
                  </td>
                  <td>{{ $b->created_at->format('d M Y, H:i') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="mt-3">
          {{ $bookings->links() }}
        </div>
      @endif

    </div>
  </div>

</div>
@endsection
