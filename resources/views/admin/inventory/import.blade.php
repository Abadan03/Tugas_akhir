@extends('layouts.app')

@section('content')

<div class="container my-5">
    <h4>Import Data Barang (CSV)</h4>

    {{-- Alert sukses dan error --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tombol Unduh Template --}}
    <a href="{{ route('import.csv.template') }}" class="btn btn-success mb-3">
        📥 Unggah Template CSV
    </a>

    {{-- Tabel Referensi Data Master --}}
    <div class="card mb-4">
        <div class="card-header bg-light fw-bold">Referensi Data Master</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h6>Kategori</h6>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr><th>ID</th><th>Nama</th></tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $cat)
                                <tr><td>{{ $cat->id }}</td><td>{{ $cat->nama_kategori }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-md-3">
                    <h6>Tipe</h6>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr><th>ID</th><th>Nama</th></tr>
                        </thead>
                        <tbody>
                            @foreach($types as $t)
                                <tr><td>{{ $t->id }}</td><td>{{ $t->nama_tipe }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-md-3">
                    <h6>Status</h6>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr><th>ID</th><th>Nama</th></tr>
                        </thead>
                        <tbody>
                            @foreach($statuses as $s)
                                <tr><td>{{ $s->id }}</td><td>{{ $s->nama_status }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-md-3">
                    <h6>Item Master</h6>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr><th>ID</th><th>Nama</th></tr>
                        </thead>
                        <tbody>
                            @foreach($items as $i)
                                <tr><td>{{ $i->id }}</td><td>{{ $i->nama_barang }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Upload CSV --}}
    <form action="{{ route('import.csv.handle') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="csv_file" class="form-label">Upload File CSV</label>
            <input type="file" name="csv_file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-lightgreen text-white">Import</button>
        <a href="{{ route('inventaris.index') }}" class="btn btn-secondary">Batal</a>
    </form>

</div>
@endsection
