@extends('layouts.app')


@section('content')

{{-- <div id="reader" width="600px"></div>
<div id="scan-result" class="mt-4"></div> --}}

<div class="container-fluid my-4">

  <div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ $backRoute }}">
      <i class="bi bi-arrow-left-square fs-3"></i>
    </a>
    <h4 class="mb-0">Scan QR Code</h4>
  </div>
  <h4>Scan QR Code</h4>




  <div class="mb-3">
    <label for="qr-file" class="form-label">Atau unggah gambar QR Code</label>
    <input type="file" class="form-control" id="qr-file" accept="image/*">
  </div>

  <p class="text-muted">Pastikan QR code jelas, tidak buram, dan di tengah frame</p>
  <div id="reader" width="" class="my-4" style="width: 600px !important;"></div>

  <div id="scan-result" class="mt-4"></div>
</div>

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const resultContainer = document.getElementById("scan-result");
    const html5QrCode = new Html5Qrcode("reader");

    function handleDecodedText(decodedText) {
      console.log("Isi QR yang dideteksi:", decodedText); // tambahkan ini
      try {
        const data = JSON.parse(decodedText);
        // console.log(`ini data kontol ${data}`)
        const html = `
          <ul class="list-group">

            <li class="list-group-item"><strong>ID:</strong> ${data.id}</li>
            <li class="list-group-item"><strong>Nama Barang:</strong> ${data.nama_barang}</li>
            <li class="list-group-item"><strong>Kategori:</strong> ${data.kategori}</li>
            <li class="list-group-item"><strong>Harga Awal:</strong> Rp${Number(data.harga_awal).toLocaleString()}</li>
            <li class="list-group-item"><strong>Nama Siswa:</strong> ${data.nama_siswa ? data.nama_siswa : '-'}</li>
            <li class="list-group-item"><strong>Tipe:</strong> ${data.tipe}</li>
            <li class="list-group-item"><strong>Status:</strong> ${data.status}</li>
            <li class="list-group-item"><strong>Keterangan:</strong> ${data.keterangan}</li>
          </ul>
        `;
        resultContainer.innerHTML = html;
      } catch (e) {
        resultContainer.innerHTML = `<p class="text-danger">QR tidak valid atau bukan JSON.</p>`;
      }
    }

    // Mulai kamera
    Html5Qrcode.getCameras().then(cameras => {
      if (cameras && cameras.length) {
        html5QrCode.start(
          { facingMode: "environment" },
          {
            fps: 10,
            qrbox: {
              width: 300,
              height: 300
            }
          },
          (decodedText, decodedResult) => {
            handleDecodedText(decodedText);
            html5QrCode.stop(); // stop setelah scan 1x
          },
          (err) => {
            console.warn("Scan error: ", err);
          }
        );
      } else {
        resultContainer.innerHTML = `<p class="text-danger">Tidak ditemukan kamera.</p>`;
      }
    }).catch(err => {
      resultContainer.innerHTML = `<p class="text-danger">Gagal mengakses kamera: ${err}</p>`;
    });

    // Scan dari gambar upload
    document.getElementById("qr-file").addEventListener("change", function (e) {
      if (e.target.files.length === 0) return;

      const file = e.target.files[0];
      html5QrCode.scanFile(file, true)
        .then(decodedText => {
          handleDecodedText(decodedText);
        })
        .catch(err => {
          resultContainer.innerHTML = `<p class="text-danger">Gagal membaca QR dari gambar: ${err}</p>`;
        });
    });
  });
</script>
@endpush


@endsection