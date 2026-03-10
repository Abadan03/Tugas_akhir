<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use App\Models\Barang;
use App\Models\Pinjaman;
use App\Models\BarangRusak;
use App\Models\Pembayaran;
use App\Models\itemStatusLog;

use Illuminate\Pagination\Paginator;

class UserController extends Controller
{
    //

    public function homepage() {
        // Ambil semua data barang
        Paginator::useBootstrap(); // Tambahkan ini
        // $barang = Barang::all();
        // $barang = Barang::paginate(20);
        // Ambil barang + relasinya
        $barang = \App\Models\Barang::with(['kategori', 'status', 'tipe'])->paginate(20);

        // Hitung total barang
        $totalBarang = Barang::count();

        // Hitung barang yang butuh perbaikan (status = 2 = Rusak Ringan)
        $barangPerbaikan = Barang::where('status_id', [4])->count();

        // Hitung barang yang butuh diganti (status = 3 = Rusak)
        $butuhDiganti = Barang::where('status_id', 3)->count();
        // $butuhDiganti = Barang::whereNotIn('status', [0, 4])->count();


        // Hitung barang yang dipinjamkan (kategori = 1 = Dipinjam oleh siswa)
        $dipinjamkan = Barang::where('kategori_id', 1)->count();

        // return view('admin.index', compact(
        //     'barang',
        //     'totalBarang',
        //     'barangPerbaikan',
        //     'butuhDiganti',
        //     'dipinjamkan'
        // ));

        // Ambil seluruh data master
        $categories = \App\Models\CategoryMaster::get();
        $statuses   = \App\Models\StatusMaster::get();
        $types      = \App\Models\TypeMaster::get();

        // Hitung total barang (opsional)
        // $totalBarang = \App\Models\Barang::count();
        
        return view('admin.index', compact('barang', 'categories', 'statuses', 'types', 'totalBarang'));
    
    }


    public function details(string $id) 
    {
        $barang = Barang::findOrFail($id);
        
          // Ambil barang rusak yang berhubungan dengan barang ini
        $barangRusaks = BarangRusak::where('barang_id', $id)->with('barang')->get();
        

        // Ambil pembayaran yang terkait barang ini melalui barang rusak
        $pembayaran = Pembayaran::whereHas('barangRusak', function ($query) use ($id) {
            $query->where('barang_id', $id);
        })->with('barangRusak.barang')->first();
        // return dd($pembayaran);

        $history = itemStatusLog::where('barang_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.details', compact('barang', 'pembayaran', 'history'));

    }
}
