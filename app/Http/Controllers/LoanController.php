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
use App\Models\Pembayaran;
use App\Models\ItemMasters;
use App\Models\CategoryMaster;
use App\Models\StatusMaster;
use App\Models\TypeMaster;
use App\Models\itemStatusLog;


use Illuminate\Pagination\Paginator;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        Paginator::useBootstrap(); // Tambahkan ini
        // $data = Pinjaman::with('barang')->get();
        // $keterangan = BarangRusak::findOrFail();
        // $data = Pinjaman::with('barang')->paginate(20);
        // $data = Barang::with(['kategori', 'status', 'tipe', 'itemMaster'])->paginate(20);
        $data = Barang::with(['kategori', 'status', 'tipe', 'itemMaster'])
            ->where('kategori_id', 2)
            ->paginate(20);
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
        $barang = Barang::findOrFail($id);
        // return dd($barang); 
        

        // Ambil barang rusak yang berhubungan dengan barang ini
        $barangRusaks = BarangRusak::where('barang_id', $id)->with('barang')->get();

        $pembayaran = DB::table('pembayaran')
            ->join('barang_rusaks', 'pembayaran.barang_rusaks_id', '=', 'barang_rusaks.id')
            ->join('barangs', 'barangs.id', '=', 'barang_rusaks.barang_id')
            // ->where('pembayaran.id', $id)
            ->select(
                'barangs.id as barang_id',
                'barang_rusaks.id as barang_rusaks_id',
                'barang_rusaks.surat as surat',
                'barangs.nama_barang as nama_barang',
                'barangs.nama_siswa as nama_siswa',
                'barangs.kategori_id as kategori_id',
                'barangs.tipe_id as tipe_id',
                'barangs.harga_awal as harga_awal',
                'barangs.kodeQR as kodeQR',
                'barangs.bukti as bukti',
                'barangs.keterangan as keterangan',
                'barangs.status_id as status_id',
                'pembayaran.id as pembayaran_id',
                'pembayaran.barang_rusaks_id as barang_rusak_id',
                'pembayaran.biaya_perbaikan as biaya_perbaikan'
            )
            ->first();

        $history = itemStatusLog::where('barang_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.loan.details', compact('barang', 'pembayaran', 'history'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        // $data = Pinjaman::with('barang')->get();
        $barang = Barang::findOrFail($id);

        $pinjaman = Pinjaman::where('barang_id', $id)->first();
        $categories = CategoryMaster::all(); // tambahkan baris ini
        $statuses = StatusMaster::all(); // kalau kamu juga pakai status di form edit
        $types = TypeMaster::all(); // kalau kamu juga pakai tipe

        // return dd([
        //     'barang' => $barang,
        //     'pinjaman' => $pinjaman
        // ]);

        return view('admin.loan.edit', compact('barang', 'pinjaman', 'categories', 'types', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $barang = Barang::findOrFail($id); 
        $pinjaman = Pinjaman::where('barang_id', $id)->first(); 
        // $pinjaman = Pinjaman::firstOrCreate(['barang_id' => $id]);
        // return dd($request->all());

        if ($request->hasFile('surat')) {
            $file = $request->file('surat');
            $path = $file->storeAs('images', $file->getClientOriginalName(), 'public');

            BarangRusak::create([
                'barang_id' => $id,
                'pinjaman_id' => $request->pinjaman_id,
                'surat' => $path,
            ]);
        } else {
            // Tetap buat record BarangRusak minimal
            BarangRusak::firstOrCreate([
                'barang_id' => $id,
                'pinjaman_id' => $request->pinjaman_id,
            ]);
        }

        // Update barang - ini selalu dijalankan
        $barang->status_id = $request->status_id;
        $barang->keterangan = $request->keterangan;
        $barang->nama_siswa = $request->nama_siswa;
        $barang->kodeQR = $request->kodeQR;
        $barang->tipe_id = $request->tipe_id;
        $barang->kategori_id = $request->kategori_id;

         // Cek apakah kategori milik sekolah atau dipinjam siswa
            if ($request->kategori_id == 2) {
                // Jika dipinjam siswa, simpan nama siswa
                $barang->nama_siswa = $request->nama_siswa;
            } else {
            // Jika milik sekolah, kosongkan nama siswa
            // $barang->delete();
            $barang->nama_siswa = null;

            // Kosongkan relasi ke pinjaman karena ini milik sekolah
            $barangRusak = BarangRusak::where('barang_id', $barang->id)->first();
            if ($barangRusak) {
                $barangRusak->pinjaman_id = null;
                $barangRusak->save();
            }

            // if ($pinjaman) {
            //     $pinjaman->delete(); 
            // }
        }

        // Upload bukti pembelian jika ada
        if ($request->hasFile('bukti')) {
            if ($barang->bukti && Storage::disk('public')->exists($barang->bukti)) {
                Storage::disk('public')->delete($barang->bukti);
            }

            $buktiPath = $request->file('bukti')->storeAs(
                'images',
                $request->file('bukti')->getClientOriginalName(),
                'public'
            );

            $barang->bukti = $buktiPath;
        }

        $barang->save();

        return redirect()->route('peminjaman.index')->with('success', 'Update berhasil.');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function showFromQR()
    {
        // $barang = Barang::findOrFail($id);
        // $barangRusaks = BarangRusak::where('barang_id', $id)->with('barang')->get();

        return view('admin.inventory.scan-QR');
    }
}
