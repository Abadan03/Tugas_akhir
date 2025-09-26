@extends('layouts.app')

@section('content')
  
  @include('layouts.flash-message')
  <div class="py-4 px-3">
    <h2 class="fw-bold">Dashboard</h2>
    <h4 class="fw-semibold mb-4">Overall Inventory</h4>

    <div class="row text-start">
        <div class="col-md-3 border-end">
            <p class="text-primary fw-semibold fs-6 mb-1">Total Barang</p>
            <h5 class="fw-bold mb-1">{{ $totalBarang }}</h5>
            <p class="text-muted small">Total {{ $totalBarang }} barang</p>
        </div>
        <div class="col-md-3 border-end">
            <p class="fw-semibold fs-6 mb-1" style="color: #D77D11;">Barang Perbaikan</p>
            <h5 class="fw-bold mb-1">{{ $barangPerbaikan }}</h5>
            <p class="text-muted small">Total {{ $barangPerbaikan }} Barang Perbaikan</p>
        </div>
        <div class="col-md-3 border-end">
            <p class="fw-semibold fs-6 mb-1" style="color: #A249C0;">Butuh Diganti</p>
            <h5 class="fw-bold mb-1">{{ $butuhDiganti }}</h5>
            <p class="text-muted small">Total {{ $butuhDiganti }} barang perlu diganti</p>
        </div>
        <div class="col-md-3">
            <p class="fw-semibold fs-6 mb-1" style="color: #FF5A5F;">Total Barang yang Dipinjamkan</p>
            <h5 class="fw-bold mb-1">{{ $dipinjamkan }}</h5>
            <p class="text-muted small">Total {{ $dipinjamkan }} barang milik siswa</p>
        </div>
    </div>

    <div>
      <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nama barang</th>
                {{-- <th>Product ID</th> --}}
                <th>Nama Siswa</th>
                <th>Kategori</th>
                <th>Tipe</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($barang as $item)
                <tr>
                    <td>{{ $no++ }}</td> {{-- ID frontend --}}
                    <td>{{ $item->nama_barang }}</td>
                    {{-- <td>{{ $item->nama_barang }}</td> --}}
                    <td>{{ $item->nama_siswa ?: '-' }}</td>
                    {{-- <td>{{ $item->product_id }}</td> --}}
                    <td>{{ $item->kategori == 1 ? 'Dipinjam oleh siswa' : 'Milik Sekolah' }}</td>
                    <td>{{ $item->tipe == 0 ? 'Barang Tetap' : 'Barang Berpindah' }}</td>
                    <td>
                        @switch($item->status)
                            @case(0) Baru @break
                            @case(1) Hilang @break
                            @case(2) Rusak Ringan @break
                            @case(3) Rusak @break
                            @case(4) Diperbarui @break
                            @default - @break
                        @endswitch
                    </td>
                    <td>
                        <a href="{{ route('dashboard.details', $item->id) }}" class="btn btn-sm btn-primary">Details</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            {{-- <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Age</th>
                <th>Start date</th>
                <th>Salary</th>
            </tr> --}}
        </tfoot>
    </table>
    <div class="d-flex justify-content-end mt-3">
          {{ $barang->links('pagination::bootstrap-5') }}
    </div>
    
    </div>
  </div>

@endsection