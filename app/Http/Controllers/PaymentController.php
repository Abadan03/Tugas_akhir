<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\Log;

use App\Models\Barang;
use App\Models\Pinjaman;
use App\Models\Pembayaran;
use App\Models\BarangRusak;
use App\Models\itemStatusLog;


use Illuminate\Pagination\Paginator;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        Paginator::useBootstrap(); // Tambahkan ini


            // $data = DB::table('barangs')
            //     ->join(DB::raw('(
            //         SELECT barang_rusaks.*
            //         FROM barang_rusaks
            //         LEFT JOIN pembayaran ON pembayaran.barang_rusaks_id = barang_rusaks.id
            //         WHERE pembayaran.id IS NULL
            //     ) as barang_rusaks'), 'barang_rusaks.barang_id', '=', 'barangs.id')
            //     ->leftJoin('kategori_masters', 'barangs.kategori_id', '=', 'kategori_masters.id')
            //     ->leftJoin('tipe_masters', 'barangs.tipe_id', '=', 'tipe_masters.id')
            //     ->leftJoin('status_masters', 'barangs.status_id', '=', 'status_masters.id')
            //     ->whereNotIn('barangs.status_id', [0, 4])
            //     ->select(
            //         'barangs.id as barang_id',
            //         'barang_rusaks.id as barang_rusaks_id',
            //         'barangs.nama_barang',
            //         'barangs.nama_siswa',
            //         'kategori_masters.nama_kategori',
            //         'tipe_masters.nama_tipe',
            //         'status_masters.nama_status',
            //         'barangs.keterangan'
            //     )
            //     ->paginate(20);

            $data = DB::table('barangs')
            ->join('barang_rusaks', 'barang_rusaks.barang_id', '=', 'barangs.id')
            ->leftJoin('pembayaran', 'pembayaran.barang_rusaks_id', '=', 'barang_rusaks.id')
            ->leftJoin('category_masters', 'barangs.kategori_id', '=', 'category_masters.id')
            ->leftJoin('type_masters', 'barangs.tipe_id', '=', 'type_masters.id')
            ->leftJoin('status_masters', 'barangs.status_id', '=', 'status_masters.id')
            ->whereIn('barangs.status_id', [2, 3, 4]) // hanya status tertentu
            // ->whereNull('pembayaran.id') 
            ->select(
                'barangs.id as barang_id',
                'barang_rusaks.id as barang_rusaks_id',
                'barangs.nama_barang',
                'barangs.status_id',
                'barangs.kategori_id',
                'barangs.tipe_id',
                'barangs.peminjam',
                'category_masters.nama_kategori',
                'type_masters.nama_tipe',
                'status_masters.nama_status',
                'barangs.keterangan'
            )
            ->paginate(20);

        $categories = \App\Models\CategoryMaster::all();
        $types = \App\Models\TypeMaster::all();
        $statuses = \App\Models\StatusMaster::all();
        $items = \App\Models\ItemMasters::all();

        $datas = Barang::with(['kategori', 'status', 'tipe', 'itemMaster'])->paginate(20);

        // return dd($data);
        
        // $data = Barang::whereNotIn('status', [0,4])->with('barangRusak')->get();

        // return dd($data);

        return view('admin.payment.index', compact('data', 'datas'));    
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
        // $pembayaran = Pembayaran::findOrFail($id)->with('barangRusak')->first();

        $pembayaran = DB::table('pembayaran')
            ->join('barang_rusaks', 'pembayaran.barang_rusaks_id', '=', 'barang_rusaks.id')
            ->join('barangs', 'barangs.id', '=', 'barang_rusaks.barang_id')
            ->where('pembayaran.id', $id)
            ->select(
                'barangs.id as barang_id',
                'barang_rusaks.id as barang_rusaks_id',
                'barang_rusaks.surat as surat',
                'barangs.nama_barang as nama_barang',
                'barangs.nama_siswa as nama_siswa',
                'barangs.kategori_id as kategori',
                'barangs.tipe_id as tipe',
                'barangs.harga_awal as harga_awal',
                'barangs.kodeQR as kodeQR',
                'barangs.bukti as bukti',
                'barangs.keterangan as keterangan',
                'barangs.status_id as status',
                'pembayaran.id as pembayaran_id',
                'pembayaran.barang_rusaks_id as barang_rusak_id'
            )
            ->first();
        // $pembayaran = Pembayaran::findOrFail($id)->with('barangRusak')->first();
        // $barang = $pembayaran->barangRusak
        // return json_encode($pembayaran);
        return view('admin.payment.details', compact('pembayaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $items = BarangRusak::with('barang')->findOrFail($id);
        $barangRusak = BarangRusak::with('barang')->get()->find($id);
        $payment = Pembayaran::where('barang_rusaks_id', $barangRusak->id)->first();
        // $barang = Barang::findOrFail($id);
        // Barang diambil dari relasi, bukan find ulang pakai id rusak
        $barang = $barangRusak->barang;

        $categories = \App\Models\CategoryMaster::all();
        $types = \App\Models\TypeMaster::all();
        $statuses = \App\Models\StatusMaster::all();
        $itemsMaster = \App\Models\ItemMasters::all();
        // return dd($pembayaran);
        return view('admin.payment.edit', compact('barangRusak', 'items', 'payment', 'categories', 'types', 'statuses', 'itemsMaster', 'barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'keterangan' => 'required|string|max:255',
            'kodeQR' => 'nullable|string|max:255',
            'biaya_perbaikan' => 'required|string|max:255',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'bukti_transfer' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Ambil data barang
        $barang = Barang::findOrFail($id);
        $pinjaman = Pinjaman::where('barang_id', $id)->first();

        // ======================================================
        // 1️⃣ Simpan data BARANG RUSAK baru (selalu insert baru)
        // ======================================================
        $pathSurat = null;
        if ($request->hasFile('surat')) {
            $file = $request->file('surat');
            $pathSurat = $file->storeAs(
                'images',
                $file->getClientOriginalName(),
                'public'
            );
        }

        // $barangRusak = new BarangRusak();
        $barangRusak = BarangRusak::where('barang_id', $id)->first();
        $barangRusak->barang_id = $barang->id;
        $barangRusak->pinjaman_id = $pinjaman ? $pinjaman->id : null;
        $barangRusak->surat = $pathSurat;
        $barangRusak->save();

        // ======================================================
        // 2️⃣ Update tabel BARANGS (status jadi rusak + QR baru)
        // ======================================================
        $pathBukti = null;
        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $pathBukti = $file->storeAs(
                'images',
                $file->getClientOriginalName(),
                'public'
            );
        }

        $barang->keterangan = $request->keterangan;
        $barang->tipe_id = $request->tipe_id;
        $barang->status_id = 5; // 5 = rusak
        $barang->bukti = $pathBukti;

        // Generate ulang isi QR
        $kodeQR = json_encode([
            'id' => $barang->id,
        ]);
        $barang->kodeQR = $kodeQR;
        $barang->save();

        // ======================================================
        // 3️⃣ Simpan data PEMBAYARAN (selalu buat baru juga)
        // ======================================================
        $pathTransfer = null;
        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $pathTransfer = $file->storeAs(
                'images',
                $file->getClientOriginalName(),
                'public'
            );
        }

        $dataChecked = Pembayaran::where('barang_rusaks_id', $barangRusak->id)->first();

        if (!$dataChecked) {
            $dataChecked = new Pembayaran();
        }
        // $pembayaran = new Pembayaran();
        // $pembayaran->barang_rusaks_id = $barangRusak->id;
        // $pembayaran->bukti_transfer = $pathTransfer;
        // $pembayaran->biaya_perbaikan = $request->biaya_perbaikan;
        // $pembayaran->save();
        $dataChecked->barang_rusaks_id = $barangRusak->id;
        $dataChecked->bukti_transfer = $pathTransfer ?? null;
        $dataChecked->biaya_perbaikan = $request->biaya_perbaikan;
        $dataChecked->save();


        // ======================================================
        // 4️⃣ Tambahkan ke ITEM STATUS LOG (untuk histori barang)
        // ======================================================
        $itemLogs = new itemStatusLog();
        $itemLogs->barang_id = $barang->id;
        $itemLogs->status = $barang->status_id;
        $itemLogs->keterangan = $barang->keterangan;
        $itemLogs->biaya_perbaikan = $request->biaya_perbaikan;
        $itemLogs->save();

        // ======================================================
        // 5️⃣ Redirect sukses
        // ======================================================
        return redirect()->route('pembayaran.index')->with('success', 'Barang rusak baru berhasil dicatat dan pembayaran disimpan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
