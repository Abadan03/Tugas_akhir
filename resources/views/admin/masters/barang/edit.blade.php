@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-between align-items-center py-3 px-5 border-3 border-bottom rounded-3">
  <h4 class="fw-semibold mb-0">Edit Item Master</h4>
  <a href="{{ route('items_masters.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="container mt-4 bg-white p-4 rounded-3 shadow-sm" style="max-width: 600px;">
  @include('layouts.flash-message')
  <form action="{{ route('barang.update', $item->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
      <label for="nama_barang" class="form-label fw-semibold">Nama Barang</label>
      <input type="text" name="nama_barang" id="nama_barang"
             class="form-control @error('nama_barang') is-invalid @enderror"
             placeholder="Masukkan nama barang"
             value="{{ old('nama_barang', $item->nama_barang) }}" required>
      @error('nama_barang')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
    <div class="mb-3">
      <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
      <textarea name="deskripsi" id="deskripsi" class="form-control"
                placeholder="Tuliskan deskripsi barang (opsional)">{{ old('deskripsi', $item->deskripsi) }}</textarea>
    </div>
    @if($item->kode)
    <div class="mb-3">
      <label class="form-label fw-semibold">Kode Item</label>
      <input type="text" class="form-control bg-light" value="{{ $item->kode }}" readonly disabled>
      <small class="text-muted">Kode tidak dapat diubah.</small>
    </div>
    @endif
    <div class="d-flex justify-content-end gap-2">
      <a href="{{ route('items_masters.index') }}" class="btn btn-secondary">Batal</a>
      <button type="submit" class="btn btn-success">Update</button>
    </div>
  </form>
</div>
@endsection
