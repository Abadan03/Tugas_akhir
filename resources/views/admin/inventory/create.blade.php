@extends('layouts.app')

@section('content')
<div class="bg-mainbg">
  <div class="container-fluid d-flex justify-content-between align-items-center py-3 px-5 border-3 border-bottom rounded-3 bg-white">
    {{-- <div>
      <input type="text" placeholder="Cari nama barang, produk id, kategori" size="60">
    </div> --}}
    <div class="input-group">
      <span class="input-group-text bg-white border-0">
        <i class="bi bi-search text-muted"></i>
      </span>
      <input type="text" class="form-control border" style="max-width: 400px;" placeholder="Search nama barang, produk id, kategori">
    </div>
    
    <div>
      @if (Auth()->user())
          {{ Auth()->user()->name }}
      @endif
    </div>
  </div>
  @include('layouts.flash-message')

  <div class="d-flex align-items-center my-3 gap-2 container-fluid">
    {{-- <button class=""> --}}
      <a href="{{ route('inventaris.index') }}">
        <i class="bi bi-arrow-left-square fs-3"></i>
      </a>
    {{-- </button> --}}
    <h4 class="mb-0">Tambah barang</h4>
  </div>

  {{-- main --}}
  <div class="container-fluid bg-white">
    <form action="{{ route("inventaris.store") }}" class="p-4" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="mb-3">
        <label for="nama_barang" class="form-label">Nama Barang<span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
      </div>

      <div class="mb-3">
        <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
        <select class="form-select" id="kategori" name="kategori" required>
          <option value="" disabled selected>Pilih kategori</option>
          <option value="0">Milik Sekolah</option>
          <option value="1">Dipinjam oleh siswa</option>
        </select>
      </div>

      <div class="mb-3 d-none" id="field-nama-siswa">
        <label for="nama_siswa" class="form-label">Nama Siswa <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="nama_siswa" name="nama_siswa">
      </div>

      <div class="mb-3">
        <label for="tipe" class="form-label">Tipe <span class="text-danger">*</span></label>
        <select class="form-select" id="tipe" name="tipe" required>
          <option value="" disabled selected>Pilih tipe</option>
          <option value="1">Barang berpindah</option>
          <option value="0">Barang tetap</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select" id="status" name="status" required>
          <option value="" disabled selected>Pilih status barang</option>
          <option value="0">baru</option>
          {{-- <option value="Hilang">hilang</option>
          <option value="Rusak ringan">rusak ringan</option>
          <option value="Rusak">rusak</option> --}}
        </select>
      </div>

      <div class="mb-3">
        <label for="harga" class="form-label">Harga Awal <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="harga_awal" id="harga" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Generate Kode QR</label><br>
        <button type="button" class="btn btn-sm btn-outline-dark">Klik untuk Generate Kode QR</button>
        <input type="text" class="visually-hidden" name="kodeQR" id="kodeQR" value="12(2Gss98vT}YG!saSD">
      </div>

      <div class="mb-3">
        <label for="bukti" class="form-label">Bukti Pembelian :</label>
        <input type="file" class="form-control" id="bukti" name="bukti">
      </div>

      <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('inventaris.index') }}">
          <button type="button" class="btn btn-danger">Batal</button>
        </a>
        <button type="submit" class="btn btn-success">Simpan</button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const kategoriSelect = document.getElementById('kategori');
    const namaSiswaField = document.getElementById('field-nama-siswa');
    const namaSiswaInput = document.getElementById('nama_siswa');

    function toggleNamaSiswa() {
      if (kategoriSelect.value == 1) {
        namaSiswaField.classList.remove('d-none');
        namaSiswaInput.required = true;
      } else {
        namaSiswaField.classList.add('d-none');
        namaSiswaInput.required = false;
        namaSiswaInput.value = '';
      }
    }

    kategoriSelect.addEventListener('change', toggleNamaSiswa);
    toggleNamaSiswa(); // trigger saat load pertama kali
  });
</script>
@endpush