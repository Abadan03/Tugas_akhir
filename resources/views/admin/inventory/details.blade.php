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

<div class="container-fluid my-4">
  <div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('inventaris.index') }}">
      <i class="bi bi-arrow-left-square fs-3"></i>
    </a>
    <h4 class="mb-0">Detail Barang</h4>
  </div>

  <div class="row bg-white p-4">      
    {{-- Kolom Kiri --}}
    <div class="col-md-6 d-flex flex-column gap-3">
      <div>
        <h6 class="fw-semibold mb-1">Nama Barang :</h6>
        <p class="fw-light">{{ $barang->nama_barang }}</p>
      </div>
      <div>
        <h6 class="fw-semibold mb-1">Kategori :</h6>
        <p class="fw-light">
          @if ($barang->kategori === "dipinjam")
              Dipinjam oleh siswa
            @elseif ($barang->kategori === "milik")
              Milik Sekolah
            @endif
        </p>
      </div>
      @if ($barang->kategori == "dipinjam")
        <div>
          <h6 class="fw-semibold mb-1">Nama Siswa :</h6>
          <p class="fw-light">{{ $barang->nama_siswa }}</p>
        </div>
      @endif
      <div>
        <h6 class="fw-semibold mb-1">Tipe :</h6>
        <p class="fw-light">{{ $barang->tipe }}</p>
      </div>
      <div>
        <h6 class="fw-semibold mb-1">Harga Awal :</h6>
        <p class="fw-light">Rp. {{ number_format($barang->harga_awal, 0, ',', '.') }}</p>
      </div>
      <div>
        <h6 class="fw-semibold mb-1">Status :</h6>
        <p class="fw-light">{{ $barang->status }}</p>
      </div>
      <div>
        <h6 class="fw-semibold mb-1">Generate Kode QR</h6>
        <p class="fw-light mb-2">Item-#{{ $barang->kodeQR }}</p>
        <img src="{{ asset('path/to/qr-code.png') }}" alt="QR Code" width="120">
      </div>
    </div>

    {{-- Kolom Kanan --}}
    <div class="col-md-6 d-flex flex-column align-items-start">
      <h6 class="fw-semibold mb-2">Bukti Pembelian :</h6>
      <img src="{{ asset('storage/' . $barang->bukti) }}" alt="Bukti Pembelian" class="img-fluid mb-4" style="max-height: 250px;">
      
      {{-- Tombol --}}
      <div class="d-flex justify-content-end w-100 gap-2 mt-auto">
        <a href="{{ route('inventaris.index') }}" class="btn btn-danger">Batal</a>
        {{-- <button class="btn btn-success">Simpan</button> --}}
      </div>
    </div>
  </div>
</div>
@endsection
