@extends('layouts.app')

@section('content')
<div class="bg-mainbg">
  <div class="container-fluid d-flex justify-content-between align-items-center py-3 px-5 border-3 border-bottom rounded-3 bg-white">
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
    <h4 class="mb-0">Tambah barang</h4>
  </div>

  {{-- main --}}
  <div class="container-fluid bg-white">
    <form action="{{ route("inventaris.store") }}" class="p-4" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="mb-3">
        <label for="items_id" class="form-label">Nama Barang<span class="text-danger">*</span></label>
        <select class="form-select" id="items_id" name="items_id" required>
          <option value="" disabled selected>Pilih Nama Barang</option>
          @foreach ($items as $item)
            <option value="{{ $item->id }}">{{ $item->nama_barang }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
        <select class="form-select" id="kategori_id" name="kategori_id" required>
          <option value="" disabled selected>Pilih kategori</option>
          @foreach ($categories as $category)
            <option value="{{ $category->id }}" data-trigger="{{ $category->defaultTrigger }}">{{ $category->nama_kategori }} </option>
          @endforeach
            
        </select>
      </div>

      <div class="mb-3 d-none" id="field-peminjam">
        <label for="peminjam" class="form-label">Peminjam <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="peminjam" name="peminjam">
      </div>

      <div class="mb-3">
        <label for="tipe_id" class="form-label">Tipe <span class="text-danger">*</span></label>
        <select class="form-select" id="tipe_id" name="tipe_id" required>
          <option value="" disabled selected>Pilih tipe</option>
          @foreach ($types as $type)
            <option value="{{ $type->id }}">{{ $type->nama_tipe }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label for="status_id" class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-select" id="status_id" name="status_id" required>
          <option value="" disabled selected>Pilih status barang</option>
          @foreach ($statuses as $status)
            <option value="{{ $status->id }}">{{ $status->nama_status }}</option>
          @endforeach
        </select>
      </div>

      {{-- Field tambahan: Surat & Keterangan (dinamis berdasarkan status) --}}
      <div class="mb-3 d-none" id="field-keterangan">
        <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
        <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Tuliskan keterangan tambahan..."></textarea>
      </div>
      <div class="mb-3 d-none" id="field-surat">
        <label for="surat" class="form-label">Surat (Opsional)</label>
        <input type="file" class="form-control" id="surat" name="surat">
      </div>

      <div class="mb-3">
        <label for="harga" class="form-label">Harga Awal <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="harga_awal" id="harga" required>
      </div>

      <div class="mb-3">
        <label for="bukti" class="form-label">Bukti :</label>
        <input type="file" class="form-control" id="bukti" name="bukti">
      </div>

      <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('inventaris.index') }}">
          <button type="button" class="btn btn-danger">Batal</button>
        </a>
        <button type="submit" class="btn btn-success">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const kategoriSelect = document.getElementById('kategori_id');
    // const GetTtrigger = document.querySelector('#kategori_id');
    const peminjamField = document.getElementById('field-peminjam');
    const peminjamInput = document.getElementById('peminjam');

    const statusSelect = document.getElementById('status_id');
    const suratField = document.getElementById('field-surat');
    const suratInput = document.getElementById('surat');
    const ketField = document.getElementById('field-keterangan');
    const ketInput = document.getElementById('keterangan');

     // === QR CODE Container ===
    // const qrContainer = document.createElement('div');
    // qrContainer.id = "qrCodeContainer";
    // qrContainer.classList.add("mt-3");
    // form.parentNode.appendChild(qrContainer);
    


    // toggle Nama Siswa
    function togglePeminjaman() {
      const selectedOption = kategoriSelect.options[kategoriSelect.selectedIndex];
      const trigger = selectedOption.dataset.trigger;
      console.log(trigger, "ini trigger");
      // if (kategoriSelect.value == 2) {
      if (trigger == 0) {
        peminjamField.classList.remove('d-none');
        peminjamInput.required = true;
      } else {
        peminjamField.classList.add('d-none');
        peminjamInput.required = false;
        peminjamInput.value = '';
      }
    }

    // toggle Surat & Keterangan
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

    kategoriSelect.addEventListener('change', togglePeminjaman);
    statusSelect.addEventListener('change', toggleSuratDanKeterangan);

    // Trigger on load
    togglePeminjaman();
    toggleSuratDanKeterangan();


     // === Submit handler dengan QR Auto Generate ===
    // form.addEventListener('submit', async function (e) {
    //   e.preventDefault();
    //   const formData = new FormData(form);

    //   try {
    //     const response = await fetch(form.action, {
    //       method: 'POST',
    //       body: formData
    //     });
    //     const result = await response.json();

    //     if (result.success) {
    //       // Bersihkan kontainer QR sebelum generate baru
    //       qrContainer.innerHTML = "";

    //       const barang = result.data; // pastikan controller return {success:true,data:barang}
    //       const qrData = `
    //         Nama Barang: ${barang.nama_barang}
    //         Kategori: ${barang.kategori.nama_kategori}
    //         Status: ${barang.status.nama_status}
    //         Harga: ${barang.harga_awal}
    //                   `;

    //         new QRCode(qrContainer, {
    //           text: qrData,
    //           width: 180,
    //           height: 180
    //         });

    //         alert("Barang berhasil ditambahkan dan QR Code sudah digenerate otomatis!");
    //         form.reset();
    //       } else {
    //         alert("Terjadi kesalahan saat menyimpan data.");
    //       }
    //     } catch (err) {
    //       console.error(err);
    //       alert("Gagal menambahkan barang.");
    //     }
    //   });
  });
</script>
@endpush
