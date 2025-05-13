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
    <h2 class="fw-bold">Dashboard</h2>
    <h4 class="fw-semibold mb-4">Overall Inventory</h4>

    <div class=" p-4 rounded-3" >
      <div class="row text-start">
        <div class="col-md-3 border-end">
            <p class="text-primary fw-semibold fs-6 mb-1">Total Barang</p>
            <h5 class="fw-bold mb-1">868</h5>
            <p class="text-muted small">Total 868 barang</p>
        </div>
        <div class="col-md-3 border-end">
            <p class="fw-semibold fs-6 mb-1" style="color: #D77D11;">Butuh Perbaikan</p>
            <h5 class="fw-bold mb-1">14</h5>
            <p class="text-muted small">Total 14 barang butuh perbaikan</p>
        </div>
        <div class="col-md-3 border-end">
            <p class="fw-semibold fs-6 mb-1" style="color: #A249C0;">Butuh Diganti</p>
            <h5 class="fw-bold mb-1">5</h5>
            <p class="text-muted small">Total 5 barang perlu diganti</p>
        </div>
        <div class="col-md-3">
            <p class="fw-semibold fs-6 mb-1" style="color: #FF5A5F;">Total Barang yang Dipinjamkan</p>
            <h5 class="fw-bold mb-1">420</h5>
            <p class="text-muted small">Total 420 barang milik siswa</p>
        </div>
      </div>
    </div>

    <div>
      <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th>Nama barang</th>
                <th>Product ID</th>
                <th>Kategori</th>
                <th>Tipe</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Lemari</td>
                <td>23567</td>
                <td>non pemilik</td>
                <td>Barang Tetap</td>
                <td>Baik</td>
            </tr>
            {{-- <tr>
                <td>Garrett Winters</td>
                <td>Accountant</td>
                <td>Tokyo</td>
                <td>63</td>
                <td>2011-07-25</td>
                <td>$170,750</td>
            </tr>
            <tr>
                <td>Ashton Cox</td>
                <td>Junior Technical Author</td>
                <td>San Francisco</td>
                <td>66</td>
                <td>2009-01-12</td>
                <td>$86,000</td>
            </tr>
            <tr>
                <td>Cedric Kelly</td>
                <td>Senior Javascript Developer</td>
                <td>Edinburgh</td>
                <td>22</td>
                <td>2012-03-29</td>
                <td>$433,060</td>
            </tr>
            <tr>
                <td>Airi Satou</td>
                <td>Accountant</td>
                <td>Tokyo</td>
                <td>33</td>
                <td>2008-11-28</td>
                <td>$162,700</td>
            </tr>
            <tr>
                <td>Brielle Williamson</td>
                <td>Integration Specialist</td>
                <td>New York</td>
                <td>61</td>
                <td>2012-12-02</td>
                <td>$372,000</td>
            </tr> --}}
            
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
    </div>
  </div>

@endsection