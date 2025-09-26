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
    <a href="{{ route('pembayaran.index') }}">
      <i class="bi bi-arrow-left-square fs-3"></i>
    </a>
    <h4 class="mb-0">Detail Barang</h4>
  </div>

  <div class="row bg-white p-4">      
    {{-- Kolom Kiri --}}
    <div class="col-md-6 d-flex flex-column gap-3">
      <div>
        <h6 class="fw-semibold mb-1">Nama Barang :</h6>
        <p class="fw-light">{{ $pembayaran->nama_barang }}</p>
      </div>
      <div>
        <h6 class="fw-semibold mb-1">Kategori :</h6>
        <p class="fw-light">
          @if ($pembayaran->kategori == 1)
              Dipinjam oleh siswa
            @elseif ($pembayaran->kategori == 0)
              Milik Sekolah
            @endif
        </p>
      </div>
      @if ($pembayaran->kategori == 1)
        <div>
          <h6 class="fw-semibold mb-1">Nama Siswa :</h6>
          <p class="fw-light">{{ $pembayaran->nama_siswa }}</p>
        </div>
      @endif
      <div>
        <h6 class="fw-semibold mb-1">Tipe :</h6>
        <p class="fw-light">
          @if ($pembayaran->tipe == 0)
            Barang Tetap
          @elseif ($pembayaran->tipe == 1)
            Barang Berpindah
          @endif
        </p>
      </div>
      @isset($pembayaran)
        <div>
          <h6 class="fw-semibold mb-1">Biaya Perbaikan :</h6>
          <p class="fw-light">Rp. {{ number_format($pembayaran->biaya_perbaikan, 0, ',', '.') }}</p>
        </div>
      @endisset
      <div>
        <h6 class="fw-semibold mb-1">Status :</h6>
        <p class="fw-light">
          @if ($pembayaran->status == 0)
            Baru
          @elseif ($pembayaran->status == 1)
            Hilang
          @elseif ($pembayaran->status == 2)
            Rusak Ringan
          @elseif ($pembayaran->status == 3)
            Rusak
          @elseif ($pembayaran->status == 4)
            Diperbarui
          @endif
      </div>
      @if ($pembayaran->keterangan)
      <div>
        <h6 class="fw-semibold mb-1">Keterangan :</h6>
        <p class="fw-light">
          {{ $pembayaran->keterangan }}
      </div>
      @endif
      
      <div>
        <h6 class="fw-semibold mb-1">Generate Kode QR</h6>
        <p class="fw-light mb-2">Item-#{{ $pembayaran->kodeQR }}</p>
        <img src="{{ asset('path/to/qr-code.png') }}" alt="QR Code" width="120">
      </div>
    </div>

    {{-- Kolom Kanan --}}
    <div class="col-md-6 d-flex flex-column align-items-start">
      <div>
        <h6 class="fw-semibold mb-2">Bukti Pembelian :</h6>
        @isset($pembayaran->bukti)
          <img src="{{ asset('storage/' . $pembayaran->bukti) }}" alt="Bukti Pembelian" class="img-fluid mb-4" style="max-height: 250px;">
        @endisset

        @empty($pembayaran->bukti)
          <p> tidak ada bukti invoice </p>
        @endempty
      </div>
    </div>
    
  </div>
</div>
@endsection
