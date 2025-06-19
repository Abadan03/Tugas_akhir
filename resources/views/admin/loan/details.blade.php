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
    <a href="{{ route('peminjaman.index') }}">
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
          @if ($barang->kategori == 1)
              Dipinjam oleh siswa
            @elseif ($barang->kategori == 0)
              Milik Sekolah
            @endif
        </p>
      </div>
      @if ($barang->kategori == 1)
        <div>
          <h6 class="fw-semibold mb-1">Nama Siswa :</h6>
          <p class="fw-light">{{ $barang->nama_siswa }}</p>
        </div>
      @endif
      <div>
        <h6 class="fw-semibold mb-1">Tipe :</h6>
        <p class="fw-light">
          @if ($barang->tipe == 0)
            Barang Tetap
          @elseif ($barang->tipe == 1)
            Barang Berpindah
          @endif
        </p>
      </div>
      <div>
        <h6 class="fw-semibold mb-1">Harga Awal :</h6>
        <p class="fw-light">Rp. {{ number_format($barang->harga_awal, 0, ',', '.') }}</p>
      </div>
      <div>
        <h6 class="fw-semibold mb-1">Status :</h6>
        <p class="fw-light">
          @if ($barang->status == 0)
            Baru
          @elseif ($barang->status == 1)
            Hilang
          @elseif ($barang->status == 2)
            Rusak Ringan
          @elseif ($barang->status == 3)
            Rusak
          @elseif ($barang->status == 4)
            Diperbarui
          @endif
      </div>
      @isset($pembayaran)
        <div>
          <h6 class="fw-semibold mb-1">Biaya Perbaikan :</h6>
          <p class="fw-light">Rp. {{ number_format($pembayaran->biaya_perbaikan, 0, ',', '.') }}</p>
        </div>
      @endisset
      @if ($barang->keterangan)
      <div>
        <h6 class="fw-semibold mb-1">Keterangan :</h6>
        <p class="fw-light">
          {{ $barang->keterangan }}
      </div>
      @endif
      
      <div>
        <h6 class="fw-semibold mb-1">Generate Kode QR</h6>
        {{-- <p class="fw-light mb-2">Item-#{{ $barang->kodeQR }}</p> --}}
        <input type="text" class="visually-hidden" id="display_kodeQR" name="display_kodeQR" value="{{ old('kodeQR', $barang->kodeQR ?? '') }}">
        <div id="qr-code" class="my-3"></div>
        {{-- <img src="{{ asset('path/to/qr-code.png') }}" alt="QR Code" width="120"> --}}
      </div>
      @if ($barang->kodeQR)
        <div class="mt-2">
          <p class="small text-muted">Preview Konten QR:</p>
          <pre class="bg-light p-2 rounded small">{{ $barang->kodeQR }}</pre>
        </div>
      @endif
    </div>

    {{-- Kolom Kanan --}}
    <div class="col-md-6 d-flex flex-column align-items-start">
      <div>
        <h6 class="fw-semibold mb-2">Bukti Pembelian :</h6>
        @isset($barang->bukti)
          <img src="{{ asset('storage/' . $barang->bukti) }}" alt="Bukti Pembelian" class="img-fluid mb-4" style="max-height: 250px;">
        @endisset

        @empty($barang->bukti)
          <p> tidak ada bukti invoice </p>
        @endempty
      </div>
    </div>
    
  </div>
</div>

@if($history->count())
  <div class="mt-4">
    <h5 class="fw-bold mb-3">Histori Status Barang</h5>
    <ul class="list-group">
      @foreach($history as $log)
        <li class="list-group-item">
          <div class="fw-semibold">
            {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y H:i') }}
          </div>
          <div>Status: 
            @php
              switch($log->status) {
                case 0: $label = 'Baru'; break;
                case 1: $label = 'Hilang'; break;
                case 2: $label = 'Rusak Ringan'; break;
                case 3: $label = 'Rusak Berat'; break;
                case 4: $label = 'Diperbarui'; break;
                default: $label = 'Tidak diketahui';
              }
            @endphp
            <span class="badge bg-secondary">{{ $label }}</span>
          </div>
          <div>Keterangan: {{ $log->keterangan }}</div>
          <div>Bukti transfer: {{ $log->bukti_transfer }}</div>
        </li>
      @endforeach
    </ul>
  </div>
@endif
@endsection

@push('scripts')

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const qrValue = document.getElementById("display_kodeQR")?.value;

    if (qrValue) {
      const qrContainer = document.getElementById("qr-code");

      new QRCode(qrContainer, {
        text: qrValue,
        width: 256,
        height: 256,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
      });
    }
  });
</script>
@endpush