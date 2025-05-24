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
        // $keterangan = BarangRusak::findOrFail();
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

        // if($request->)
        $validator = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'harga_awal' => 'required|numeric',
            'kodeQR' => 'required|string|max:255',
            'keterangan' => 'required|string|max:255',
            // 'bukti' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        // return dd($request->all(), $request->file('surat'));
        // return dd($request->$id);
        // Log::info('ini data', [$request->surat]);

        $path = null;
        if ($request->hasFile('surat')) {
            $file = $request->file('surat');
            $imagePath = Storage::disk('public')->put('surat', $file);

            $path = $file->storeAs(
                'images', // Direktori target di disk 'public'
                $file->getClientOriginalName(), // Nama file asli
                'public' // Disk 'public'
            ); 
        }
        
        if ($request->status !== 0 && $path) {
            BarangRusak::create([
                'barang_id' => $id,
                'pinjaman_id' => $id,
                // 'keterangan' => $request->keterangan,
                'surat' => $path,
            ]);

            $barang = Barang::findOrFail($id);

            $barang->status = $request->status;
            $barang->keterangan = $request->keterangan;
            $barang->save();

            // if($barang) {
            //     $barang->keterangan = $request->keterangan;
            //     $barang->save();
            // }
        }

        // Barang::create()

        return redirect()->route('peminjaman.index')->with('success', 'Update status telah dibarui' . $path);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
