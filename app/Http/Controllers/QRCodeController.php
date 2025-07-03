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

        return view('admin.qrcode.scan', compact('backRoute'));
    }

    public function fetch($id)
    {
        $barang = Barang::findOrFail($id);
        // return dd($barang);


        if (!$barang) {
            return response()->json(['error' => 'Barang tidak ditemukan'], 404);
        }

        return response()->json([
            'id' => $barang->id,
            'nama_barang' => $barang->nama_barang,
            'kategori' => $barang->kategori, // nilai asli (0/1)
            'kategori_label' => $barang->kategori == 1 ? 'Dipinjam oleh siswa' : 'Milik Sekolah',

            'tipe' => $barang->tipe, // nilai asli
            'tipe_label' => $barang->tipe == 1 ? 'Barang berpindah' : 'Barang tetap',

            'status' => $barang->status,
            'status_label' => match ($barang->status) {
                0 => 'Baru',
                1 => 'Hilang',
                2 => 'Rusak Ringan',
                3 => 'Rusak',
                4 => 'Diperbarui',
                default => '-'
            },

            'harga_awal' => $barang->harga_awal,
            'nama_siswa' => $barang->nama_siswa,
            'keterangan' => $barang->keterangan ?? ''
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
        $barang->update($validated);
        // $barang->nama_barang = $request->nama_barang;
        // $barang->nama_siswa = $request->nama_siswa;
        // $barang->harga_awal = $request->harga_awal;
        // $barang->tipe = $request->tipe;
        // $barang->kategori = $request->kategori;
        // $barang->status = $request->status;
        // $barang->keterangan = $request->keterangan;
        // $barang->save(); 



        if ((int) $request->kategori == 1) {
            $pinjaman = Pinjaman::where('barang_id', $barang->id)->first();

            if ($pinjaman) {
                // Update jika perlu, misalnya kamu mau tambah field lain nanti
                $pinjaman->barang_id = $barang->id;
                $pinjaman->save();
            } else {
                // Jika belum ada, baru buat
                Pinjaman::create([
                    'barang_id' => $barang->id,
                ]);
            }
        }

        if ($request->status != 0) {
            $suratPath = null;

            // if ($request->hasFile('surat')) {
            //     $file = $request->file('surat');
            //     $suratPath = $file->storeAs('surat', $file->getClientOriginalName(), 'public');
            // }

            // Jika status != 4, maka kita buat entry di barang_rusaks
            if ($request->status != 4) {
                BarangRusak::create([
                    'barang_id' => $id,
                    'pinjaman_id' => null,
                ]);
            }
        }

        // return dd(request->all());

        // return response()->json(['message' => 'Barang berhasil diperbarui']);
        return response()->json(['ok' => true, 'data' => $validated]);

    }
}
