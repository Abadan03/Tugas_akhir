@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-between align-items-center py-3 px-5 border-3 border-bottom rounded-3">
  <div class="input-group">
    <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
    <input type="text" id="search" class="form-control border" style="max-width: 400px;" placeholder="Search nama kategori">
  </div>
  <div>
    @if (Auth()->user())
      {{ Auth()->user()->name }}
    @endif
  </div>
</div>

@include('layouts.flash-message')

<div class="py-4 px-3 d-flex justify-content-between align-items-center">
  <div>
    <h2 class="fw-bold mb-0">Master Kategori</h2>
    <p class="fw-light text-muted mb-0">Kelola data kategori barang</p>
  </div>
  <div>
    <a href="{{ route('kategori.create') }}" class="btn btn-skyblue">Tambah Kategori</a>
    <a href="{{ route('inventaris.index') }}" class="btn btn-secondary">Kembali</a>
  </div>
</div>

<div class="container-fluid mt-3">
  <table id="tableKategori" class="table table-striped" style="width:100%">
    <thead>
      <tr>
        <th>No</th>
        <th>Kode</th>
        <th>Nama Kategori</th>
        <th>Deskripsi</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($categories as $index => $item)
        <tr>
          <td>{{ $categories->firstItem() + $index }}</td>
          <td><span class="badge bg-secondary">{{ $item->kode ?? '-' }}</span></td>
          <td>{{ $item->nama_kategori }}</td>
          <td>{{ $item->deskripsi ?? '-' }}</td>
          <td class="d-flex gap-2 align-items-center">
            <a href="{{ route('kategori.edit', $item->id) }}" class="text-black" title="Edit"><i class="bi bi-pencil-square"></i></a>
            @if($item->is_default)
              <span class="text-primary" title="Data default sistem — tidak dapat dihapus" data-bs-toggle="tooltip" data-bs-placement="top">
                <i class="bi bi-shield-lock-fill"></i>
              </span>
            @elseif($item->has_barangs)
              <span class="text-muted" title="Tidak dapat dihapus: kategori ini masih digunakan oleh data barang" data-bs-toggle="tooltip" data-bs-placement="top">
                <i class="bi bi-lock-fill"></i>
              </span>
            @else
              <button type="button" class="text-danger border-0 bg-transparent p-0"
                title="Hapus Kategori"
                data-bs-toggle="modal"
                data-bs-target="#deleteModal"
                data-action="{{ route('kategori.destroy', $item->id) }}"
                data-name="{{ $item->nama_kategori }}">
                <i class="bi bi-trash"></i>
              </button>
            @endif
          </td>
        </tr>
      @empty
        <tr><td colspan="5" class="text-center">Tidak ada kategori ditemukan.</td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="d-flex justify-content-end mt-3">{{ $categories->links('pagination::bootstrap-5') }}</div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Yakin ingin menghapus kategori <strong id="deleteName"></strong>? Tindakan ini tidak dapat dibatalkan.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <form id="deleteForm" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Hapus</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  const deleteModal = document.getElementById('deleteModal');
  deleteModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    document.getElementById('deleteName').textContent = button.getAttribute('data-name');
    document.getElementById('deleteForm').action = button.getAttribute('data-action');
  });
</script>
@endpush
