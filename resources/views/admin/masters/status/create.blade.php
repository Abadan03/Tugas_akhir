@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-between align-items-center py-3 px-5 border-3 border-bottom rounded-3">
  <h4 class="fw-semibold mb-0">Tambah Status</h4>
  <a href="{{ route('status.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="container mt-4 bg-white p-4 rounded-3 shadow-sm" style="max-width: 600px;">
  <form action="{{ route('status.store') }}" method="POST">
    @csrf
    <div class="mb-3">
      <label for="nama_status" class="form-label fw-semibold">Nama Status</label>
      <input type="text" name="nama_status" id="nama_status" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
      <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
    </div>
    <div class="d-flex justify-content-end gap-2">
      <button type="reset" class="btn btn-danger">Reset</button>
      <button type="submit" class="btn btn-success">Simpan</button>
    </div>
  </form>
</div>
@endsection
