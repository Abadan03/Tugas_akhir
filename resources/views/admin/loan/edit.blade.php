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
    {{-- @if ($barang->status == 0) --}}
      {{-- <h1>assd</h1> --}}
      <div class="mb-3">
        <label for="nama_barang" class="form-label">Nama Barang<span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="nama_barang_display" name="nama_barang_display" value="{{ $barang->nama_barang }}" required disabled>
        <input type="hidden" class="form-control" id="nama_barang" name="nama_barang" value="{{ $barang->nama_barang }}">
      </div>
    
      <div class="mb-3">
        <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
        <select class="form-select" id="kategori_display" name="kategori_display" required disabled>
          <option value="0" {{ $barang->kategori == 0 ? 'selected' : '' }}>Milik Sekolah</option>
          <option value="1" {{ $barang->kategori == 1 ? 'selected' : '' }}>Dipinjam oleh siswa</option>
        </select>
        <input type="hidden" name="kategori" id="kategori" value="{{ $barang->kategori }}">
      </div>

      <div class="mb-3 d-none" id="field-nama-siswa">
        <label for="nama_siswa" class="form-label">Nama Siswa <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" value="{{ $barang->nama_siswa }}">
      </div>
    
      <div class="mb-3">
        <label for="tipe" class="form-label">Tipe <span class="text-danger">*</span></label>
        <select class="form-select" id="tipe_display" name="tipe_display" required disabled>
          <option value="1" {{ $barang->tipe == 1 ? 'selected' : '' }}>Barang berpindah</option>
          <option value="0" {{ $barang->tipe == 0 ? 'selected' : '' }}>Barang tetap</option>
        </select>
        <input type="hidden" name="tipe" id="tipe" value="{{ $barang->tipe }}">
      </div>
    
      <div class="mb-3">
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select" id="status" name="status" required>
          <option value="0" {{ $barang->status == 0 ? 'selected' : '' }}>Baru</option>
          <option value="1" {{ $barang->status == 1 ? 'selected' : '' }}>Hilang</option>
          <option value="2" {{ $barang->status == 2 ? 'selected' : '' }}>Rusak ringan</option>
          <option value="3" {{ $barang->status == 3 ? 'selected' : '' }}>Rusak</option>
        </select>
      </div>

      <div id="keterangan_container">
        
      </div>
    
      
      <div id="surat_container">

      </div>
    
      <div class="mb-3">
        <label for="harga" class="form-label">Harga Awal <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="harga_display" id="harga_display" value="{{ $barang->harga_awal }}" required>
        <input type="hidden" class="form-control" name="harga_awal" id="harga_awal" value="{{ $barang->harga_awal }}">
      </div>
    
      <div class="mb-3">
        <label class="form-label">Kode QR</label>
        <input type="text" class="form-control" name="kodeQR" value="{{ $barang->kodeQR }}" readonly>
      </div>
    
      <div class="mb-3 ">
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

  // $("#status").change(function() {
  //   if ($(this).val() == "1") {
  //     $("#keterangan_container").show();
  //     $("#field-keterangan").removeClass("d-none");
  //   } else {
  //     $("#keterangan_container").hide();
  //     $("#field-keterangan").addClass("d-none");
  //   }
  // });

    document.getElementById("status").addEventListener("change", function () {
      const statusValue = this.value;
      const keteranganContainer = document.getElementById("keterangan_container");
      const suratContainer = document.getElementById("surat_container");
      //  <p><a href="{{ asset('storage/' . $barang->bukti) }}" target="_blank">Lihat Bukti</a></p>

      // Clear the container first
      keteranganContainer.innerHTML = "";
      suratContainer.innerHTML = "";

      if (statusValue != "0") {
        // Append the HTML if status is 1
        keteranganContainer.innerHTML = `
          <div class="mb-3" id="field-keterangan">
            <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
            <textarea name="keterangan" id="keterangan" cols="30" rows="4" class="form-control"></textarea>
          </div>
        `;
        
        suratContainer.innerHTML = `
          <div class="mb-3" id="field-surat">
            <label for="surat" class="form-label">Keterangan jika dalam bentuk surat</label>
            <input type="file" class="form-control" id="surat" name="surat">
          </div>
        `;
      }
    });





  // document.addEv
</script>
@endpush