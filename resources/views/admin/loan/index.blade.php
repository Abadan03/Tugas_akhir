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
    <input type="text" id="search" class="form-control border" style="max-width: 400px;" placeholder="Search Nama Barang, Keterangan, Kategori...">
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
    <a href="{{ route('qrcode.render', ['from' => 'peminjaman']) }}">
      <button class="btn btn-darkblue text-white ">Scan Kode QR</button>
    </a>
    <a href="">
      <button class="btn btn-lightgreen text-white">Import CSV</button>
    </a>
    {{-- <a href="">
      <button class="btn btn-grey text-white">Download All</button>
    </a> --}}
  </div>
</div>

<div class="container-fluid">
  <table id="example" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama barang</th>
            <th>Kategori</th>
            <th>Peminjam</th>
            <th>Tipe</th>
            <th>Status</th>
            <th>Keterangan</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
      @forelse ($data as $index => $item)
      {{-- @if ($item->barang->status === "Baru") --}}
       {{-- {{ $item->status }} --}}
       {{-- {{ $item->barang->tipe }} --}}
      <tr>
          <td>{{ $data->firstItem() + $index }}</td> {{-- Nomor urut global --}}
          <td>{{ $item->nama_barang }}</td>
          <td>{{ $item->kategori->nama_kategori ?? ''}}</td>
          <td>{{ $item->peminjam ?? '-' }}</td>
          <td>{{ $item->tipe->nama_tipe ?? '' }}</td>
          <td>
            {{ $item->status->nama_status ?? '' }}
          </td>
          <td>
            {{ $item->keterangan ?? '-' }}
          </td>

          <td class="text-nowrap">
            <div class="d-flex gap-1">
              <a href="{{ route('peminjaman.show', $item->id) }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center">
                <i class="bi bi-eye me-1"></i> Detail
              </a>

              @if (in_array(optional($item->status)->id, [1, 5]))
                <a href="{{ route('peminjaman.edit', $item->id) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center">
                  <i class="bi bi-pencil-square me-1"></i> Edit
                </a>
              @endif

              <button type="button" onclick="deleteItem('{{ route('inventaris.destroy', $item->id) }}')" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center">
                <i class="bi bi-trash me-1"></i> Hapus
              </button>
            </div>
          </td>
         
          {{-- <td class="d-flex gap-2">
              <a href="{{ route('peminjaman.show', $item->id) }}" class="text-black">
                  <i class="bi bi-eye"></i>
              </a>
              @if (in_array(optional($item->status)->id, [1, 5]))

                  <a href="{{ route('peminjaman.edit', $item->id) }}" class="text-black">
                      <i class="bi bi-pencil-square"></i>
                  </a>
              @endif
          </td> --}}

          <tr id="no-results" style="display: none;">
            <td colspan="9" class="text-center py-4">
              <i class="bi bi-search-heart mb-2 d-block fs-2 text-muted"></i>
              <span class="text-muted fw-medium">Barang yang anda cari tidak ada di sini...</span>
            </td>
          </tr>
      </tr>

      @empty
        {{-- Jika data kosong --}}
            <div class="alert alert-info my-2">
              Tidak ada data tersedia.
            </div>
      @endforelse
      {{-- @endif --}}
    </tbody>
  </table>

  <div class="d-flex justify-content-end mt-3">
          {{ $data->links('pagination::bootstrap-5') }}
  </div>
</div>

<script>
  // console.log(first)
$(document).ready(function() {
    $("#search").on("keyup", function () {
      var value = $(this).val().toLowerCase();
      let matchCount = 0;

      $("#select-all").prop("checked", false);
      $("input[name='selected_items[]']").prop("checked", false);

      $("#example tbody tr").not('#no-results').each(function () {
        const match = $(this).text().toLowerCase().indexOf(value) > -1;
        $(this).toggle(match);
        if (match) matchCount++;
      });

      if (matchCount === 0 && value !== "") {
        $("#no-results").show();
      } else {
        $("#no-results").hide();
      }

      $("#no-results").toggle(matchCount === 0);
      updateNomorUrut(); 
    });
  });

</script>

@endsection


