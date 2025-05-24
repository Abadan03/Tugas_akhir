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

<form action="{{ route('pembayaran.update', $items->id) }}" method="POST" enctype="multipart/form-data" class="p-4 bg-white" id="submit-form">
  @csrf
  @method('PUT')
  {{-- @if ($barang->status == 0) --}}
    {{-- <h1>assd</h1> --}}
    <div class="mb-3">
      <label for="nama_barang" class="form-label">Nama Barang<span class="text-danger">*</span></label>
      <input type="text" class="form-control" id="nama_barang_display" name="nama_barang_display" value="{{ $items->barang->nama_barang }}" required disabled>
      <input type="hidden" class="form-control" id="nama_barang" name="nama_barang" value="{{ $items->barang->nama_barang }}">
    </div>
  
    <div class="mb-3">
      <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
      <select class="form-select" id="kategori_display" name="kategori_display" required disabled>
        <option value="0" {{ $items->barang->kategori == 0 ? 'selected' : '' }}>Milik Sekolah</option>
        <option value="1" {{ $items->barang->kategori == 1 ? 'selected' : '' }}>Dipinjam oleh siswa</option>
      </select>
      <input type="hidden" name="kategori" id="kategori" value="{{ $items->barang->kategori }}">
    </div>

    <div class="mb-3 d-none" id="field-nama-siswa">
      <label for="nama_siswa" class="form-label">Nama Siswa <span class="text-danger">*</span></label>
      <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" value="{{ $items->barang->nama_siswa }}">
    </div>
  
    <div class="mb-3">
      <label for="tipe" class="form-label">Tipe <span class="text-danger">*</span></label>
      <select class="form-select" id="tipe_display" name="tipe_display" required disabled>
        <option value="1" {{ $items->barang->tipe == 1 ? 'selected' : '' }}>Barang berpindah</option>
        <option value="0" {{ $items->barang->tipe == 0 ? 'selected' : '' }}>Barang tetap</option>
      </select>
      <input type="hidden" name="tipe" id="tipe" value="{{ $items->barang->tipe }}">
    </div>
  
    <div class="mb-3">
      <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
      <select class="form-select" id="status" name="status" required disabled>
        <option value="0" {{ $items->barang->status == 0 ? 'selected' : '' }}>Baru</option>
        <option value="1" {{ $items->barang->status == 1 ? 'selected' : '' }}>Hilang</option>
        <option value="2" {{ $items->barang->status == 2 ? 'selected' : '' }}>Rusak ringan</option>
        <option value="3" {{ $items->barang->status == 3 ? 'selected' : '' }}>Rusak</option>
      </select>
    </div>

    <div id="keterangan_container" data-keterangan="{{ old('keterangan', $items->barang->keterangan ?? '') }}">
      
    </div>
    {{-- {{ asset('storage', $items->surat) }} --}}
    
    <div id="surat_container" data-surat="{{ $items->surat ? asset('storage/' . old('surat', $items->surat)) : '' }}">
 
    </div>
  
    <div class="mb-3">
      <label for="harga" class="form-label">Harga Awal <span class="text-danger">*</span></label>
      <input type="number" class="form-control" name="harga_display" id="harga_display" value="{{ $items->barang->harga_awal }}" required disabled>
      <input type="hidden" class="form-control" name="harga_awal" id="harga_awal" value="{{ $items->barang->harga_awal }}">
    </div>
  
    <div class="mb-3">
      <label class="form-label">Kode QR</label>
      <input type="text" class="form-control" name="kodeQR" value="{{ $items->barang->kodeQR }}" readonly>
    </div>
  
    <div class="mb-3 ">
      <label for="bukti" class="form-label">Bukti Pembelian :</label>
      @if($items->barang->bukti)
        <p><a href="{{ asset('storage/' . $items->barang->bukti) }}" target="_blank">Lihat Bukti</a></p>
        <img src="{{ asset('storage/' . $items->barang->bukti) }}" alt="" style="max-width: 500px;">
      @else
        <p class="fs-6 text-danger"><span>* </span>Admin belum memasukkan bukti pembelian</p>
        <input type="file" class="form-control" id="bukti" name="bukti">
      @endif
    </div>

    <div class="mb-3">
      <label for="status" class="form-label">Jika dalam bentuk transfer</label>
      <input type="file" class="form-control" name="transfer" id="transfer" value="">
    </div>


  
    <div class="d-flex justify-content-end gap-2 mt-4">
      <a href="{{ route('pembayaran.index') }}">
        <button type="button" class="btn btn-danger">Batal</button>
      </a>
      <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmationmodal" id="confirmation-button">Konfirmasi</button>
    </div>

    <!-- Modal -->
    {{-- <div class="modal fade" id="confirmationmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            ...
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" >Yes</button>
          </div>
        </div>
      </div>
    </div> --}}
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
    const statusElement = document.getElementById("status");
    const keteranganContainer = document.getElementById("keterangan_container");
    const suratContainer = document.getElementById("surat_container");
    
    

    // Ambil data keterangan dari atribut data-keterangan
    const savedKeterangan = keteranganContainer.dataset.keterangan;
    const savedSurat = suratContainer.dataset.surat;
    console.log("Image source is:", savedSurat);

    function updateFields() {
      const statusValue = statusElement.value;
      keteranganContainer.innerHTML = "";
      suratContainer.innerHTML = "";

      if (statusValue !== "0") {
        keteranganContainer.innerHTML = `
          <div class="mb-3" id="field-keterangan">
            <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
            <textarea name="keterangan" id="keterangan" cols="30" rows="4" class="form-control" required>${savedKeterangan || ''}</textarea>
          </div>
        `;

        suratContainer.innerHTML = `
          <div class="mb-3 d-flex flex-column" id="field-surat">
            <label for="surat" class="form-label">Bukti Surat</label>
            <img src="${savedSurat || ''}" class="img-fluid" alt="Surat sebelumnya" style="max-width: 500px;">
          </div>
        `;
      }
    }

    // Inisialisasi saat halaman dimuat
    updateFields();

    // Update saat status berubah
    statusElement.addEventListener("change", updateFields);
  });

  const form = document.getElementById("submit-form");
  const yesButton = document.getElementById("yes-button");

   yesButton.addEventListener("click", function () {
    form.submit();
  });
</script>

@endpush