<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class QRCodeController extends Controller
{

    public function render(Request $request)
    {

        $from = $request->query('from', 'inventaris');

        if ($from === 'peminjaman') {
            $backRoute = route('peminjaman.index');
        } elseif ($from === 'pembayaran') {
            $backRoute = route('pembayaran.index');
        } else {
            $backRoute = route('inventaris.index');
        }

        $categories = \App\Models\CategoryMaster::all();
        $types = \App\Models\TypeMaster::all();
        $statuses = \App\Models\StatusMaster::all();

        return view('admin.qrcode.scan', compact(
            'backRoute',
            'categories',
            'types',
            'statuses'
        ));
    }

    public function fetch($id)
    {

        $barang = Barang::with(['kategori','tipe','status'])->findOrFail($id);

        return response()->json([
            'id' => $barang->id,
            'nama_barang' => $barang->nama_barang,

            'kategori' => $barang->kategori_id,
            'kategori_label' => $barang->kategori->nama_kategori ?? '-',

            'tipe' => $barang->tipe_id,
            'tipe_label' => $barang->tipe->nama_tipe ?? '-',

            'status' => $barang->status_id,
            'status_label' => $barang->status->nama_status ?? '-',

            'harga_awal' => $barang->harga_awal,
            'peminjam' => $barang->peminjam,
            'keterangan' => $barang->keterangan
        ]);
    }

    public function update(Request $request, $id)
    {

        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'peminjam' => 'nullable|string|max:255',
            'harga_awal' => 'required|numeric',
            'tipe' => 'required|numeric',
            'kategori' => 'required|numeric',
            'status' => 'required|numeric',
            'keterangan' => 'nullable|string|max:255'
        ]);

        $barang = Barang::findOrFail($id);

        $barang->update([
            'nama_barang' => $validated['nama_barang'],
            'peminjam' => $validated['peminjam'],
            'harga_awal' => $validated['harga_awal'],
            'tipe_id' => $validated['tipe'],
            'kategori_id' => $validated['kategori'],
            'status_id' => $validated['status'],
            'keterangan' => $validated['keterangan']
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Barang berhasil diupdate'
        ]);
    }
}