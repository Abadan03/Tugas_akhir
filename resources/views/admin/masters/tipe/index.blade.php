@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-between align-items-center py-3 px-5 border-3 border-bottom rounded-3">
  <div class="input-group">
    <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
    <input type="text" id="search" class="form-control border" style="max-width: 400px;" placeholder="Search nama tipe">
  </div>
  <div>{{ Auth()->user()->name ?? '' }}</div>
</div>

@include('layouts.flash-message')

<div class="py-4 px-3 d-flex justify-content-between align-items-center">
  <div>
    <h2 class="fw-bold mb-0">Master Tipe</h2>
    <p class="fw-light text-muted mb-0">Kelola jenis atau tipe barang</p>
  </div>
  <div>
    <a href="{{ route('tipe.create') }}" class="btn btn-skyblue">Tambah Tipe</a>
    <a href="{{ route('inventaris.index') }}" class="btn btn-secondary">Kembali</a>
  </div>
</div>

<div class="container-fluid mt-3">
  <table id="tableTipe" class="table table-striped">
    <thead>
      <tr>
        <th>No</th>
        <th>Nama Tipe</th>
        <th>Deskripsi</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($types as $index => $item)
      <tr>
        <td>{{ $types->firstItem() + $index }}</td>
        <td>{{ $item->nama_tipe }}</td>
        <td>{{ $item->deskripsi ?? '-' }}</td>
        <td class="d-flex gap-2">
          <a href="{{ route('tipe.edit', $item->id) }}" class="text-black"><i class="bi bi-pencil-square"></i></a>
          <form action="{{ route('tipe.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus tipe ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="text-danger border-0 bg-transparent"><i class="bi bi-trash"></i></button>
          </form>
        </td>
      </tr>
      @empty
        <tr><td colspan="4" class="text-center">Belum ada tipe barang.</td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="d-flex justify-content-end mt-3">{{ $types->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
