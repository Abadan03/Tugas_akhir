<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StatusMaster;

class StatusController extends Controller
{
    //
    public function index()
    {
        $statuses = StatusMaster::orderBy('id', 'desc')->paginate(10);
        $statuses->getCollection()->transform(function ($status) {
            $status->has_barangs = \App\Models\Barang::where('status_id', $status->id)->exists();
            return $status;
        });
        return view('admin.masters.status.index', compact('statuses'));
    }

    public function create()
    {
        return view('admin.masters.status.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_status' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $lastKode = \App\Models\StatusMaster::orderBy('id', 'desc')->value('kode');
        $prefix = 'STS';
        $lastNumber = $lastKode ? (int) substr($lastKode, strrpos($lastKode, '-') + 1) : 0;
        $newKode = sprintf('%s-%02d', $prefix, $lastNumber + 1);

        StatusMaster::create([
            'nama_status' => $request->nama_status,
            'kode' => $newKode,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('status_masters.index')
                         ->with('success', 'Status berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $status = StatusMaster::findOrFail($id);
        return view('admin.masters.status.create', compact('status'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_status' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $status = StatusMaster::findOrFail($id);
        $status->update([
            'nama_status' => $request->nama_status,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('status_masters.index')
                         ->with('success', 'Status berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $status = StatusMaster::findOrFail($id);

        // Cek data default sistem — tidak boleh dihapus
        if ($status->is_default) {
            return redirect()->route('status_masters.index')
                             ->with('error', 'Status ini adalah data default sistem dan tidak dapat dihapus.');
        }

        // Cek keterkaitan: jika status masih dipakai barang, tolak penghapusan
        if (\App\Models\Barang::where('status_id', $status->id)->exists()) {
            return redirect()->route('status_masters.index')
                             ->with('error', 'Status tidak dapat dihapus karena masih digunakan oleh data barang.');
        }

        $status->delete();

        return redirect()->route('status_masters.index')
                         ->with('success', 'Status berhasil dihapus.');
    }
}
