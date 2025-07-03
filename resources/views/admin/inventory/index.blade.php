@extends('layouts.app')

@section('content')

{{-- <div class="container-fluid d-flex justify-content-between align-items-center py-3 px-5 border-3 border-bottom rounded-3">
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
  <h2 class="fw-bold">Inventaris</h2>
  <h4 class="fw-semibold mb-4">Keseluruhan inventaris</h4>
</div>


<div class="container-fluid d-flex justify-content-between align-items-center">
  <div>
    <h1>Inventaris</h1>
  </div>
  <div>
    <a href="">
      <button class="btn btn-skyblue">Print Kode QR</button>
    </a>
    <a href="{{ route('qrcode.render', ['from' => 'inventaris']) }}">
      <button class="btn btn-darkblue text-white ">Scan Kode QR</button>
    </a>
    <a href="">
      <button class="btn btn-lightgreen text-white">Import CSV</button>
    </a>
    <a href="{{ route('inventaris.create') }}">
      <button class="btn btn-skyblue">Tambah Barang</button>
    </a>
  </div>
</div>

<div class="container-fluid">
  <table id="example" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>Nama barang</th>
            <th>Kategori</th>
            <th>Tipe</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
      @foreach ($data as $item)
      <tr>
          <td>{{ $item->nama_barang }}</td>
          <td>
            @if ($item->kategori == 1)
              Dipinjam oleh siswa
            @elseif ($item->kategori == 0)
              Milik Sekolah
            @endif
          </td>
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
          <td class="d-flex gap-2">
              <a href="{{ route('inventaris.show', $item->id) }}" class="text-black">
                  <i class="bi bi-eye"></i>
              </a>
              <form action="{{ route('inventaris.destroy', $item->id) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button type="submit" style="border: none; background: none;" class="text-black">
                      <i class="bi bi-trash"></i>
                  </button>
              </form>
              @if ($item->status == 0 )
                @if ($item->kategori != 1)
                  <a href="{{ route('inventaris.edit', $item->id) }}" class="text-black">
                      <i class="bi bi-pencil-square"></i>
                  </a>
                @endif
              @elseif ($item->status == 4 && $item->kategori != 1) 
                  <a href="{{ route('inventaris.edit', $item->id) }}" class="text-black">
                      <i class="bi bi-pencil-square"></i>
                  </a>
              @endif
          </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div> --}}

<div class="container-fluid d-flex justify-content-between align-items-center py-3 px-5 border-3 border-bottom rounded-3">
  <div class="input-group">
    <span class="input-group-text bg-white border-0">
      <i class="bi bi-search text-muted"></i>
    </span>
    <input type="text" id="search" class="form-control border" style="max-width: 400px;" placeholder="Search nama barang, produk id, kategori">
  </div>
  <div>
    @if (Auth()->user())
        {{ Auth()->user()->name }}
    @endif
  </div>
</div>
@include('layouts.flash-message')

<div class="py-4 px-3">
  <h2 class="fw-bold">Inventaris</h2>
  <h4 class="fw-semibold mb-4">Keseluruhan inventaris</h4>
</div>



<form method="POST" action="{{ route('inventaris.exportPDF') }}">
  @csrf

  <div class="container-fluid d-flex justify-content-between align-items-center">
    <div>
      <h1>Inventaris</h1>
    </div>
    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-grey text-white">Download</button>
      <a href="{{ route('qrcode.render', ['from' => 'inventaris']) }}">
        <button type="button" class="btn btn-darkblue text-white">Scan Kode QR</button>
      </a>
      <a href="">
        <button type="button" class="btn btn-lightgreen text-white">Import CSV</button>
      </a>
      <a href="{{ route('inventaris.create') }}">
        <button type="button" class="btn btn-skyblue">Tambah Barang</button>
      </a>
    </div>
  </div>

  {{-- PINDAHKAN TABLE KE DALAM FORM --}}
  <div class="container-fluid mt-3">
    <table id="example" class="table table-striped" style="width:100%">
      <thead>
        <tr>
          <th>No</th>
          <th><input type="checkbox" id="select-all"></th>
          <th>Nama barang</th>
          <th>Kategori</th>
          <th>Nama Siswa</th>
          <th>Tipe</th>
          <th>Status</th>
          <th>Keterangan</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($data as $item)
        <tr>
          <td class="nomor-urut"></td>
          <td><input type="checkbox" name="selected_items[]" value="{{ $item->id }}"></td>
          <td>{{ $item->nama_barang }}</td>
          <td>{{ $item->kategori == 1 ? 'Dipinjam oleh siswa' : 'Milik Sekolah' }}</td>
          <td>{{ $item->nama_siswa ?: '-' }}</td>
          <td>{{ $item->tipe == 0 ? 'Barang Tetap' : 'Barang Berpindah' }}</td>
          <td>
            @switch($item->status)
              @case(0) Baru @break
              @case(1) Hilang @break
              @case(2) Rusak Ringan @break
              @case(3) Rusak @break
              @case(4) Diperbarui @break
            @endswitch
          </td>
          <td>{{ $item->keterangan ?: '-' }}</td>
          <td class="d-flex gap-2">
                <a href="{{ route('inventaris.show', $item->id) }}" class="text-black">
                    <i class="bi bi-eye"></i>
                </a>
                {{-- <form action="{{ route('inventaris.destroy', $item->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="border: none; background: none;" class="text-black">
                        <i class="bi bi-trash"></i>
                    </button>
                </form> --}}
                <button type="button" onclick="deleteItem('{{ route('inventaris.destroy', $item->id) }}')" style="border:none; background:none;">
                  <i class="bi bi-trash"></i>
                </button>
                @if (($item->status == 0 || $item->status == 4) && $item->kategori != 1)
                  <a href="{{ route('inventaris.edit', $item->id) }}" class="text-black">
                      <i class="bi bi-pencil-square"></i>
                  </a>
                @endif
            </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    
  </div>
</form> {{-- <- FORM DITUTUP DI SINI --}}

<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
  function deleteItem(url) {
    const form = document.getElementById('delete-form');
    form.action = url;
    if (confirm('Yakin ingin menghapus data ini?')) {
      form.submit();
    }
  }
</script>
<script>
  // document.getElementById('select-all').addEventListener('click', function() {
  //   const checkboxes = document.querySelectorAll('input[name="selected_items[]"]');
  //   checkboxes.forEach(checkbox => checkbox.checked = this.checked);
  // });

  // $('#select-all').on('click', function () {
  //   const isChecked = $(this).is(':checked');
  //   $('#example tbody tr:visible input[name="selected_items[]"]').prop('checked', isChecked);
  // });

  $('#select-all').on('click', function () {
    const isChecked = $(this).is(':checked');
    
    // Select only checkboxes in visible rows
    const visibleCheckboxes = $('#example tbody tr:visible input[name="selected_items[]"]');
    visibleCheckboxes.prop('checked', isChecked);

    // Logging berapa banyak yang dicentang
    // Tampilkan ID yang dicentang
    if (isChecked) {
      const selectedIds = visibleCheckboxes.map(function () {
        return $(this).val();
      }).get();

      console.log("ID yang dicentang:", selectedIds); // Akan menampilkan array [1, 3, 5, ...]
    } else {
      console.log("Tidak ada data yang dicentang");
    }
  });

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

      $("#no-results").toggle(matchCount === 0);
      updateNomorUrut(); 
    });
  });

  function updateNomorUrut() {
    $('#example tbody tr:visible').each(function(index) {
      $(this).find('td.nomor-urut').text(index + 1);
    });
  }

  $(document).ready(function () {
    updateNomorUrut();
  });


</script>


@endsection