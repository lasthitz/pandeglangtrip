@extends('layouts.panel')

@section('panel_label', 'Panel Pengelola')
@section('title', 'Tiket Wisata')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 mb-0">CRUD Tiket Wisata</h1>
  <a href="{{ route('panel.tickets.create') }}" class="btn btn-primary btn-sm">+ Buat Tiket</a>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
  <div class="card-body table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th>Nama</th>
          <th class="text-end">Harga</th>
          <th>Tanggal</th>
          <th>Status</th>
          <th>Active</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($tickets as $t)
          <tr>
            <td>
              <div class="fw-semibold">{{ $t->name }}</div>
              @if($t->image_path)
                <small class="text-muted">Gambar: {{ $t->image_path }}</small>
              @else
                <small class="text-muted">Tanpa gambar</small>
              @endif
            </td>

            <td class="text-end">Rp{{ number_format((int)$t->price, 0, ',', '.') }}</td>

            <td>{{ $t->visit_date ? $t->visit_date->format('Y-m-d') : '-' }}</td>

            <td>
              @php
                $status = strtoupper($t->approval_status ?? 'PENDING');
                $badge = match($status) {
                  'APPROVED' => 'success',
                  'REJECTED' => 'danger',
                  default => 'warning',
                };
              @endphp
              <span class="badge bg-{{ $badge }}">{{ $status }}</span>
            </td>

            <td>
              @if($t->is_active)
                <span class="badge bg-success">Yes</span>
              @else
                <span class="badge bg-secondary">No</span>
              @endif
            </td>

            <td class="text-end">
              <a href="{{ route('panel.tickets.edit', $t->id) }}" class="btn btn-outline-primary btn-sm">Edit</a>

              <form action="{{ route('panel.tickets.destroy', $t->id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Hapus tiket ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger btn-sm">Hapus</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center text-muted py-4">Belum ada tiket.</td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <div class="mt-3">
      {{ $tickets->links() }}
    </div>
  </div>
</div>
@endsection
