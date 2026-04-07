@extends('layouts.app')

@section('content')


<div class="container-fluid d-flex justify-content-between align-items-center py-3 px-5 border-3 border-bottom rounded-3">
  <div class="input-group">
    <span class="input-group-text bg-white border-0">
      <i class="bi bi-search text-muted"></i>
    </span>
    <input type="text" id="search" class="form-control border" style="max-width: 400px;" placeholder="Search Nama Barang, Keterangan, Kategori...">
    
    {{-- <form method="GET" action="{{ route('inventaris.cari') }}">
      <button type="submit">
        
      </button>
    </form> --}}
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
      <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#masterModal">
        Kelola Data Master
      </button>
      <a href="{{ route('qrcode.render', ['from' => 'inventaris']) }}">
        <button type="button" class="btn btn-darkblue text-white">Scan Kode QR</button>
      </a>
      <a href="{{ route('import.csv') }}">
        <button type="button" class="btn btn-lightgreen text-white">Import CSV</button>
      </a>
      <a href="{{ route('inventaris.create') }}">
        <button type="button" class="btn btn-skyblue">Tambah Barang</button>
      </a>
    </div>
  </div>

  <!-- Modal Kelola Data Master -->
<div class="modal fade" id="masterModal" tabindex="-1" aria-labelledby="masterModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="masterModalLabel">Kelola Data Master</h5>
        <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body d-flex flex-column gap-3">
        
        <a href="{{ route('kategori.index') }}" class="btn btn-outline-primary w-100">
          <i class="bi bi-folder-plus me-2"></i> Tambah / Lihat Kategori Master
        </a>
        <a href="{{ route('tipe.index') }}" class="btn btn-outline-success w-100">
          <i class="bi bi-diagram-3 me-2"></i> Tambah / Lihat Tipe Master
        </a>
        <a href="{{ route('status.index') }}" class="btn btn-outline-warning w-100">
          <i class="bi bi-gear-fill me-2"></i> Tambah / Lihat Status Master
        </a>
        <a href="{{ route('items_masters.index') }}" class="btn btn-outline-dark w-100">
          <i class="bi bi-box-seam me-2"></i> Tambah / Lihat Barang Master
        </a>
      </div>
    </div>
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
          <th>Peminjam</th>
          <th>Tipe</th>
          <th>Status</th>
          <th>Keterangan</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($data as $index => $item)
        <tr>
          <td>{{ $data->firstItem() + $index }}</td> {{-- Nomor urut global --}}
          <td><input type="checkbox" name="selected_items[]" value="{{ $item->id }}"></td>
          <td>{{ $item->nama_barang }}</td>
          {{-- <td>{{ $item->kategori == 1 ? 'Dipinjam oleh siswa' : 'Milik Sekolah' }}</td> --}}
          <td>{{ $item->kategori->nama_kategori ?? ''}}</td>
          <td>{{ $item->peminjam ?? '-' }}</td>
          {{-- <td>{{ $item->tipe == 0 ? 'Barang Tetap' : 'Barang Berpindah' }}</td> --}}
          <td>{{ $item->tipe->nama_tipe ?? '' }}</td>
          @if ($item->status->nama_status === 'Baru')
              <td class="bg-primary text-white">
                  {{ $item->status->nama_status ?? '' }}
              </td>
          @elseif (in_array($item->status->nama_status, ['Hilang', 'Rusak', 'Rusak Ringan', 'Rusak Berat']))
              {{-- Beri warna merah (bg-danger) untuk barang bermasalah --}}
              <td class="bg-danger text-white">
                  {{ $item->status->nama_status ?? '' }}
              </td>
          @else
              {{-- Beri warna abu-abu atau biarkan kosong untuk status lainnya --}}
              <td class="bg-secondary text-white">
                  {{ $item->status->nama_status ?? '' }}
              </td>
          @endif
          {{-- <td class="">
            {{ $item->status->nama_status ?? '' }}
          </td> --}}
          <td>{{ $item->keterangan ?: '-' }}</td>
          <td class="text-nowrap">
            <div class="d-flex gap-1">
              <a href="{{ route('inventaris.show', $item->id) }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center">
                <i class="bi bi-eye me-1"></i> Detail
              </a>

              @if (in_array(optional($item->status)->id, [1, 5]) && optional($item->kategori)->id == 1)
                <a href="{{ route('inventaris.edit', $item->id) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center">
                  <i class="bi bi-pencil-square me-1"></i> Edit
                </a>
              @endif

              <button type="button" onclick="deleteItem('{{ route('inventaris.destroy', $item->id) }}')" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center">
                <i class="bi bi-trash me-1"></i> Hapus
              </button>
            </div>
          </td>
        </tr>
        @empty
          <tr>
            <td colspan="9" class="text-center">Tidak ada data tersedia.</td>
          </tr>
        @endforelse
        <tr id="no-results" style="display: none;">
          <td colspan="9" class="text-center py-4">
            <i class="bi bi-search-heart mb-2 d-block fs-2 text-muted"></i>
            <span class="text-muted fw-medium">Barang yang anda cari tidak ada di sini...</span>
          </td>
        </tr>
      </tbody>

      

    </table>
    <div class="d-flex justify-content-end mt-3">
          {{ $data->links('pagination::bootstrap-5') }}
    </div>
    
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

      if (matchCount === 0 && value !== "") {
        $("#no-results").show();
      } else {
        $("#no-results").hide();
      }

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