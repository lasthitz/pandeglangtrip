@extends('layouts.panel')

@section('title', 'Approval Tickets — Admin')

@section('panel_label', 'Admin Approval')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
  <div>
    <h1 class="h4 mb-1">Approval Konten — Tickets</h1>
    <div class="text-muted small">
      Default menampilkan <b>PENDING</b>. Approve/Reject hanya mengubah <code>approval_status</code> (tidak menyentuh <code>is_active</code>).
    </div>
  </div>

  <div class="d-flex flex-wrap gap-2">
    <a href="{{ route('admin.tickets.index', ['status' => 'PENDING']) }}"
       class="btn btn-sm {{ $status === 'PENDING' ? 'btn-primary' : 'btn-outline-primary' }}">Pending</a>

    <a href="{{ route('admin.tickets.index', ['status' => 'APPROVED']) }}"
       class="btn btn-sm {{ $status === 'APPROVED' ? 'btn-success' : 'btn-outline-success' }}">Approved</a>

    <a href="{{ route('admin.tickets.index', ['status' => 'REJECTED']) }}"
       class="btn btn-sm {{ $status === 'REJECTED' ? 'btn-danger' : 'btn-outline-danger' }}">Rejected</a>

    <a href="{{ route('admin.tickets.index', ['status' => 'ALL']) }}"
       class="btn btn-sm {{ $status === 'ALL' ? 'btn-dark' : 'btn-outline-dark' }}">All</a>
  </div>
</div>

@if (session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow-sm">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:72px;">Image</th>
            <th>Name</th>
            <th style="width:140px;">Price</th>
            <th style="width:160px;">Approval</th>
            <th style="width:120px;">Active</th>
            <th style="width:220px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($tickets as $ticket)
            @php
              $badgeClass = match($ticket->approval_status) {
                'APPROVED' => 'bg-success',
                'REJECTED' => 'bg-danger',
                default => 'bg-warning text-dark',
              };

              $imgUrl = $ticket->image_path ? asset('storage/' . $ticket->image_path) : null;
            @endphp

            <tr>
              <td>
                @if ($imgUrl)
                  <img src="{{ $imgUrl }}" alt="ticket" class="rounded border"
                       style="width:56px;height:56px;object-fit:cover;">
                @else
                  <div class="rounded border bg-light d-flex align-items-center justify-content-center"
                       style="width:56px;height:56px;">
                    <span class="text-muted small">—</span>
                  </div>
                @endif
              </td>

              <td>
                <div class="fw-semibold">{{ $ticket->name }}</div>
                <div class="text-muted small">ID: {{ $ticket->id }}</div>
              </td>

              <td>
                Rp {{ number_format((int) $ticket->price, 0, ',', '.') }}
              </td>

              <td>
                <span class="badge {{ $badgeClass }}">{{ $ticket->approval_status }}</span>
              </td>

              <td>
                @if ($ticket->is_active)
                  <span class="badge bg-success-subtle text-success border border-success-subtle">Active</span>
                @else
                  <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Inactive</span>
                @endif
              </td>

              <td>
                <div class="d-flex flex-wrap gap-2">
                  <form action="{{ route('admin.tickets.approve', $ticket) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-sm btn-success"
                      {{ $ticket->approval_status === 'APPROVED' ? 'disabled' : '' }}>
                      Approve
                    </button>
                  </form>

                  <form action="{{ route('admin.tickets.reject', $ticket) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-sm btn-danger"
                      {{ $ticket->approval_status === 'REJECTED' ? 'disabled' : '' }}>
                      Reject
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted py-4">
                Tidak ada data untuk status <b>{{ $status }}</b>.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3">
      {{ $tickets->links() }}
    </div>
  </div>
</div>
@endsection
