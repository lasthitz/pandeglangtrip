@extends('layouts.panel')

@section('title', 'Booking Masuk (PAID)')
@section('panel_label', 'Panel Pengelola')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h1 class="h4 mb-1">Booking Masuk</h1>
    <div class="text-muted">
      Hanya menampilkan booking dengan status <span class="badge bg-success">PAID</span>
    </div>
  </div>
</div>

<div class="card shadow-sm">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Kode</th>
            <th>Nama Pemesan</th>
            <th>Email Pemesan</th>
            <th>Item</th>
            <th class="text-end">Qty</th>
            <th class="text-end">Total</th>
            <th>Payment</th>
            <th>Status</th>
            <th>Tanggal</th>
          </tr>
        </thead>

        <tbody>
          @forelse ($bookings as $booking)
            @php
              $userName  = trim((string) optional($booking->user)->name);
              $userEmail = (string) optional($booking->user)->email;

              $displayName = $userName !== '' ? $userName : ($userEmail !== '' ? $userEmail : '-');

              $itemType = !empty($booking->bookable_type) ? class_basename($booking->bookable_type) : '-';
              $itemName = ($booking->bookable && !empty($booking->bookable->name)) ? $booking->bookable->name : '-';

              $qty = (int) ($booking->qty ?? 0);

              $total = (int) ($booking->total_price ?? 0);
              $totalRupiah = 'Rp ' . number_format($total, 0, ',', '.');

              $paymentMethod = strtoupper((string) ($booking->payment_method ?? '-'));
              $createdAt = $booking->created_at ? $booking->created_at->format('d M Y, H:i') : '-';
            @endphp

            <tr>
              <td class="fw-semibold">{{ $booking->booking_code ?? '-' }}</td>
              <td>{{ $displayName }}</td>
              <td>{{ $userEmail !== '' ? $userEmail : '-' }}</td>
              <td>
                <div class="fw-semibold">{{ $itemType }}</div>
                <div class="text-muted small">{{ $itemName }}</div>
              </td>
              <td class="text-end">{{ $qty }}</td>
              <td class="text-end">{{ $totalRupiah }}</td>
              <td>
                <span class="badge bg-info text-dark">{{ $paymentMethod }}</span>
              </td>
              <td>
                <span class="badge bg-success">PAID</span>
              </td>
              <td>{{ $createdAt }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="text-center text-muted py-4">
                Belum ada booking berstatus <span class="badge bg-success">PAID</span>.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($bookings->hasPages())
      <div class="mt-3">
        {{ $bookings->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
