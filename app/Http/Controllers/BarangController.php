<?php

namespace App\Http\Controllers;

use App\Models\ItemMasters;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    //
     public function index()
    {
        // paginasi agar konsisten dengan view lain
        $items = ItemMasters::orderBy('id', 'desc')->paginate(15);

        // view: resources/views/admin/masters/item/index.blade.php (sesuaikan)
        return view('admin.masters.barang.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.masters.barang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
        ]);

        // ItemMasters::create($request->only('nama_barang', 'deskripsi'));
        ItemMasters::create([
            'nama_barang' => $request->nama_barang,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('items_masters.index')
                         ->with('success', 'Item master berhasil ditambahkan.');
    }
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'nama_kategori' => 'required|string|max:255',
    //         'deskripsi' => 'nullable|string',
    //     ]);

    //     CategoryMaster::create([
    //         'nama_kategori' => $request->nama_kategori,
    //         'deskripsi' => $request->deskripsi,
    //     ]);

    //     return redirect()->route('admin.category_masters.index')
    //                      ->with('success', 'Kategori berhasil ditambahkan.');
    // }

    /**
     * Display the specified resource.
     */
    public function show(ItemMasters $itemMaster)
    {
        // opsional — kalau tidak perlu, boleh kosong atau redirect ke edit/detail
        return view('admin.masters.barang.show', ['item' => $itemMaster]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ItemMasters $itemMaster)
    {
        return view('admin.masters.barang.edit', ['item' => $itemMaster]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItemMasters $itemMaster)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
        ]);

        $itemMaster->update($request->only('nama_barang', 'deskripsi'));

        return redirect()->route('items_masters.index')
                         ->with('success', 'Item master berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemMasters $itemMaster)
    {
        $itemMaster->delete();

        return redirect()->route('items_masters.index')
                         ->with('success', 'Item master berhasil dihapus.');
    }
}
