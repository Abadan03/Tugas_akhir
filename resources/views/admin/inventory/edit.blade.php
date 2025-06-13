@php
  $suratPath = count($barangRusaks) > 0 && $barangRusaks[0]->surat ? asset('storage/' . $barangRusaks[0]->surat) : '';
@endphp

@extends('layouts.app')

@section('content')

<div class="container-fluid d-flex justify-content-between align-items-center py-3 px-5 border-3 border-bottom rounded-3">
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
  <a href="{{ route('inventaris.index') }}">
    <i class="bi bi-arrow-left-square fs-3"></i>
  </a>
  <h4 class="mb-0">Edit barang</h4>
</div>

<div class="container-fluid">
  <form action="{{ route('inventaris.update', $barang->id) }}" method="POST" enctype="multipart/form-data" class="p-4 bg-white">
    @csrf
    @method('PUT')
      <div class="mb-3">
        <label for="nama_barang" class="form-label">Nama Barang<span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="{{ $barang->nama_barang }}" required>
      </div>
    
      <div class="mb-3">
        <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
        <select class="form-select" id="kategori" name="kategori" required>
          <option value="0" {{ $barang->kategori == '0' ? 'selected' : '' }}>Milik Sekolah</option>
          <option value="1" {{ $barang->kategori == '1' ? 'selected' : '' }}>Dipinjam oleh siswa</option>
        </select>
      </div>
      
      <div id="nama_siswa_container" @if ($barang->kategori != '1') style="display:none;" @endif>
        <div class="mb-3">
          <label for="nama_siswa" class="form-label">Nama Siswa <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" value="{{ old('nama_siswa', $barang->nama_siswa ?? '') }}">
        </div>
      </div>
    
      <div class="mb-3">
        <label for="tipe" class="form-label">Tipe <span class="text-danger">*</span></label>
        <select class="form-select" id="tipe" name="tipe" required>
          <option value="1" {{ $barang->tipe == 1 ? 'selected' : '' }}>Barang berpindah</option>
          <option value="0" {{ $barang->tipe == 0 ? 'selected' : '' }}>Barang tetap</option>
        </select>
      </div>
      
      <div class="mb-3">
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select" id="status" name="status" required>
          <option value="0" {{ $barang->status == '0' ? 'selected' : '' }}>Baru</option>
          <option value="1" {{ $barang->status == '1' ? 'selected' : '' }}>Hilang</option>
          <option value="2" {{ $barang->status == '2' ? 'selected' : '' }}>Rusak ringan</option>
          <option value="3" {{ $barang->status == '3' ? 'selected' : '' }}>Rusak</option>
          <option value="4" {{ $barang->status == '4' ? 'selected' : '' }}>Diperbarui</option>
        </select>
      </div>
      
      <div id="keterangan_container">
        {{-- Keterangan field will be added dynamically --}}
      </div>
      
      <div id="surat_container">
        {{-- Surat field will be added dynamically --}}
      </div>
      
      <div class="mb-3">
        <label for="harga" class="form-label">Harga Awal <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="harga_awal" id="harga" value="{{ $barang->harga_awal }}" required>
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
          <p class="fs-6 text-danger"><span>* </span>Admin tidak memasukkan bukti pembelian</p>
          <input type="file" class="form-control" id="bukti" name="bukti">
        @endif
      </div>
    
      <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('inventaris.index') }}">
          <button type="button" class="btn btn-danger">Batal</button>
        </a>
        <button type="submit" class="btn btn-success">Simpan</button>
      </div>
    
  </form>
</div>

@endsection

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const statusElement = document.getElementById("status");
    const kategoriElement = document.getElementById("kategori");
    const keteranganContainer = document.getElementById("keterangan_container");
    const suratContainer = document.getElementById("surat_container");
    const namaSiswaContainer = document.getElementById('nama_siswa_container');
    const savedNamaSiswa = namaSiswaContainer.querySelector('#nama_siswa');
    
    function updateFields() {
      const statusValue = statusElement.value;
      const kategoriValue = kategoriElement.value;
      
      keteranganContainer.innerHTML = "";
      suratContainer.innerHTML = "";
      
      if (kategoriValue === "1") {
        namaSiswaContainer.style.display = "block";
      } else {
        namaSiswaContainer.style.display = "none";
      }

      if (statusValue !== "0" && statusValue !== "4") {
        keteranganContainer.innerHTML = `
          <div class="mb-3" id="field-keterangan">
            <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
            <textarea name="keterangan" id="keterangan" cols="30" rows="4" class="form-control" required></textarea>
          </div>
        `;
        
        suratContainer.innerHTML = `
          <div class="mb-3 d-flex flex-column" id="field-surat">
            <label for="surat" class="form-label">Jika dalam bentuk surat</label>
            <input type="file" id="surat" name="surat">
          </div>
        `;
      }
    }

    // Initialize fields on page load
    updateFields();

    // Update fields when status or kategori changes
    statusElement.addEventListener("change", updateFields);
    kategoriElement.addEventListener("change", updateFields);
  });
</script>
@endpush
