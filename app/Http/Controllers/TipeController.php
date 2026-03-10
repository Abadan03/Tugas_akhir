<?php

namespace App\Http\Controllers;

use App\Models\TypeMaster;
use Illuminate\Http\Request;

class TipeController extends Controller
{
    //
    public function index()
    {
        $types = TypeMaster::orderBy('id', 'desc')->paginate(10);
        $types->getCollection()->transform(function ($type) {
            $type->has_barangs = \App\Models\Barang::where('tipe_id', $type->id)->exists();
            return $type;
        });
        return view('admin.masters.tipe.index', compact('types'));
    }

    public function create()
    {
        return view('admin.masters.tipe.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tipe' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $lastKode = \App\Models\TypeMaster::orderBy('id', 'desc')->value('kode');
        $prefix = 'TYP';
        $lastNumber = $lastKode ? (int) substr($lastKode, strrpos($lastKode, '-') + 1) : 0;
        $newKode = sprintf('%s-%02d', $prefix, $lastNumber + 1);

        TypeMaster::create([
            'nama_tipe' => $request->nama_tipe,
            'kode' => $newKode,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('type_masters.index')
                         ->with('success', 'Tipe berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $type = TypeMaster::findOrFail($id);
        return view('admin.masters.tipe.create', compact('type'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_tipe' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $type = TypeMaster::findOrFail($id);
        $type->update([
            'nama_tipe' => $request->nama_tipe,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('type_masters.index')
                         ->with('success', 'Tipe berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $type = TypeMaster::findOrFail($id);

        // Cek data default sistem — tidak boleh dihapus
        if ($type->is_default) {
            return redirect()->route('type_masters.index')
                             ->with('error', 'Tipe ini adalah data default sistem dan tidak dapat dihapus.');
        }

        // Cek keterkaitan: jika tipe masih dipakai barang, tolak penghapusan
        if (\App\Models\Barang::where('tipe_id', $type->id)->exists()) {
            return redirect()->route('type_masters.index')
                             ->with('error', 'Tipe tidak dapat dihapus karena masih digunakan oleh data barang.');
        }

        $type->delete();

        return redirect()->route('type_masters.index')
                         ->with('success', 'Tipe berhasil dihapus.');
    }
}
