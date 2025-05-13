<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\Log;

use App\Models\Pinjaman;
use App\Models\Barang;
use App\Models\BarangRusak;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = Pinjaman::with('barang')->get();
        // return dd($data);

        return view('admin.loan.index', compact('data'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        // $data = Pinjaman::with('barang')->get();
        $barang = Barang::findOrFail($id);

        // return dd($data);

        return view('admin.loan.edit', compact('barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $input = $request->all();
        $validator = Validator::make($input, [
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'harga_awal' => 'required|numeric',
            'kodeQR' => 'required|string|max:255',
            'bukti' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        
        // $create_barangrusak = new BarangRusak();
        
        if ($request->status !== "Baik") {
            BarangRusak::create([
                'barang_id' => $id,
                'pinjaman_id' => $id,
                'keterangan' => $request->keterangan,
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
