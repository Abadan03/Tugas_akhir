@extends('layouts.app')

@section('content')

<div class="container-fluid d-flex justify-content-between align-items-center py-3 px-5 border-3 border-bottom rounded-3 ">
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


<div class="container-fluid my-4 ">
  <div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('peminjaman.index') }}">
      <i class="bi bi-arrow-left-square fs-3"></i>
    </a>
    <h4 class="mb-0">Edit Status Barang</h4>
  </div>

  <form action="{{ route('peminjaman.update', $barang->id) }}" method="POST" enctype="multipart/form-data" class="p-4 bg-white">
    @csrf
    @method('PUT')
    <div class="mb-3">
      <label for="nama_barang" class="form-label">Nama Barang<span class="text-danger">*</span></label>
      <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="{{ $barang->nama_barang }}" required disabled>
    </div>
  
    <div class="mb-3">
      <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
      <select class="form-select" id="kategori" name="kategori" required disabled>
        <option value="milik" {{ $barang->kategori == 'milik' ? 'selected' : '' }}>Milik Sekolah</option>
        <option value="dipinjam" {{ $barang->kategori == 'dipinjam' ? 'selected' : '' }}>Dipinjam oleh siswa</option>
      </select>
    </div>

    <div class="mb-3 d-none" id="field-nama-siswa">
      <label for="nama_siswa" class="form-label">Nama Siswa <span class="text-danger">*</span></label>
      <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" value="{{ $barang->nama_siswa }}">
    </div>
  
    <div class="mb-3">
      <label for="tipe" class="form-label">Tipe <span class="text-danger">*</span></label>
      <select class="form-select" id="tipe" name="tipe" required disabled>
        <option value="barang berpindah" {{ $barang->tipe == 'barang berpindah' ? 'selected' : '' }}>Barang berpindah</option>
        <option value="barang tetap" {{ $barang->tipe == 'barang tetap' ? 'selected' : '' }}>Barang tetap</option>
      </select>
    </div>
  
    <div class="mb-3">
      <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
      <select class="form-select" id="status" name="status" required>
        <option value="Baru" {{ $barang->status == 'Baru' ? 'selected' : '' }}>Baru</option>
        <option value="Hilang" {{ $barang->status == 'Hilang' ? 'selected' : '' }}>Hilang</option>
        <option value="Rusak ringan" {{ $barang->status == 'Rusak ringan' ? 'selected' : '' }}>Rusak ringan</option>
        <option value="Rusak" {{ $barang->status == 'Rusak' ? 'selected' : '' }}>Rusak</option>
      </select>
    </div>

    <div class="mb-3 d-none" id="field-keterangan">
      <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
      <textarea name="keterangan" id="keterangan" cols="30" rows="4" class="form-control"></textarea>
      {{-- <input type="textarea" class="form-control" id="keterangan" name="keterangan"> --}}
    </div>
  
    <div class="mb-3 d-none" id="field-surat">
      <label for="surat" class="form-label">Keterangan jika dalam bentuk surat</label>
      <input type="file" class="form-control" id="surat" name="surat">
    </div>
  
    <div class="mb-3">
      <label for="harga" class="form-label">Harga Awal <span class="text-danger">*</span></label>
      <input type="number" class="form-control" name="harga_awal" id="harga" value="{{ $barang->harga_awal }}" required disabled>
    </div>
  
    <div class="mb-3">
      <label class="form-label">Kode QR</label>
      <input type="text" class="form-control" name="kodeQR" value="{{ $barang->kodeQR }}" readonly>
    </div>
  
    <div class="mb-3">
      <label for="bukti" class="form-label">Bukti Pembelian :</label>
      @if($barang->bukti)
        <p><a href="{{ asset('storage/' . $barang->bukti) }}" target="_blank">Lihat Bukti</a></p>
      @else
        <p class="fs-6 text-danger"><span>* </span>Admin belum memasukkan bukti pembelian</p>
        <input type="file" class="form-control" id="bukti" name="bukti">
      @endif
    </div>
  
    <div class="d-flex justify-content-end gap-2 mt-4">
      <a href="{{ route('peminjaman.index') }}">
        <button type="button" class="btn btn-danger">Batal</button>
      </a>
      <button type="submit" class="btn btn-success">Simpan</button>
    </div>
  </form>
</div>

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const statusSelect = document.getElementById('status');
    const keteranganField = document.getElementById('field-keterangan');
    const keteranganInput = document.getElementById('keterangan');

    const suratField = document.getElementById('field-surat');
    const suratInput = document.getElementById('surat');

    function toggleStatus() {
      if (statusSelect.value !== 'Baru') {
        keteranganField.classList.remove('d-none');
        keteranganInput.required = true;
        suratField.classList.remove('d-none');
      }
       else {
        keteranganField.classList.add('d-none');
        keteranganInput.required = false;
        keteranganInput.value = '';
      }

      // if statusSelect.value !== 'Baru' 
    }

    statusSelect.addEventListener('change', toggleStatus);
    toggleStatus();
  });
</script>
@endpush