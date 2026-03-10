@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-between align-items-center py-3 px-5 border-3 border-bottom rounded-3">
  <h4 class="fw-semibold mb-0">{{ isset($type) ? 'Edit Tipe' : 'Tambah Tipe Master' }}</h4>
  <a href="{{ route('type_masters.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="container mt-4 bg-white p-4 rounded-3 shadow-sm" style="max-width: 600px;">
  @include('layouts.flash-message')
  <form action="{{ isset($type) ? route('tipe.update', $type->id) : route('tipe.store') }}" method="POST">
    @csrf
    @if(isset($type))
      @method('PUT')
    @endif
    <div class="mb-3">
      <label for="nama_tipe" class="form-label fw-semibold">Nama Tipe</label>
      <input type="text" name="nama_tipe" id="nama_tipe" class="form-control @error('nama_tipe') is-invalid @enderror"
             placeholder="Masukkan nama tipe"
             value="{{ old('nama_tipe', $type->nama_tipe ?? '') }}" required>
      @error('nama_tipe')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
    <div class="mb-3">
      <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
      <textarea name="deskripsi" id="deskripsi" class="form-control" placeholder="Tuliskan deskripsi tipe (opsional)">{{ old('deskripsi', $type->deskripsi ?? '') }}</textarea>
    </div>
    <div class="d-flex justify-content-end gap-2">
      <button type="reset" class="btn btn-danger">Reset</button>
      <button type="submit" class="btn btn-success">{{ isset($type) ? 'Update' : 'Simpan' }}</button>
    </div>
  </form>
</div>
@endsection
