  @extends('layouts.app')

  @section('content')

  <div class="container-fluid d-flex justify-content-between align-items-center py-3 px-5 border-3 border-bottom rounded-3">
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

  <div class="py-4 px-3">
    <h2 class="fw-bold">Pembayaran</h2>
    <h4 class="fw-semibold mb-4">Keseluruhan Pembayaran</h4>
  </div>

  <div class="container-fluid d-flex justify-content-between align-items-center">
    <div>
      <h1>Inventaris</h1>
    </div>
    <div>
      {{-- <a href="">
        <button class="btn btn-lightblue text-white ">Laporan kerusakan</button>
      </a> --}}
      <a href="{{ route('qrcode.render', ['from' => 'pembayaran']) }}">
        <button class="btn btn-darkblue text-white ">Scan Kode QR</button>
      </a>
      <a href="">
        <button class="btn btn-lightgreen text-white">Import CSV</button>
      </a>
      <a href="">
        <button class="btn btn-grey text-white">Download All</button>
      </a>
    </div>
  </div>

  <div class="container-fluid">
    <table id="example" class="table table-striped" style="width:100%">
      <thead>
          <tr>
              <th>Nama Barang</th>
              {{-- <th>Id barang</th> --}}
              <th>Kategori</th>
              <th>Nama Siswa</th>
              <th>Tipe</th>
              <th>Status</th>
              <th>Keterangan</th>
              {{-- <th>Harga Perbaikan</th> --}}
              <th>Action</th>
          </tr>
      </thead>
      <tbody>
        {{-- {{ $data }} --}}
        @forelse ($data as $item)
        {{-- {{ 'ini barang_id :' . $item->barang_id }}
        {{ 'ini status :' . $item->status }}  --}}
        {{-- {{ $item }} --}}
        <tr>
            <td>{{ $item->nama_barang }}</td>
            <td>
            @if ($item && $item->kategori == 1)
            Dipinjam oleh siswa
            @elseif ($item && $item->kategori == 0)
                Milik Sekolah
            @endif
            </td>
            @if ($item->nama_siswa)
              <td>
                {{ $item->nama_siswa }}
              </td>
            @else
              <td>
                -
              </td>
            @endif
            <td>
            @if ($item->tipe == 0)
              Barang Tetap
            @elseif ($item->tipe == 1)
              Barang Berpindah
            @endif
            </td>
            <td>
            @if ($item->status == 0)
              Baru
            @elseif ($item->status == 1)
              Hilang
            @elseif ($item->status == 2)
              Rusak Ringan
            @elseif ($item->status == 3)
              Rusak
            @elseif ($item->status == 4)
              Diperbarui
            @endif
            </td>
            <td>{{ $item->keterangan }}</td>
            {{-- <td>{{ $item->harga }}</td> --}}
            <td class="d-flex gap-2">
                {{-- @if ($item->status == 4)
                  <a href="{{ route('pembayaran.show', $item->pembayaran_id) }}" class="text-black">
                    <i class="bi bi-eye"></i>
                  </a>
                @endif --}}
                <form action="{{ route('pembayaran.destroy', $item->barang_rusaks_id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="border: none; background: none;" class="text-black">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
                @if ($item->status != 4)
                  <a href="{{ route('pembayaran.edit', $item->barang_rusaks_id) }}" class="text-black">
                      <i class="bi bi-pencil-square"></i>
                  </a>
                @endif
            </td>
        </tr>
        @empty
        {{-- Jika data kosong --}}
        <tr>
          <td colspan="7" class="text-center">
            <div class="alert alert-info my-2">
              Tidak ada data tersedia.
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>


  @endsection