@extends('layouts.panel')

@section('title', 'Paket Tur')
@section('panel_label', 'Pengelola')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h4 class="mb-0">Paket Tur</h4>
    <div class="text-muted small">CRUD Paket Tur (guide + itinerary + 1 gambar)</div>
  </div>

  <a href="{{ route('panel.tours.create') }}" class="btn btn-primary btn-sm">
    + Tambah Tour
  </a>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Harga/Orang</th>
            <th>Date Range</th>
            <th>Guide</th>
            <th>Status</th>
            <th>Active</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($tours as $tour)
            <tr>
              <td class="fw-semibold">{{ $tour->name }}</td>
              <td>Rp{{ number_format($tour->price_per_person ?? 0, 0, ',', '.') }}</td>
              <td class="text-muted">
                {{ $tour->start_date ? $tour->start_date->format('d M Y') : '-' }}
                —
                {{ $tour->end_date ? $tour->end_date->format('d M Y') : '-' }}
              </td>
              <td>{{ $tour->guide_name ?? '-' }}</td>
              <td>
                @php
                  $status = strtoupper($tour->approval_status ?? 'PENDING');
                  $badge = 'secondary';
                  if ($status === 'APPROVED') $badge = 'success';
                  if ($status === 'REJECTED') $badge = 'danger';
                  if ($status === 'PENDING')  $badge = 'warning';
                @endphp
                <span class="badge bg-{{ $badge }}">{{ $status }}</span>
              </td>
              <td>
                @if($tour->is_active)
                  <span class="badge bg-success">YES</span>
                @else
                  <span class="badge bg-secondary">NO</span>
                @endif
              </td>
              <td class="text-end">
                <a href="{{ route('panel.tours.edit', $tour) }}" class="btn btn-outline-primary btn-sm">
                  Edit
                </a>

                <form action="{{ route('panel.tours.destroy', $tour) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Yakin hapus tour ini?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center text-muted py-4">
                Belum ada data tour.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3">
      {{ $tours->links() }}
    </div>
  </div>
</div>
@endsection
