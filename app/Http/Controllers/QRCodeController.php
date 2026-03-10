<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Barang;
use App\Models\Pinjaman;
use App\Models\BarangRusak;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class QRCodeController extends Controller
{
    //

    public function render(Request $request)
    {
        $from = $request->query('from', 'inventaris');
        // $backRoute = $from === 'peminjaman' ? route('peminjaman.index') : route('inventaris.index');
        if ($from === 'peminjaman') {
            $backRoute = route('peminjaman.index');
        } elseif ($from === 'pembayaran') {
            $backRoute = route('pembayaran.index');
        } else {
            $backRoute = route('inventaris.index');
        }

        // $barang = Barang::findOrFail($id);
        // $barang = $id ? Barang::find($id) : null;

        $categories = \App\Models\CategoryMaster::all();
        $types = \App\Models\TypeMaster::all();
        $statuses = \App\Models\StatusMaster::all();
        $items = \App\Models\ItemMasters::all();

        return view('admin.qrcode.scan', compact('backRoute', 'categories', 'types', 'statuses', 'items'));
        
    }

    public function fetch($id)
    {
        // $barang = Barang::findOrFail($id);
        $barang = Barang::with(['kategori', 'tipe', 'status'])->findOrFail($id);
        // return dd($barang);


        if (!$barang) {
            return response()->json(['error' => 'Barang tidak ditemukan'], 404);
        }

        // return response()->json([
        //     'id' => $barang->id,
        //     'nama_barang' => $barang->nama_barang,
        //     'kategori' => $barang->kategori, 
        //     'kategori_label' => $barang->kategori == 1 ? 'Dipinjam oleh siswa' : 'Milik Sekolah',

        //     'tipe' => $barang->tipe,
        //     'tipe_label' => $barang->tipe == 1 ? 'Barang berpindah' : 'Barang tetap',

        //     'status' => $barang->status,
        //     'status_label' => match ($barang->status) {
        //         0 => 'Baru',
        //         1 => 'Hilang',
        //         2 => 'Rusak Ringan',
        //         3 => 'Rusak',
        //         4 => 'Diperbarui',
        //         default => '-'
        //     },

        //     'harga_awal' => $barang->harga_awal,
        //     'nama_siswa' => $barang->nama_siswa,
        //     'keterangan' => $barang->keterangan ?? ''
        // ]);

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
            'nama_siswa' => $barang->nama_siswa,
            'keterangan' => $barang->keterangan ?? '',
        ]);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required|string|max:255',
            'nama_siswa' => 'nullable|string|max:255',
            'harga_awal' => 'required|numeric',
            'tipe' => 'required|numeric',
            'kategori' => 'required|numeric',
            'status' => 'required|numeric',
            'keterangan' => 'nullable|string|max:255'
        ]);
        // return dd($request->keterangan); // buang ini


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $validated = $validator->validated(); 

        $barang = Barang::findOrFail($id);
        // $barang->update($validated);
        // $barang->nama_barang = $request->nama_barang;
        // $barang->nama_siswa = $request->nama_siswa;
        // $barang->harga_awal = $request->harga_awal;
        // $barang->tipe = $request->tipe;
        // $barang->kategori = $request->kategori;
        // $barang->status = $request->status;
        // $barang->keterangan = $request->keterangan;
        // $barang->save(); 



        $barang->update([
            'nama_barang' => $validated['nama_barang'],
            'nama_siswa' => $validated['nama_siswa'] ?? null,
            'harga_awal' => $validated['harga_awal'],
            'tipe_id' => $validated['tipe'],
            'kategori_id' => $validated['kategori'],
            'status_id' => $validated['status'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        $qrData = [
            'id' => $barang->id,
            'nama_barang' => $barang->nama_barang,
            'nama_siswa' => $barang->nama_siswa ?? null,
            'kategori' => $barang->kategori->nama_kategori ?? null,
            'status' => $barang->status->nama_status ?? null,
            'tipe' => $barang->tipe->nama_tipe ?? null,
            'harga_awal' => $barang->harga_awal,
        ];

        $barang->kodeQR = json_encode($qrData, JSON_UNESCAPED_UNICODE);
        if (!in_array((int) $request->status_id, [2, 4])) {
            $barang->keterangan = $request->keterangan;
        }

        // 🔁 Update QR JSON
        // $barang->update([
        //     'kodeQR' => json_encode([
        //         'id' => $barang->id,
        //         'nama_barang' => $barang->nama_barang,
        //         'kode_unik' => 'BRG-' . str_pad($barang->id, 4, '0', STR_PAD_LEFT)
        //     ]),
        // ]);

        // 📌 Jika kategori "Dipinjam oleh siswa"
        if ((int) $validated['kategori'] === 2) {
            Pinjaman::firstOrCreate(['barang_id' => $barang->id]);
        }

        // 📄 Jika status menunjukkan rusak (2, 3, 4)
        if (in_array((int)$validated['status'], [2, 3, 4])) {
            BarangRusak::firstOrCreate([
                'barang_id' => $barang->id,
            ]);
        }

        return response()->json(['ok' => true, 'data' => $barang]);

    }
}
