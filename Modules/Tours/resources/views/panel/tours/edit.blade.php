@extends('layouts.panel')

@section('title', 'Edit Tour')
@section('panel_label', 'Pengelola')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h4 class="mb-0">Edit Paket Tur</h4>
    <div class="text-muted small">
      Status saat ini:
      @php
        $status = strtoupper($tour->approval_status ?? 'PENDING');
        $badge = 'secondary';
        if ($status === 'APPROVED') $badge = 'success';
        if ($status === 'REJECTED') $badge = 'danger';
        if ($status === 'PENDING')  $badge = 'warning';
      @endphp
      <span class="badge bg-{{ $badge }}">{{ $status }}</span>
      <span class="text-muted"> (read-only)</span>
    </div>
  </div>
</div>

@if ($errors->any())
  <div class="alert alert-danger">
    <div class="fw-semibold mb-1">Validasi gagal:</div>
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="card">
  <div class="card-body">
    <form action="{{ route('panel.tours.update', $tour) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $tour->name) }}" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Harga per Orang</label>
        <input type="number" name="price_per_person" class="form-control" min="0"
               value="{{ old('price_per_person', $tour->price_per_person) }}" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" rows="4" class="form-control" required>{{ old('description', $tour->description) }}</textarea>
      </div>

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Start Date</label>
          <input type="date" name="start_date" class="form-control"
                 value="{{ old('start_date', $tour->start_date ? $tour->start_date->format('Y-m-d') : '') }}">
        </div>
        <div class="col-md-6">
          <label class="form-label">End Date</label>
          <input type="date" name="end_date" class="form-control"
                 value="{{ old('end_date', $tour->end_date ? $tour->end_date->format('Y-m-d') : '') }}">
        </div>
      </div>

      <div class="mt-3 mb-3">
        <label class="form-label">Guide Name</label>
        <input type="text" name="guide_name" class="form-control"
               value="{{ old('guide_name', $tour->guide_name) }}">
      </div>

      <div class="mb-3">
        <label class="form-label">Itinerary (manual)</label>
        <textarea name="itinerary" rows="6" class="form-control"
                  placeholder="Contoh: Hari 1: ...&#10;Hari 2: ...">{{ old('itinerary', $tour->itinerary) }}</textarea>
      </div>

      @if($tour->image_path)
        <div class="mb-3">
          <label class="form-label">Gambar Saat Ini</label>
          <div class="border rounded p-2">
            <img src="{{ asset('storage/' . $tour->image_path) }}" alt="Tour Image" class="img-fluid" style="max-height: 220px;">
          </div>
          <div class="text-muted small mt-1">
            Upload gambar baru untuk replace (gambar lama otomatis dihapus).
          </div>
        </div>
      @endif

      <div class="mb-3">
        <label class="form-label">Ganti Gambar (opsional, jpg/png/webp, max 2MB)</label>
        <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
      </div>

      <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active"
          {{ old('is_active', $tour->is_active) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">
          Aktifkan (is_active)
        </label>
      </div>

      <div class="d-flex gap-2">
        <a href="{{ route('panel.tours.index') }}" class="btn btn-light btn-sm">Kembali</a>
        <button class="btn btn-primary btn-sm" type="submit">Update</button>
      </div>
    </form>
  </div>
</div>
@endsection
