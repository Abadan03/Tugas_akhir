@extends('layouts.app')

@section('content')
<div class="container-fluid my-4">
  <div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ $backRoute }}" class="text-success">
      <i class="bi bi-arrow-left-square fs-3"></i>
    </a>
    <h4 class="mb-0 text-success">Scan QR Code</h4>
  </div>

  <p class="text-muted">Pastikan QR code jelas, tidak buram, dan di tengah frame.</p>
  <a href="{{ route('qrcode.render') }}" class="text-warning">Klik disini untuk scan kembali</a>

  <div id="reader" class="my-4 shadow-sm rounded border border-success" style="width: 100%; max-width: 800px; margin: auto;"></div>
  <div id="scan-result" class="mt-4"></div>
</div>

@include('layouts.flash-message')

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="editForm" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Barang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="edit-id" name="id">

        <div class="row g-3">
          <div class="col-md-6">
            <label for="edit-nama-barang" class="form-label">Nama Barang</label>
            <input type="text" class="form-control" id="edit-nama-barang" name="nama_barang" required readonly>
            <!-- <select class="form-select" id="edit-nama-barang" name="nama_barang" required>
            @foreach($categories as $kategori)
              <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
            @endforeach
            </select> -->
          </div>

          <div class="col-md-6">
            <label for="edit-harga" class="form-label">Harga Awal</label>
            <input type="number" class="form-control" id="edit-harga" name="harga_awal" required>
          </div>

          <div class="col-md-6">
            <label for="edit-tipe" class="form-label">Tipe Barang</label>
            <select class="form-select" id="edit-tipe" name="tipe" required>
              @foreach($types as $tipe)
                <option value="{{ $tipe->id }}">{{ $tipe->nama_tipe }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-6">
            <label for="edit-kategori" class="form-label">Kategori</label>
            <select class="form-select" id="edit-kategori" name="kategori" required>
              @foreach($categories as $kategori)
                <option value="{{ $kategori->id }}" data-trigger="{{ $kategori->defaultTrigger ? 'true' : 'false' }}">{{ $kategori->nama_kategori }}</option>
              @endforeach
            </select>
          </div>

            {{-- <div class="col-md-6 visually-hidden" id="edit-nama-siswa-container">
              <label for="edit-nama-siswa" class="form-label">Peminjam</label>
              <select class="form-select" id="edit-nama-siswa" name="peminjam">
                <option value="">Pilih peminjam</option>
                @foreach($siswaList as $siswa)
                  <option value="{{ $siswa->peminjam }}">{{ $siswa->peminjam }}</option>
                @endforeach
              </select>
            </div> --}}

          {{-- <div id="peminjam_container" @if ($barang->kategori_id != '1') style="display:none;" @endif> --}}
          <div id="peminjam_container" style="display:none;">
            <div class="mb-3">
              <label for="peminjam" class="form-label">Peminjam<span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="peminjam" name="peminjam" value="{{ old('peminjam', $barang->peminjam ?? '') }}">
            </div>
          </div>


          <div class="col-md-6">
            <label for="edit-status" class="form-label">Status</label>
            <select class="form-select" id="edit-status" name="status" required>
              @foreach($statuses as $status)
                <option value="{{ $status->id }}">{{ $status->nama_status }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-12 visually-hidden" id="edit-keterangan-container">
            <label for="edit-keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control" id="edit-keterangan" name="keterangan" rows="3"></textarea>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Simpan</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
  const resultContainer = document.getElementById("scan-result");
  const html5QrCode = new Html5Qrcode("reader");

  const namaSiswaContainer = document.getElementById("edit-nama-siswa-container");
  const keteranganContainer = document.getElementById("edit-keterangan-container");

  function updateConditionalFields() {
    const kategoriSelect = document.getElementById("edit-kategori");
    const selectedKategori = kategoriSelect.options[kategoriSelect.selectedIndex];
    const trigger = selectedKategori.getAttribute("data-trigger");
    const statusVal = document.getElementById("edit-status").value;

    // Jika kategori memiliki defaultTrigger = false (artinya butuh input Peminjam)
    if (trigger === "false") {
      document.getElementById("peminjam_container").style.display = "block";
    } else {
      document.getElementById("peminjam_container").style.display = "none";
      document.getElementById("peminjam").value = "";
    }

    // Jika status = 2, 3, 4
    if (["2","3","4"].includes(statusVal)) {
      document.getElementById("edit-keterangan-container").classList.remove("visually-hidden");
    } else {
      document.getElementById("edit-keterangan-container").classList.add("visually-hidden");
      document.getElementById("edit-keterangan").value = "";
    }
  }

  document.getElementById("edit-kategori").addEventListener("change", updateConditionalFields);
  document.getElementById("edit-status").addEventListener("change", updateConditionalFields);

  function handleDecodedText(decodedText) {
    try {
      const data = JSON.parse(decodedText);
      if (!data.id) {
        resultContainer.innerHTML = `<p class="text-danger">QR tidak valid: ID tidak ditemukan.</p>`;
        return;
      }

      fetch(`/qrcode/fetch/${data.id}`)
        .then(res => res.json())
        .then(barang => {
          resultContainer.innerHTML = `
            <ul class="list-group">
              <li class="list-group-item"><strong>ID:</strong> ${barang.id}</li>
              <li class="list-group-item"><strong>Nama Barang:</strong> ${barang.nama_barang}</li>
              <li class="list-group-item"><strong>Kategori:</strong> ${barang.kategori_label}</li>
              <li class="list-group-item"><strong>Harga Awal:</strong> Rp${Number(barang.harga_awal).toLocaleString()}</li>
              <li class="list-group-item"><strong>Peminjam:</strong> ${barang.peminjam ?? '-'}</li>
              <li class="list-group-item"><strong>Tipe:</strong> ${barang.tipe_label}</li>
              <li class="list-group-item"><strong>Status:</strong> ${barang.status_label}</li>
              <li class="list-group-item"><strong>Keterangan:</strong> ${barang.keterangan ?? '-'}</li>
              <button type="button" class="btn btn-success" data-id="${barang.id}" id="btn-edit-barang">Edit</button>
            </ul>`;
        });
    } catch {
      resultContainer.innerHTML = `<p class="text-danger">QR tidak valid atau bukan format JSON.</p>`;
    }
  }

  document.addEventListener('click', function (e) {
    if (e.target && e.target.id === 'btn-edit-barang') {
      const barangId = e.target.dataset.id;
      fetch(`/qrcode/fetch/${barangId}`)
        .then(res => res.json())
        .then(barang => {
          document.getElementById('edit-id').value = barang.id;
          document.getElementById('edit-nama-barang').value = barang.nama_barang;
          document.getElementById('edit-harga').value = barang.harga_awal;
          document.getElementById('edit-tipe').value = barang.tipe;
          document.getElementById('edit-kategori').value = barang.kategori;
          document.getElementById('edit-status').value = barang.status;
          // document.getElementById('edit-nama-siswa').value = barang.peminjam ?? '';
          document.getElementById('peminjam').value = barang.peminjam ?? '';
          document.getElementById('edit-keterangan').value = barang.keterangan ?? '';
          updateConditionalFields();
          new bootstrap.Modal(document.getElementById('editModal')).show();
        });
    }
  });

  document.getElementById('editForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const id = document.getElementById('edit-id').value;
    const formData = new FormData(this);

    fetch(`/qrcode/update/${id}`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      alert(data.message || 'Berhasil diupdate!');
      bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
      fetch(`/qrcode/fetch/${id}`)
        .then(res => res.json())
        .then(barang => {
          resultContainer.innerHTML = `
            <ul class="list-group">
              <li class="list-group-item"><strong>ID:</strong> ${barang.id}</li>
              <li class="list-group-item"><strong>Nama Barang:</strong> ${barang.nama_barang}</li>
              <li class="list-group-item"><strong>Kategori:</strong> ${barang.kategori_label}</li>
              <li class="list-group-item"><strong>Harga Awal:</strong> Rp${Number(barang.harga_awal).toLocaleString()}</li>
              <li class="list-group-item"><strong>Peminjam:</strong> ${barang.peminjam ?? '-'}</li>
              <li class="list-group-item"><strong>Tipe:</strong> ${barang.tipe_label}</li>
              <li class="list-group-item"><strong>Status:</strong> ${barang.status_label}</li>
              <li class="list-group-item"><strong>Keterangan:</strong> ${barang.keterangan ?? '-'}</li>
              <button type="button" class="btn btn-success" data-id="${barang.id}" id="btn-edit-barang">Edit</button>
            </ul>`;
        });
    })
    .catch(err => alert("Terjadi kesalahan saat update: " + err.message));
  });

  Html5Qrcode.getCameras()
  .then(cameras => {
    console.log("Cameras found:", cameras); // debug
    if (cameras && cameras.length) {
      html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 300, height: 300 } },
        decodedText => {
          handleDecodedText(decodedText);
          html5QrCode.stop();
        },
        err => console.warn("Scan error:", err)
      );
    } else {
      resultContainer.innerHTML = `<p class="text-danger">Tidak ditemukan kamera.</p>`;
    }
  })
  .catch(err => {
    // Ini yang sering tidak ketangkep!
    console.error("getCameras error:", err);
    resultContainer.innerHTML = `<p class="text-danger">Error akses kamera: ${err}</p>`;
  });
});
</script>
@endpush
