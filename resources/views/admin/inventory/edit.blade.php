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
      <div class="mb-3">
        <label for="items_id" class="form-label">Nama Barang<span class="text-danger">*</span></label>
        {{-- <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="{{ $barang->nama_barang }}" required> --}}
        <select class="form-select" id="items_id" name="items_id">
        @foreach ($items as $item)
            <option value="{{ $item->id }}" {{ $barang->items_id == $item->id ? 'selected' : '' }}>{{ $item->nama_barang }}</option>
        @endforeach
        </select>
      </div>

      {{-- Mengambil barang_id --}}
      <input type="text" class="form-control visually-hidden" id="barang_id" name="barang_id" value="{{ $barang->id }}" required>
    
      <div class="mb-3">
        <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
        <select class="form-select" id="kategori_id" name="kategori_id" required>
          {{-- <option value="0" {{ $barang->kategori == '0' ? 'selected' : '' }}>Milik Sekolah</option>
          <option value="1" {{ $barang->kategori == '1' ? 'selected' : '' }}>Dipinjam oleh siswa</option> --}}
          @foreach($categories as $category)
            <option value="{{ $category->id }}" data-trigger="{{ strtolower($category->defaultTrigger) }}" 
              {{ $barang->kategori_id == $category->id ? 'selected' : '' }}>
              {{ $category->nama_kategori }}
            </option>
          @endforeach
        </select>
      </div>
      
      {{-- <div id="peminjam_container" @if ($barang->kategori_id != '1') style="display:none;" @endif>
        <div class="mb-3">
          <label for="peminjam" class="form-label">Peminjam <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="peminjam" name="peminjam" value="{{ old('peminjam', $barang->peminjam ?? '') }}">
        </div>
      </div> --}}

       <div class="mb-3 d-none" id="field-peminjam">
        <label for="peminjam" class="form-label">Peminjam <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="peminjam" name="peminjam" value="{{ old('peminjam', $barang->peminjam ?? '') }}">
      </div>
    
      <div class="mb-3">
        <label for="tipe_id" class="form-label">Tipe <span class="text-danger">*</span></label>
        <select class="form-select" id="tipe_id" name="tipe_id" required>
          {{-- <option value="1" {{ $barang->tipe->id == 1 ? 'selected' : '' }}>Barang berpindah</option>
          <option value="0" {{ $barang->tipe->id == 0 ? 'selected' : '' }}>Barang tetap</option> --}}
          @foreach($types as $type)
            <option value="{{ $type->id }}" 
              {{ $barang->tipe_id == $type->id ? 'selected' : '' }}>
              {{ $type->nama_tipe }}
            </option>
          @endforeach
        </select>
      </div>
      
      <div class="mb-3">
        <label for="status_id" class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select" id="status_id" name="status_id" required>
          {{-- <option value="0" {{ $barang->status->id == '1' ? 'selected' : '' }}>Baru</option>
          <option value="1" {{ $barang->status->id == '1' ? 'selected' : '' }}>Hilang</option>
          <option value="2" {{ $barang->status->id == '2' ? 'selected' : '' }}>Rusak ringan</option>
          <option value="3" {{ $barang->status->id == '3' ? 'selected' : '' }}>Rusak</option>
          <option value="4" {{ $barang->status->id == '4' ? 'selected' : '' }}>Diperbarui</option> --}}
          @foreach ($statuses as $status)
            <option 
              value="{{ $status->id }}" 
              {{ $barang->status_id == $status->id ? 'selected' : '' }}>
              {{ $status->nama_status }}
            </option>
          @endforeach
        </select>
      </div>
      
      {{-- <div id="keterangan_container">
        Keterangan field will be added dynamically
      </div>
      
      <div id="surat_container">
        Surat field will be added dynamically
      </div> --}}

      <div class="mb-3 d-none" id="field-keterangan">
        <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
        <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Tuliskan keterangan tambahan..."></textarea>
        @error('keterangan')
            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
        @enderror
      </div>
      <div class="mb-3 d-none" id="field-surat">
        <label for="surat" class="form-label">Surat (Opsional)</label>
        <input type="file" class="form-control" id="surat" name="surat">
      </div>
      
      <div class="mb-3">
        <label for="harga" class="form-label">Harga Awal <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="harga_awal" id="harga" value="{{ $barang->harga_awal }}" required>
      </div>

      @if ($barang->kodeQR)
        <div>
          <h6 class="fw-semibold mb-1">Kode QR</h6>
          <input type="text" class="visually-hidden" id="kodeQR" name="kodeQR" value="{{ old('kodeQR', $barang->kodeQR ?? '') }}">
          <div id="qr-code" class="my-3"></div>
        </div>
      @else
        <div class="mb-3">
          <label class="form-label">Generate Kode QR</label><br>
          <button type="button" class="btn btn-sm btn-outline-dark" id="generate-qr">Klik untuk Generate Kode QR</button>
          <input type="text" class="visually-hidden" name="kodeQR" id="kodeQR" value="{{ old('kodeQR', $barang->kodeQR ?? '') }}">
        </div>
        <div id="qr-code" class="my-3"></div>
      @endif

      <div class="mb-3">
        <label for="bukti" class="form-label">Bukti Pembelian :</label>
        @if($barang->bukti)
          <p><a href="{{ asset('storage/' . $barang->bukti) }}" target="_blank">Lihat Bukti</a></p>
        @else
          <p class="fs-6 text-danger">Admin belum memasukkan bukti pembelian</p>
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
  const initialKeterangan = @json(old('keterangan', $barangRusaks[0]->keterangan ?? ''));
  const suratPath = @json($suratPath);
</script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const kategoriSelect = document.getElementById('kategori_id');
    const peminjamField = document.getElementById('field-peminjam');
    const peminjamInput = document.getElementById('peminjam');
    const qrContainer = document.getElementById('qr-code');
    const generateBtn = document.getElementById('generate-qr');
    const qrInput = document.getElementById('kodeQR');
    const statusSelect = document.getElementById('status_id');
  // const keteranganContainer = document.getElementById('keterangan_container');
  // const suratContainer = document.getElementById('surat_container');

    /**
     * Fungsi untuk menampilkan / menyembunyikan field trigger siswa
     * secara dinamis berdasarkan kategori yang dipilih
     */
    function togglePeminjaman() {
      const selectedOption = kategoriSelect.options[kategoriSelect.selectedIndex];
      const trigger = selectedOption.dataset.trigger;
      console.log(trigger, "ini trigger");
      // if (kategoriSelect.value == 2) {
      if (trigger == 0) {
        peminjamField.classList.remove('d-none');
        // peminjamInput.required = true;
      } else {
        peminjamField.classList.add('d-none');
        // peminjamInput.required = false;
        peminjamInput.value = '';
      }
    }

    // / Fungsi untuk menampilkan/menyembunyikan field berdasarkan status
    const suratField = document.getElementById('field-surat');
    const suratInput = document.getElementById('surat');
    const ketField = document.getElementById('field-keterangan');
    const ketInput = document.getElementById('keterangan');

    function toggleSuratDanKeterangan() {
      const selectedStatus = parseInt(statusSelect.value);
      if ([2, 3, 4].includes(selectedStatus)) { // Hilang, Rusak Ringan, Rusak Berat
        suratField.classList.remove('d-none');
        ketField.classList.remove('d-none');
        // suratInput.required = true;
        ketInput.required = true;
      } else {
        suratField.classList.add('d-none');
        ketField.classList.add('d-none');
        suratInput.required = false;
        ketInput.required = false;
        suratInput.value = '';
        ketInput.value = '';
      }
    }

    const qrValue = document.getElementById("kodeQR")?.value;

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

    /**
     * Fungsi untuk generate kode QR dinamis
     */
    function generateQRCode() {
      // Jika sudah ada QR sebelumnya, hapus dulu biar tidak dobel
      qrContainer.innerHTML = '';

      // Ambil data dinamis dari beberapa input
      const itemNama = document.querySelector('#items_id option:checked')?.textContent?.trim() || '';
      const kategoriNama = kategoriSelect.options[kategoriSelect.selectedIndex]?.textContent?.trim() || '';
      const tipeNama = document.querySelector('#tipe_id option:checked')?.textContent?.trim() || '';
      const statusNama = document.querySelector('#status_id option:checked')?.textContent?.trim() || '';
      const hargaAwal = document.getElementById('harga').value || '';

      const qrData = {
        nama_barang: itemNama,
        kategori: kategoriNama,
        tipe: tipeNama,
        status: statusNama,
        harga_awal: hargaAwal,
      };

      // Convert ke JSON biar mudah dibaca ketika scan QR
      const qrText = JSON.stringify(qrData, null, 2);

      // Generate QR dengan library qrcode.js
      new QRCode(qrContainer, {
        text: qrText,
        width: 128,
        height: 128
      });

      // Simpan ke input hidden supaya bisa dikirim ke database
      qrInput.value = qrText;
    }

    // Jalankan fungsi saat halaman pertama kali dimuat
    togglePeminjaman();
    toggleSuratDanKeterangan();


    // Event listener
    kategoriSelect.addEventListener('change', togglePeminjaman);
    statusSelect.addEventListener('change', toggleSuratDanKeterangan);


    // Tombol generate QR diklik
    // generateBtn.addEventListener('click', generateQRCode);
    if (generateBtn) {
      generateBtn.addEventListener('click', generateQRCode);
    }

  });
</script>
@endpush
