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

    {{-- {{ $barang->tipe }} --}}

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


  <form action="{{ route('peminjaman.update', $barang->id) }}" method="POST" enctype="multipart/form-data" class="p-4 bg-white">
    @csrf
    @method('PUT')
    {{-- @if ($barang->status == 0) --}}
      {{-- <h1>assd</h1> --}}
      <div class="mb-3">
        <label for="nama_barang" class="form-label">Pilih Barang<span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="nama_barang_display" name="nama_barang_display" value="{{ $barang->nama_barang }}" required readonly>
        <input type="hidden" class="form-control" id="nama_barang" name="nama_barang" value="{{ $barang->nama_barang }}">
      </div>
    
      <div class="mb-3">
        <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
        <select class="form-select" id="kategori_id" name="kategori_id" required>
          @foreach($categories as $category)
            <option value="{{ $category->id }}" data-trigger="{{ $category->defaultTrigger ? 'true' : 'false' }}" 
              {{ $barang->kategori_id == $category->id ? 'selected' : '' }}>
              {{ $category->nama_kategori }}
            </option>
          @endforeach
        </select>
        @error('kategori_id')
            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
        @enderror
        
      </div>

      <div id="field-peminjam" class="mb-3" style="display:none;">
        <label for="peminjam" class="form-label">Peminjam<span class="text-danger">*</span></label>
        <input type="text" name="peminjam" id="peminjam" value="{{ old('peminjam', $barang->peminjam) }}" class="form-control">
        @error('peminjam')
            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
        @enderror
      </div>


      <div class="mb-3">
        <label for="tipe_id" class="form-label">Tipe <span class="text-danger">*</span></label>
        <select class="form-select" id="tipe_id" name="tipe_id" required>
          {{-- <option value="1" {{ $barang->tipe == 1 ? 'selected' : '' }}>Barang berpindah</option>
          <option value="0" {{ $barang->tipe == 0 ? 'selected' : '' }}>Barang tetap</option> --}}
          @foreach($types as $type)
            <option value="{{ $type->id }}" 
              {{ $barang->tipe_id == $type->id ? 'selected' : '' }}>
              {{ $type->nama_tipe }}
            </option>
          @endforeach
        </select>
        @error('tipe_id')
            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
        @enderror
      </div>
    
      <div class="mb-3">
        <label for="status_id" class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select" id="status_id" name="status_id" required>
          {{-- <option value="0" {{ $barang->status == 0 ? 'selected' : '' }}>Baru</option>
          <option value="1" {{ $barang->status == 1 ? 'selected' : '' }}>Hilang</option>
          <option value="2" {{ $barang->status == 2 ? 'selected' : '' }}>Rusak ringan</option>
          <option value="3" {{ $barang->status == 3 ? 'selected' : '' }}>Rusak</option>
          <option value="4" {{ $barang->status == 4 ? 'selected' : '' }}>Diperbaiki</option> --}}
          @foreach ($statuses as $status)
            <option 
              value="{{ $status->id }}" 
              {{ $barang->status_id == $status->id ? 'selected' : '' }}>
              {{ $status->nama_status }}
            </option>
          @endforeach
        </select>
        @error('status_id')
            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
        @enderror
      </div>

      {{-- This is for pinjaman id to throw in barangRusaks table --}}
      <input type="hidden" id="pinjaman_id" name="pinjaman_id" value="{{ $pinjaman->id }}">

      {{-- {{ $pinjaman->id }} --}}

      <div id="keterangan_container">
        
      </div>
      @error('keterangan')
          <div class="text-danger mt-1"><small>{{ $message }}</small></div>
      @enderror
    
      
      <div id="surat_container">

      </div>
      @error('surat')
          <div class="text-danger mt-1"><small>{{ $message }}</small></div>
      @enderror
    
      <div class="mb-3">
        <label for="harga" class="form-label">Harga Awal <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="harga_display" id="harga_display" 
        value="Rp. {{ number_format($barang->harga_awal, 0, ',', '.') }}" required readOnly>
        <input type="hidden" class="form-control" name="harga_awal" id="harga_awal" value="{{ $barang->harga_awal }}">
      </div>
    
      {{-- <div class="mb-3">
        <label class="form-label">Kode QR</label>
        <input type="text" class="form-control" name="kodeQR" value="{{ $barang->kodeQR }}" readonly>
      </div> --}}
    @if ($barang->kodeQR)
      <div>
        <h6 class="fw-semibold mb-1">Generate Kode QR</h6>
        {{-- <p class="fw-light mb-2">Item-#{{ $barang->kodeQR }}</p> --}}
        <input type="text" class="visually-hidden" id="display_kodeQR" name="display_kodeQR" value="{{ old('kodeQR', $barang->kodeQR ?? '') }}">
        <div id="qr-code" class="my-3"></div>
        <input type="text" class="visually-hidden" name="kodeQR" id="kodeQR" value="{{ old('kodeQR', $barang->kodeQR ?? '') }}">
        {{-- <img src="{{ asset('path/to/qr-code.png') }}" alt="QR Code" width="120"> --}}
      </div>
     @else
     <div class="mb-3">
       <label class="form-label">Generate Kode QR</label><br>
       <button type="button" class="btn btn-sm btn-outline-dark" id="generate-qr">Klik untuk Generate Kode QR</button>
       <input type="text" class="visually-hidden" name="kodeQR" id="kodeQR" value="{{ old('kodeQR', $barang->kodeQR ?? '') }}">
      </div>
    @endif

      <div id="qr-code" class="my-3"></div>
    
      <div class="mb-3 ">
        <label for="bukti" class="form-label">Bukti Pembelian :</label>
          @if($barang->bukti)
            <p><a href="{{ asset('storage/' . $barang->bukti) }}" target="_blank">Lihat Bukti</a></p>
          @else
          <p class="fs-6 text-p-grey">
            <small>
              (Admin belum memasukkan bukti pembelian)
            </small>
          </p>
          <input type="file" class="form-control" id="bukti" name="bukti">
        @endif
        @error('bukti')
            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
        @enderror
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
    const kategoriSelect = document.getElementById('kategori_id');
    const fieldPeminjam = document.getElementById('field-peminjam');
    const namaSiswaInput = document.getElementById('peminjam');
    const namaBarang = document.getElementById('nama_barang');
    const tipeElement = document.getElementById('tipe_id');
    const statusElement = document.getElementById('status_id');
    const hargaElement = document.getElementById('harga_awal');
    const kodeQRInput = document.getElementById("kodeQR");
    const qrContainer = document.getElementById("qr-code");
    const generateQRBtn = document.getElementById("generate-qr");
    const barangId = {{ $barang->id }};

    const keteranganContainer = document.getElementById("keterangan_container");
    const suratContainer = document.getElementById("surat_container");

    function toggleNamaSiswa() {
      const selectedOption = kategoriSelect.options[kategoriSelect.selectedIndex];
      const trigger = selectedOption.getAttribute('data-trigger');
      
      if (trigger === 'false') {
        fieldPeminjam.style.display = "block";
      } else {
        fieldPeminjam.style.display = "none";
        document.getElementById('peminjam').value = "";
      }
    }

    function toggleSuratKeterangan() {
      const status = statusElement.value;
      const show = status !== "1" && status !== "5";
      keteranganContainer.innerHTML = show ? `
        <div class="mb-3">
          <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
          <textarea name="keterangan" id="keterangan" rows="4" class="form-control"></textarea>
        </div>` : '';
      suratContainer.innerHTML = show ? `
        <div class="mb-3">
          <label for="surat" class="form-label">Keterangan jika dalam bentuk surat</label>
          <input type="file" class="form-control" id="surat" name="surat">
        </div>` : '';
    }

    const getLabel = {
      status: val => ({
        "1": "Baru",
        "2": "Hilang",
        "3": "Rusak Ringan",
        "4": "Rusak Berat",
        "5": "Diperbaiki"
      }[val] || "-"),
      kategori: val => val === "1" ? "Dipinjam oleh siswa" : "Milik Sekolah",
      tipe: val => val === "1" ? "Barang berpindah" : "Barang tetap"
    };

    // function generateQRContent() {
    //   const kategoriVal = kategoriSelect.value;
    //   const namaSiswa = kategoriVal === "2" ? (namaSiswaInput?.value || '-') : "-";
    //   return JSON.stringify({
    //     id: barangId,
    //   });
    // }

    // Jika data qr ada
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
    
    function generateQRContent() {
      const kategoriVal = kategoriSelect.value;

      return JSON.stringify({
        id: barangId,
      });
    }

    function renderQRCode(content) {
      qrContainer.innerHTML = "";
      const wrapper = document.createElement("div");
      qrContainer.appendChild(wrapper);
      new QRCode(wrapper, {
        text: content,
        width: 256,
        height: 256,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
      });
    }

    function autoGenerateQR() {
      const content = generateQRContent();
      kodeQRInput.value = content;
      renderQRCode(content);
    }

    // Event Listeners
    kategoriSelect.addEventListener('change', () => {
      toggleNamaSiswa();
      autoGenerateQR();
    });

    namaSiswaInput?.addEventListener("input", autoGenerateQR);
    tipeElement.addEventListener("change", autoGenerateQR);
    statusElement.addEventListener("change", () => {
      toggleSuratKeterangan();
      autoGenerateQR();
    });

    generateQRBtn?.addEventListener("click", function () {
      if (kategoriSelect.value === "2" && (!namaSiswaInput || namaSiswaInput.value.trim() === "")) {
        alert("Silakan isi Nama Siswa terlebih dahulu.");
        return;
      }
      autoGenerateQR();
    });

    // Submit: langsung aja tanpa timeout
    document.querySelector("form").addEventListener("submit", function () {
      autoGenerateQR();
    });

    // Init awal
    toggleNamaSiswa();
    toggleSuratKeterangan();
    autoGenerateQR();
  });
</script>
@endpush
