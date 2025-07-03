<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        .item-wrapper {
            width: 48%;
            float: left;
            margin: 1%;
            border: 1px solid #000;
            padding: 10px;
            box-sizing: border-box;
        }
        .qr { float: left; width: 40%; }
        .info { float: right; width: 55%; }
        .clearfix { clear: both; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

@foreach ($barangs->chunk(4) as $chunk)
    @foreach ($chunk as $barang)
        <div class="item-wrapper">
            <div class="qr">
                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate(json_encode(['id' => $barang->id]))) !!}" alt="QR Code">
            </div>
            <div class="info">
                <strong>Nama:</strong> {{ $barang->nama_barang }} <br>
                <strong>Kategori:</strong> {{ $barang->kategori == 1 ? 'Dipinjam oleh siswa' : 'Milik Sekolah' }}<br>
                <strong>Nama Siswa:</strong> {{ $barang->nama_siswa ?? '-' }} <br>
                <strong>Tipe:</strong> {{ $barang->tipe == 0 ? 'Barang Tetap' : 'Barang Berpindah' }}<br>
                <strong>Status:</strong>
                    @switch($barang->status)
                        @case(0) Baru @break
                        @case(1) Hilang @break
                        @case(2) Rusak Ringan @break
                        @case(3) Rusak @break
                        @case(4) Diperbarui @break
                    @endswitch <br>
                <strong>Keterangan:</strong> {{ $barang->keterangan ?? '-' }}
            </div>
            <div class="clearfix"></div>
        </div>
    @endforeach
    <div class="page-break"></div>
@endforeach

</body>
</html>
