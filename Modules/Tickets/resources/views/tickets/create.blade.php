@extends('layouts.panel')

@section('panel_label', 'Panel Pengelola')
@section('title', 'Buat Tiket')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 mb-0">Buat Tiket</h1>
  <a href="{{ route('panel.tickets.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
</div>

<div class="card">
  <div class="card-body">
    <form action="{{ route('panel.tickets.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @include('tickets::tickets._form', ['ticket' => $ticket])
      <div class="mt-3">
        <button class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection
