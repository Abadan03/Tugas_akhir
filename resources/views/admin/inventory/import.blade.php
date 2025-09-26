@extends('layouts.app')


@section('content')

<div class="container">
    <h4>Import Data Barang (CSV)</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
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

    <form action="{{ route('import.csv.handle') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="csv_file" class="form-label">Upload File CSV</label>
            <input type="file" name="csv_file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-lightgreen text-white">Import</button>
        <a href="{{ route('inventaris.index') }}" class="btn btn-secondary">Batal</a>
    </form>

    <hr>
    <p><strong>Format CSV yang diterima:</strong></p>
    <pre>
nama_barang,kategori,nama_siswa,tipe,status,harga_awal,kodeQR
Kasur,0,,1,0,500000,
Meja,1,Ahmad,1,1,300000,MEJA123
    </pre>
</div>

@endsection