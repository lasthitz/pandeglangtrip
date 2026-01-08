@php $isEdit = isset($ticket) && $ticket->exists; @endphp

@if($errors->any())
  <div class="alert alert-danger">
    <div class="fw-semibold mb-1">Validasi gagal:</div>
    <ul class="mb-0">
      @foreach($errors->all() as $e)
        <li>{{ $e }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Nama</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $ticket->name ?? '') }}" required>
  </div>

  <div class="col-md-6">
    <label class="form-label">Harga</label>
    <input type="number" name="price" class="form-control" min="0"
           value="{{ old('price', $ticket->price ?? 0) }}" required>
  </div>

  <div class="col-md-6">
    <label class="form-label">Tanggal Kunjungan (opsional)</label>
    <input type="date" name="visit_date" class="form-control"
           value="{{ old('visit_date', ($ticket->visit_date ?? null) ? $ticket->visit_date->format('Y-m-d') : '') }}">
  </div>

  <div class="col-md-6">
    <label class="form-label d-block">Aktif</label>
    <div class="form-check mt-2">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
             {{ old('is_active', $ticket->is_active ?? false) ? 'checked' : '' }}>
      <label class="form-check-label" for="is_active">Tampilkan (aktif)</label>
    </div>
    <div class="form-text">Public tetap hanya menampilkan <b>APPROVED</b> + <b>is_active=1</b>.</div>
  </div>

  <div class="col-12">
    <label class="form-label">Deskripsi</label>
    <textarea name="description" rows="5" class="form-control" required>{{ old('description', $ticket->description ?? '') }}</textarea>
  </div>

  <div class="col-12">
    <label class="form-label">Gambar (opsional, 1 file)</label>
    <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">

    @if($isEdit)
      <div class="mt-2 d-flex align-items-start gap-3">
        <div>
          <div class="text-muted small">Approval Status:</div>
          @php
            $status = strtoupper($ticket->approval_status ?? 'PENDING');
            $badge = match($status) {
              'APPROVED' => 'success',
              'REJECTED' => 'danger',
              default => 'warning',
            };
          @endphp
          <span class="badge bg-{{ $badge }}">{{ $status }}</span>
        </div>

        <div>
          <div class="text-muted small">Preview:</div>
          @if($ticket->image_path)
            <img src="{{ asset('storage/'.$ticket->image_path) }}" alt="Preview"
                 class="rounded border" style="max-height:120px">
          @else
            <span class="text-muted">Tidak ada gambar</span>
          @endif
        </div>
      </div>
    @endif
  </div>
</div>
