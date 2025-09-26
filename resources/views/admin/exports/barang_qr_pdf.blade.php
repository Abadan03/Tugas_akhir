<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-spacing: 10px;
        }

        td {
            width: 50%;
            vertical-align: top;
        }

        .item-wrapper {
            border: 1px solid #000;
            padding: 8px;
            height: 200px;
            box-sizing: border-box;
            display: flex;
            align-items: center;
        }

        .item-content {
            display: flex;
            flex-direction: row;
            width: 100%;
            height: 100%;
        }

        .qr-section {
            width: 75px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .qr-section img.qr {
            width: 60px;
            height: 60px;
        }

        .qr-section img.logo {
            width: 28px;
            margin-top: 4px;
        }

        .info {
            flex: 1;
            padding-left: 8px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .info div {
            margin-bottom: 1px;
            word-wrap: break-word;
        }

        .page-break {
            page-break-after: always;
        }
    </style>

</head>
<body>

@foreach ($barangs->chunk(8) as $pageChunk)
    <table>
        @foreach ($pageChunk->chunk(2) as $rowChunk)
            <tr>
                @foreach ($rowChunk as $barang)
                    <td>
                        <div class="item-wrapper">
                            <div class="item-content">
                                <table style="width: 100%;">
                                    <tr>
                                        <td style="width: 90px; text-align: center;">
                                            <img class="qr" src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(120)->generate(json_encode(['id' => $barang->id]))) }}" alt="QR Code"><br>
                                            <img class="logo" src="assets/images/LogoSabiraEdit.jpg.webp" alt="Logo" style="width: 60px; margin-top: 10px;">
                                        </td>
                                        <td style="padding-left: 10px;">
                                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                                <div><strong>Nama:</strong> {{ $barang->nama_barang }}</div>
                                                <div><strong>Kategori:</strong> {{ $barang->kategori == 1 ? 'Dipinjam oleh siswa' : 'Milik Sekolah' }}</div>
                                                @if($barang->kategori == 1)
                                                    <div><strong>Nama Siswa:</strong> {{ $barang->nama_siswa ?? '-' }}</div>
                                                @endif
                                                <div><strong>Tipe:</strong> {{ $barang->tipe == 0 ? 'Barang Tetap' : 'Barang Berpindah' }}</div>
                                                <div><strong>Status:</strong>
                                                    @switch($barang->status)
                                                        @case(0) Baru @break
                                                        @case(1) Hilang @break
                                                        @case(2) Rusak Ringan @break
                                                        @case(3) Rusak @break
                                                        @case(4) Diperbarui @break
                                                        @default - @break
                                                    @endswitch
                                                </div>
                                                <div><strong>Keterangan:</strong> {{ $barang->keterangan ?? '-' }}</div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </td>
                @endforeach
                @if ($rowChunk->count() < 2)
                    <td></td>
                @endif
            </tr>
        @endforeach
    </table>
    @if (!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach


</body>
</html>
