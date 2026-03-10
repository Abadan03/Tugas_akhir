<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryMaster;

class KategoriController extends Controller
{
    //
    public function index()
    {
        $categories = CategoryMaster::orderBy('id', 'desc')->paginate(10);
        $categories->getCollection()->transform(function ($category) {
            $category->has_barangs = \App\Models\Barang::where('kategori_id', $category->id)->exists();
            return $category;
        });
        return view('admin.masters.kategori.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.masters.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

         // Ambil kode terakhir
        $lastKode = \App\Models\CategoryMaster::orderBy('id', 'desc')->value('kode');

        // Tentukan prefix (KTG untuk kategori)
        $prefix = 'KTG';

        // Dapatkan nomor terakhir dari kode sebelumnya
        $lastNumber = 0;
        if ($lastKode) {
            // Pisahkan berdasarkan tanda hubung, contoh: KTG-03
            $lastNumber = (int) substr($lastKode, strrpos($lastKode, '-') + 1);
        }

        // Generate kode baru dengan increment
        $newKode = sprintf('%s-%02d', $prefix, $lastNumber + 1);

        CategoryMaster::create([
            'nama_kategori' => $request->nama_kategori,
            'kode' => $newKode,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.category_masters.index')
                         ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $category = CategoryMaster::findOrFail($id);
        return view('admin.masters.kategori.create', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $category = CategoryMaster::findOrFail($id);
        $category->update([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.category_masters.index')
                         ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $category = CategoryMaster::findOrFail($id);

        // Cek data default sistem — tidak boleh dihapus
        if ($category->is_default) {
            return redirect()->route('admin.category_masters.index')
                             ->with('error', 'Kategori ini adalah data default sistem dan tidak dapat dihapus.');
        }

        // Cek keterkaitan: jika kategori masih dipakai barang, tolak penghapusan
        if (\App\Models\Barang::where('kategori_id', $category->id)->exists()) {
            return redirect()->route('admin.category_masters.index')
                             ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh data barang.');
        }

        $category->delete();

        return redirect()->route('admin.category_masters.index')
                         ->with('success', 'Kategori berhasil dihapus.');
    }
}
