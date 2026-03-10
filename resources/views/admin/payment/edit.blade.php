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
    <a href="{{ route('pembayaran.index') }}">
      <i class="bi bi-arrow-left-square fs-3"></i>
    </a>
    <h4 class="mb-0">Pembayaran Barang</h4>
  </div>
</div>

<form action="{{ route('pembayaran.update', $barangRusak->barang->id) }}" method="POST" enctype="multipart/form-data" class="p-4 bg-white" id="submit-form">
  @csrf
  @method('PUT')
  {{-- @if ($barang->status == 0) --}}
    {{-- <h1>assd</h1> --}}
    <div class="mb-3">
      <label for="nama_barang" class="form-label">Nama Barang<span class="text-danger">*</span></label>
      {{-- <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="{{ $items->nama_barang }}" required disabled> --}}
      <input type="hidden" class="form-control" id="nama_barang" name="nama_barang" value="{{ $items->nama_barang }}">
      <select class="form-select" id="items_id" name="items_id">
        @foreach ($itemsMaster as $item)
            <option value="{{ $item->id }}">{{ $item->nama_barang }}</option>
        @endforeach
      </select>
    </div>

    {{-- {{ $items }} --}}
  
    <div class="mb-3">
      <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
      <select class="form-select" id="kategori_id" name="kategori_id" required disabled>
        {{-- <option value="0" {{ $items->barang->kategori == 0 ? 'selected' : '' }}>Milik Sekolah</option>
        <option value="1" {{ $items->barang->kategori == 1 ? 'selected' : '' }}>Dipinjam oleh siswa</option> --}}
        @foreach($categories as $category)
            <option value="{{ $category->id }}" data-nama="{{ strtolower($category->nama_kategori) }}" 
              {{ $barang->kategori_id == $category->id ? 'selected' : '' }}>
              {{ $category->nama_kategori }}
            </option>
        @endforeach
      </select>
      <input type="hidden" name="kategori" id="kategori" value="{{ $items->barang->kategori_id }}">
    </div>

    @if ($items->barang->peminjam)
      <div class="mb-3" id="field-nama-siswa">
        <label for="peminjam" class="form-label">Peminjam <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="peminjam" name="peminjam" value="{{ $items->barang->peminjam }}">
      </div>
    @endif
  
    <div class="mb-3">
      <label for="tipe_id" class="form-label">Tipe <span class="text-danger">*</span></label>
      <select class="form-select" id="tipe_id" name="tipe_id" required>
        {{-- <option value="1" {{ $items->barang->tipe == 1 ? 'selected' : '' }}>Barang berpindah</option>
        <option value="0" {{ $items->barang->tipe == 0 ? 'selected' : '' }}>Barang tetap</option> --}}
         {{-- @foreach($types as $type)
            <option value="{{ $type->id }}" 
            {{ $itemsMaster->tipe_id == $type->id ? 'selected' : '' }}>
              {{ $type->nama_tipe }}
            </option>
          @endforeach --}}
          @foreach($types as $type)
            <option value="{{ $type->id }}" 
              {{ $barang->tipe_id == $type->id ? 'selected' : '' }}>
              {{ $type->nama_tipe }}
            </option>
          @endforeach
          {{-- {{ var_dump($types) }} --}}
      </select>
      {{-- <input type="hidden" name="tipe" id="tipe" value="{{ $items->barang->tipe }}"> --}}
    </div>
  
    <div class="mb-3">
      <label for="status_id" class="form-label">Status <span class="text-danger">*</span></label>
      <select class="form-select" id="status_id" name="status_id" required>
        {{-- <option value="0" {{ $items->barang->status == 0 ? 'selected' : '' }}>Baru</option>
        <option value="1" {{ $items->barang->status == 1 ? 'selected' : '' }}>Hilang</option>
        <option value="2" {{ $items->barang->status == 2 ? 'selected' : '' }}>Rusak ringan</option>
        <option value="3" {{ $items->barang->status == 3 ? 'selected' : '' }}>Rusak</option>
        <option value="4" {{ $items->barang->status == 4 ? 'selected' : '' }}>Diperbarui</option> --}}
        @foreach ($statuses as $status)
            <option 
              value="{{ $status->id }}" 
              {{ $barang->status_id == $status->id ? 'selected' : '' }}>
              {{ $status->nama_status }}
            </option>
        @endforeach
      </select>
    </div>

    <div id="keterangan_container" data-keterangan="{{ old('keterangan', $items->barang->keterangan ?? '') }}">
      
    </div>
    {{-- {{ asset('storage', $items->surat) }} --}}
    
    <div id="surat_container" data-surat="{{ $items->surat ? asset('storage/' . old('surat', $items->surat)) : '' }}">
 
    </div>
  
    <div class="mb-3">
      <label for="harga" class="form-label">Harga Awal <span class="text-danger">*</span></label>
      <input type="text" class="form-control" name="harga_display" id="harga_display" 
        value="Rp. {{ number_format($items->barang->harga_awal, 0, ',', '.') }}" required readOnly>
      <input type="hidden" class="form-control" name="harga_awal" id="harga_awal" value="{{ $items->barang->harga_awal }}">
    </div>
  
    {{-- <div class="mb-3">
      <label class="form-label">Kode QR</label>
      <input type="text" class="form-control" name="kodeQR" value="{{ $items->barang->kodeQR }}" readonly>
    </div> --}}
    @if ($items->barang->kodeQR)
      <div>
        <h6 class="fw-semibold mb-1">Generate Kode QR</h6>
        {{-- <p class="fw-light mb-2">Item-#{{ $barang->kodeQR }}</p> --}}
        <input type="text" class="visually-hidden" id="display_kodeQR" name="display_kodeQR" value="{{ old('kodeQR', $items->barang->kodeQR ?? '') }}">
        <div id="qr-code" class="my-3"></div>
        <input type="text" class="visually-hidden" name="kodeQR" id="kodeQR" value="{{ old('kodeQR', $items->barang->kodeQR ?? '') }}">
        {{-- <img src="{{ asset('path/to/qr-code.png') }}" alt="QR Code" width="120"> --}}
      </div>
     @else
     <div class="mb-3">
       <label class="form-label">Generate Kode QR</label><br>
       <button type="button" class="btn btn-sm btn-outline-dark" id="generate-qr">Klik untuk Generate Kode QR</button>
       <div id="qr-code" class="my-3"></div>
       <input type="text" class="visually-hidden" name="kodeQR" id="kodeQR" value="{{ old('kodeQR', $items->barang->kodeQR ?? '') }}">
      </div>
    @endif

  
    <div class="mb-3 ">
      <label for="bukti" class="form-label">Bukti Pembelian :</label>
      @if($items->barang->bukti)
        <p><a href="{{ asset('storage/' . $items->barang->bukti) }}" target="_blank">Lihat Bukti</a></p>
        <img src="{{ asset('storage/' . $items->barang->bukti) }}" alt="" style="max-width: 500px;">
      @else
        <p class="fs-6 text-p-grey">
          <small>
            (Admin belum memasukkan bukti pembelian)
          </small>
        </p>
        <input type="file" class="form-control" id="bukti" name="bukti">
      @endif
    </div>

    <div class="mb-3">
      <label for="status" class="form-label">Biaya perbaikan <span class="text-danger">*</span></label>
      @if ($payment)
        <input type="text" min="0" class="form-control" name="biaya_perbaikan" id="biaya_perbaikan" value="" required>
      @else
        <p class="fs-6 text-p-grey">
          <small>
            (Admin belum memasukkan biaya perbaikan)
          </small>
        </p>
        <input type="text" min="0" class="form-control" name="biaya_perbaikan" id="biaya_perbaikan" value="" required>
      @endif
    </div>

    {{-- {{  }} --}}
    {{-- @currency($payment->biaya_perbaikan) --}}

    {{-- <div class="mb-3">
      <label for="biaya_perbaikan" class="form-label">Biaya perbaikan <span class="text-danger">*</span></label>
      <input 
        type="text" 
        class="form-control" 
        id="biaya_perbaikan_display"
        value="{{ $payment->biaya_perbaikan ? 'Rp. ' . number_format($payment->biaya_perbaikan, 0, ',', '.') : '' }}" 
        required
      >

      <input type="hidden" name="biaya_perbaikan" id="biaya_perbaikan" value="{{ $payment->biaya_perbaikan }}">
    </div> --}}


    <div class="mb-3">
      <label for="status" class="form-label">Jika dalam bentuk transfer</label>
      <input type="file" class="form-control" name="bukti_transfer" id="bukti_transfer" value="">
    </div>


  
    <div class="d-flex justify-content-end gap-2 mt-4">
      <a href="{{ route('pembayaran.index') }}">
        <button type="button" class="btn btn-danger">Batal</button>
      </a>
      <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmationmodal" id="confirmation-button">Konfirmasi</button>
    </div>

    <div class="modal fade" id="confirmationmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Apakah Anda yakin ingin mengkonfirmasi data ini?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Ya</button>
          </div>
        </div>
      </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

  const statusElement = document.getElementById("status_id");
  const keteranganContainer = document.getElementById("keterangan_container");
  const suratContainer = document.getElementById("surat_container");

  const kodeQRInput = document.getElementById("kodeQR");
  const qrContainer = document.getElementById("qr-code");
  const generateQRBtn = document.getElementById("generate-qr");

  const barangId = {{ $items->barang->id }};

  // Ambil data keterangan dari atribut data
  const savedKeterangan = keteranganContainer.dataset.keterangan;
  const savedSurat = suratContainer.dataset.surat;

  function updateFields() {

    const statusValue = statusElement.value;

    keteranganContainer.innerHTML = "";
    suratContainer.innerHTML = "";

    if (statusValue !== "1") {

      keteranganContainer.innerHTML = `
        <div class="mb-3" id="field-keterangan">
          <label for="keterangan" class="form-label">
            Keterangan <span class="text-danger">*</span>
          </label>
          <textarea name="keterangan" id="keterangan" cols="30" rows="4"
          class="form-control" required>${savedKeterangan || ''}</textarea>
        </div>
      `;

      suratContainer.innerHTML = `
        <div class="mb-3 d-flex flex-column" id="field-surat">
          <label for="surat" class="form-label">Bukti Surat</label>

          ${savedSurat ? `
            <img src="${savedSurat}" class="img-fluid"
            alt="Surat sebelumnya" style="max-width:500px;">
          ` : `
            <p class="fs-6 text-p-grey">
              <small>(Admin belum memasukkan surat kerusakan)</small>
            </p>
            <input type="file" class="form-control" id="surat" name="surat">
          `}
        </div>
      `;

    }

  }

  // Jalankan saat halaman load
  updateFields();

  // Jalankan saat status berubah
  statusElement.addEventListener("change", updateFields);

  // Ambil QR jika sudah ada
  const qrValue = document.getElementById("display_kodeQR")?.value;

  function generateQRContent() {

    return JSON.stringify({
      id: barangId
    });

  }

  function renderQRCode(content) {

    if (!qrContainer) return;

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

  // Klik tombol generate
  generateQRBtn?.addEventListener("click", function () {

    autoGenerateQR();

  });

  // Jika QR sudah ada → tampilkan langsung
  if (qrValue) {

    renderQRCode(qrValue);

  }

});


// Submit modal confirmation
const form = document.getElementById("submit-form");
const yesButton = document.getElementById("yes-button");

yesButton?.addEventListener("click", function () {

  form.submit();

});
</script>
@endpush