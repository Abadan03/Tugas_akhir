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
  <h2 class="fw-bold">Peminjaman</h2>
  <h4 class="fw-semibold mb-4">Keseluruhan Peminjaman</h4>
</div>

{{-- Buttons menu --}}
<div class="container-fluid d-flex justify-content-between align-items-center">
  <div>
    <h1>Peminjaman</h1>
  </div>
  <div>
    {{-- <a href="">
      <button class="btn btn-lightblue text-white ">Laporan kerusakan</button>
    </a> --}}
    <a href="">
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
            <th>Nama barang</th>
            <th>Kategori</th>
            <th>Nama Siswa</th>
            <th>Tipe</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
      @foreach ($data as $item)
      {{-- @if ($item->barang->status === "Baru") --}}
       {{-- {{ $item->status }} --}}
       {{-- {{ $item->barang->tipe }} --}}
      <tr>
          <td>{{ $item->barang->nama_barang }}</td>
          <td>
            @if ($item->barang && $item->barang->kategori == 1)
              Dipinjam oleh siswa
            @elseif ($item->barang && $item->barang->kategori == 0)
              Milik Sekolah
            @endif
          </td>
          <td>
            {{ $item->barang->nama_siswa }}
          </td>
          <td>
            @if ($item->barang->tipe == 0)
              Barang Tetap
            @elseif ($item->barang->tipe == 1)
              Barang Berpindah
            @endif
          </td>
          <td>
            @if ($item->barang->status == 0)
              Baru
            @elseif ($item->barang->status == 1)
              Hilang
            @elseif ($item->barang->status == 2)
              Rusak Ringan
            @elseif ($item->barang->status == 3)
              Rusak
            @elseif ($item->barang->status == 4)
              Diperbarui
            @endif
          </td>

         
          <td class="d-flex gap-2">

            {{-- {{ $item->barang_id }} --}}
              <a href="{{ route('peminjaman.show', $item->barang_id) }}" class="text-black">
                  <i class="bi bi-eye"></i>
              </a>
              <form action="{{ route('peminjaman.destroy', $item->id) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button type="submit" style="border: none; background: none;" class="text-black">
                      <i class="bi bi-trash"></i>
                  </button>
              </form>
              {{-- @if ($item->barang->status == 0 && $item->barang->kategori != 0)
                <a href="{{ route('peminjaman.edit', $item->barang->id) }}" class="text-black">
                    <i class="bi bi-pencil-square"></i>
                </a>
              @endif --}}
              @if ($item->barang->status == 0 || $item->barang->status === 4)
                @if ($item->barang->kategori != 0)
                  <a href="{{ route('peminjaman.edit', $item->barang->id) }}" class="text-black">
                      <i class="bi bi-pencil-square"></i>
                  </a>
                @endif
              {{-- @elseif ($item->barang->status == 4 && $item->kategori != 1) 
                  <a href="{{ route('inventaris.edit', $item->id) }}" class="text-black">
                      <i class="bi bi-pencil-square"></i>
                  </a> --}}
              @endif
          </td>
      </tr>
      {{-- @endif --}}
      @endforeach
    </tbody>
  </table>
</div>

@endsection