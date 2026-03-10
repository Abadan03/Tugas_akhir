@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-between align-items-center py-3 px-5 border-3 border-bottom rounded-3">
  <h4 class="fw-semibold mb-0">{{ isset($category) ? 'Edit Kategori' : 'Tambah Kategori Master' }}</h4>
  <a href="{{ route('admin.category_masters.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="container mt-4 bg-white p-4 rounded-3 shadow-sm" style="max-width: 600px;">
  @include('layouts.flash-message')
  <form action="{{ isset($category) ? route('kategori.update', $category->id) : route('kategori.store') }}" method="POST">
    @csrf
    @if(isset($category))
      @method('PUT')
    @endif
    <div class="mb-3">
      <label for="nama_kategori" class="form-label fw-semibold">Nama Kategori</label>
      <input type="text" name="nama_kategori" id="nama_kategori" class="form-control @error('nama_kategori') is-invalid @enderror"
             placeholder="Masukkan nama kategori"
             value="{{ old('nama_kategori', $category->nama_kategori ?? '') }}" required>
      @error('nama_kategori')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
    <div class="mb-3">
      <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
      <textarea name="deskripsi" id="deskripsi" class="form-control" placeholder="Tuliskan deskripsi kategori (opsional)">{{ old('deskripsi', $category->deskripsi ?? '') }}</textarea>
    </div>
    <div class="d-flex justify-content-end gap-2">
      <button type="reset" class="btn btn-danger">Reset</button>
      <button type="submit" class="btn btn-success">{{ isset($category) ? 'Update' : 'Simpan' }}</button>
    </div>
  </form>
</div>
@endsection
