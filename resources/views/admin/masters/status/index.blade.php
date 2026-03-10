@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex justify-content-between align-items-center py-3 px-5 border-3 border-bottom rounded-3">
  <div class="input-group">
    <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
    <input type="text" id="search" class="form-control border" style="max-width: 400px;" placeholder="Search nama status">
  </div>
  <div>{{ Auth()->user()->name ?? '' }}</div>
</div>

@include('layouts.flash-message')

<div class="py-4 px-3 d-flex justify-content-between align-items-center">
  <div>
    <h2 class="fw-bold mb-0">Master Status</h2>
    <p class="fw-light text-muted mb-0">Kelola status kondisi barang</p>
  </div>
  <div>
    <a href="{{ route('status.create') }}" class="btn btn-skyblue">Tambah Status</a>
    <a href="{{ route('inventaris.index') }}" class="btn btn-secondary">Kembali</a>
  </div>
</div>

<div class="container-fluid mt-3">
  <table id="tableStatus" class="table table-striped">
    <thead>
      <tr>
        <th>No</th>
        <th>Kode</th>
        <th>Nama Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($statuses as $index => $item)
      <tr>
        <td>{{ $statuses->firstItem() + $index }}</td>
        <td><span class="badge bg-secondary">{{ $item->kode ?? '-' }}</span></td>
        <td>{{ $item->nama_status }}</td>
        <td class="d-flex gap-2 align-items-center">
          <a href="{{ route('status.edit', $item->id) }}" class="text-black" title="Edit"><i class="bi bi-pencil-square"></i></a>
          @if($item->is_default)
            <span class="text-primary" title="Data default sistem — tidak dapat dihapus" data-bs-toggle="tooltip" data-bs-placement="top">
              <i class="bi bi-shield-lock-fill"></i>
            </span>
          @elseif($item->has_barangs)
            <span class="text-muted" title="Tidak dapat dihapus: status ini masih digunakan oleh data barang" data-bs-toggle="tooltip" data-bs-placement="top">
              <i class="bi bi-lock-fill"></i>
            </span>
          @else
            <button type="button" class="text-danger border-0 bg-transparent p-0"
              title="Hapus Status"
              data-bs-toggle="modal"
              data-bs-target="#deleteModal"
              data-action="{{ route('status.destroy', $item->id) }}"
              data-name="{{ $item->nama_status }}">
              <i class="bi bi-trash"></i>
            </button>
          @endif
        </td>
      </tr>
      @empty
        <tr><td colspan="4" class="text-center">Belum ada status.</td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="d-flex justify-content-end mt-3">{{ $statuses->links('pagination::bootstrap-5') }}</div>
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
        Yakin ingin menghapus status <strong id="deleteName"></strong>? Tindakan ini tidak dapat dibatalkan.
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
