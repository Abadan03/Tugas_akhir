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
  <form action="{{ route('inventaris.update', $barang->id) }}"  method="POST" enctype="multipart/form-data" class="p-4 bg-white">
    @csrf
    @method('PUT')
     <input type="hidden" name="_method" value="PUT">
      <div class="mb-3">
        <label for="nama_barang" class="form-label">Nama Barang<span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="{{ $barang->nama_barang }}" required>
      </div>

      {{-- Mengambil barang_id --}}
      <input type="text" class="form-control visually-hidden" id="barang_id" name="barang_id" value="{{ $barang->id }}" required>
    
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

      {{-- @if ($barang->kodeQR) 
        <div class="mb-3">
          <label class="form-label">Kode QR</label>
          <input type="text" class="visually-hidden" id="display_kodeQR" name="display_kodeQR" value="{{ old('kodeQR', $barang->kodeQR ?? '') }}">
          <input type="text" class="visually-hidden" id="kodeQR" name="kodeQR" value="{{ old('kodeQR', $barang->kodeQR ?? '') }}">

          <div id="qr-code" class="my-3"></div>
        </div>

      @else
          <div class="mb-3">
            <label class="form-label">Generate Kode QR</label><br>
            <button type="button" class="btn btn-sm btn-outline-dark" id="generate-qr">Klik untuk Generate Kode QR</button>
            <input type="text" class="" name="kodeQR" id="kodeQR" value="">
          </div>


        <div id="qr-code" class="my-3"></div>
      @endif --}}

      <div class="mb-3">
        <label class="form-label">Generate Kode QR</label><br>
        <button type="button" class="btn btn-sm btn-outline-dark" id="generate-qr">Klik untuk Generate Kode QR</button>
        <input type="text" class="visually-hidden" name="kodeQR" id="kodeQR" value="{{ old('kodeQR', $barang->kodeQR ?? '') }}">
      </div>

      <div id="qr-code" class="my-3"></div>

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
    const namaSiswaContainer = document.getElementById('nama_siswa_container');
    const keteranganContainer = document.getElementById("keterangan_container");
    const suratContainer = document.getElementById("surat_container");

    const namaBarang = document.getElementById("nama_barang");
    let namaSiswa = document.getElementById("nama_siswa");
    const tipeElement = document.getElementById("tipe");
    const hargaElement = document.getElementById("harga");
    const barangIdElement = document.getElementById("barang_id");
    const kodeQRInput = document.getElementById("kodeQR");
    const qrContainer = document.getElementById("qr-code");
    const generateQRBtn = document.getElementById("generate-qr");

    function getStatusLabel(val) {
      switch (val) {
        case "0": return "Baru";
        case "1": return "Hilang";
        case "2": return "Rusak ringan";
        case "3": return "Rusak";
        case "4": return "Diperbarui";
        default: return "-";
      }
    }

    function getKategoriLabel(val) {
      return val === "1" ? "Dipinjam oleh siswa" : "Milik Sekolah";
    }

    function getTipeLabel(val) {
      return val === "1" ? "Barang berpindah" : "Barang tetap";
    }

    function generateQRContent() {
      const kategoriVal = kategoriElement.value;
      const statusVal = statusElement.value;
      const nama = kategoriVal === "1" ? (namaSiswa?.value || '-') : "-";
      const keteranganInput = document.getElementById("keterangan");

      console.log("Nama siswa:", nama); // debug
      return JSON.stringify({
        id: barangIdElement.value || '',
        nama_barang: namaBarang.value || '',
        kategori: getKategoriLabel(kategoriVal),
        nama_siswa: kategoriVal === "1" ? (namaSiswa?.value || '-') : "-",
        tipe: getTipeLabel(tipeElement.value),
        status: getStatusLabel(statusVal),
        harga_awal: hargaElement.value || '',
        keterangan: keteranganInput ? keteranganInput.value : '-'
      });
    }

    function renderQRCode(content) {
      qrContainer.innerHTML = ""; // clear existing QR
      new QRCode(qrContainer, {
        text: content,
        width: 256,
        height: 256,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
      });
    }

    function updateFields() {
      const kategoriVal = kategoriElement.value;
      const statusVal = statusElement.value;
      namaSiswa = document.getElementById("nama_siswa"); // refresh elemen

      namaSiswaContainer.style.display = kategoriVal === "1" ? "block" : "none";
      keteranganContainer.innerHTML = "";
      suratContainer.innerHTML = "";

      if (namaSiswa) {
        namaSiswa.addEventListener("input", () => {
          const content = generateQRContent();
          kodeQRInput.value = content;
          renderQRCode(content);
        });
      }

      if (statusVal !== "0" && statusVal !== "4") {
        keteranganContainer.innerHTML = `
          <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
            <textarea name="keterangan" id="keterangan" cols="30" rows="4" class="form-control" required></textarea>
          </div>
        `;
        suratContainer.innerHTML = `
          <div class="mb-3">
            <label for="surat" class="form-label">Jika dalam bentuk surat</label>
            <input type="file" id="surat" name="surat" class="form-control">
          </div>
        `;
      }
    }

    kategoriElement.addEventListener("change", updateFields);
    statusElement.addEventListener("change", updateFields);
    updateFields();

    // Tombol generate QR manual (jika ada)
    generateQRBtn?.addEventListener("click", function () {
      const kategoriVal = kategoriElement.value;

      // validasi nama_siswa jika kategori = 1
      if (kategoriVal === "1" && (!namaSiswa || namaSiswa.value.trim() === "")) {
        alert("Silakan isi Nama Siswa terlebih dahulu.");
        return;
      }

      const content = generateQRContent();
      kodeQRInput.value = content;
      renderQRCode(content);
    });

    // Saat form disubmit, QR harus diupdate dulu
    document.querySelector("form").addEventListener("submit", function () {
      const content = generateQRContent();
      kodeQRInput.value = content;
      renderQRCode(content); // Tambahkan ini juga untuk memastikan preview update
    });

    // Saat halaman load dan kodeQR sudah ada, render ulang preview-nya
    if (kodeQRInput?.value) {
      renderQRCode(kodeQRInput.value);
    }
  });
</script>
@endpush
