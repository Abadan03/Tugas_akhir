<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Log;

use App\Models\Barang;
use App\Models\Pinjaman;


class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = Barang::all(); // ambil semua data
        return view('admin.inventory.index', compact('data')); // kirim ke view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.inventory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'harga_awal' => 'required|numeric',
            'kodeQR' => 'required|string|max:255',
            'bukti' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            // 'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $barang = new Barang();
        

        $path = null;
        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $imagePath = Storage::disk('public')->put('bukti_pembelian', $file);

            $path = $file->storeAs(
                'images', // Direktori target di disk 'public'
                $file->getClientOriginalName(), // Nama file asli
                'public' // Disk 'public'
            ); 
        }

        $barang->nama_barang = $request->nama_barang;
        $barang->kategori = $request->kategori;
        $barang->nama_siswa = $request->nama_siswa;
        $barang->tipe = $request->tipe;
        $barang->status = $request->status;
        $barang->harga_awal = $request->harga_awal;
        $barang->kodeQR = $request->kodeQR;
        $barang->bukti = $path;
        $barang->save();

        if ($request->kategori === 'dipinjam') {
            Pinjaman::create([
                // 'user_id' => $request->user_id,
                'barang_id' => $barang->id,
            ]);
        }


        return redirect()->route('inventaris.index')->with('success', 'Barang berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    public function show(string $id)
    {
        //
        return view('admin.inventory.details');
    }

    public function details(string $id) 
    {
        $barang = Barang::findOrFail($id);
        return view('admin.inventory.details', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $barang = Barang::findOrFail($id);
        return view('admin.inventory.edit', compact('barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $barang = Barang::findOrFail($id);

    $validatedData = $request->validate([
        'nama_barang' => 'required|string|max:255',
        'kategori' => 'required|string|max:255',
        'tipe' => 'required|string|max:255',
        'status' => 'required|string|max:255',
        'harga_awal' => 'required|numeric',
        'kodeQR' => 'required|string|max:255',
        'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $barang->nama_barang = $request->nama_barang;
    $barang->kategori = $request->kategori;
    $barang->tipe = $request->tipe;
    $barang->status = $request->status;
    $barang->harga_awal = $request->harga_awal;
    $barang->kodeQR = $request->kodeQR;

    // Handle file upload
    if ($request->hasFile('bukti')) {
        // Hapus file lama jika ada
        if ($barang->bukti && Storage::disk('public')->exists($barang->bukti)) {
            Storage::disk('public')->delete($barang->bukti);
        }

        $file = $request->file('bukti');
        $path = $file->storeAs(
            'images',
            $file->getClientOriginalName(),
            'public'
        );
        $barang->bukti = $path;
    }

    $barang->save();

    return redirect()->route('inventaris.index')->with('success', 'Data barang berhasil diperbarui');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
