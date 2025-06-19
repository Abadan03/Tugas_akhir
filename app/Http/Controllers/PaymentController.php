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

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // $data = barangRusak::with('barang')->get();
        // $payments = Pembayaran::with('barangRusak')->get();

        // $data = DB::table('pembayaran')
        // ->leftjoin('barang_rusaks', 'pembayaran.barang_rusaks_id', '=', 'barang_rusaks.id')
        // ->join('barangs', 'barangs.id', '=', 'barang_rusaks.barang_id')
        //     ->select(
        //         'barangs.id as barang_id',
        //         'barang_rusaks.id as barang_rusaks_id',
        //         'barangs.nama_barang as nama_barang',
        //         'barangs.nama_siswa as nama_siswa',
        //         'barangs.kategori as kategori',
        //         'barangs.tipe as tipe',
        //         'barangs.keterangan as keterangan',
        //         'barangs.status as status',
        //         'pembayaran.id as pembayaran_id',
        //         'pembayaran.barang_rusaks_id as barang_rusak_id'
        //     )->get();

            // $data = DB::table('barang_rusaks')
            //     ->leftJoin('pembayaran', 'barang_rusaks.id', '=', 'pembayaran.barang_rusaks_id')
            //     ->join('barangs', 'barang_rusaks.barang_id', '=', 'barangs.id')
            //     ->whereNull('pembayaran.id')
            //     ->where('barangs.status', 4)
            //     ->select(
            //         'barangs.id as barang_id',
            //         'barang_rusaks.id as barang_rusaks_id',
            //         'barangs.nama_barang as nama_barang',
            //         'barangs.nama_siswa as nama_siswa',
            //         'barangs.kategori as kategori',
            //         'barangs.tipe as tipe',
            //         'barangs.keterangan as keterangan',
            //         'barangs.status as status'
            //     )
            //     ->get();

            // $data = DB::table('barangs')
            // ->leftJoin('barang_rusaks', 'barangs.id', '=', 'barang_rusaks.barang_id')
            // ->leftJoin('pembayaran', 'barang_rusaks.id', '=', 'pembayaran.barang_rusaks_id')
            // ->whereNotIn('status', [0, 4])
            // ->whereNull('pembayaran.id')
            // ->select(
            //     'barangs.id as barang_id',
            //     'barang_rusaks.id as barang_rusaks_id',
            //     'barangs.nama_barang',
            //     'barangs.nama_siswa',
            //     'barangs.kategori',
            //     'barangs.tipe',
            //     'barangs.keterangan',
            //     'barangs.status'
            // )
            // ->get();

            $data = DB::table('barangs')
            ->join(DB::raw('
                (
                    SELECT barang_rusaks.*
                    FROM barang_rusaks
                    LEFT JOIN pembayaran ON pembayaran.barang_rusaks_id = barang_rusaks.id
                    WHERE pembayaran.id IS NULL
                ) as barang_rusaks
            '), 'barang_rusaks.barang_id', '=', 'barangs.id')
            ->whereNotIn('barangs.status', [0, 4])
            ->select(
                'barangs.id as barang_id',
                'barang_rusaks.id as barang_rusaks_id',
                'barangs.nama_barang',
                'barangs.nama_siswa',
                'barangs.kategori', 
                'barangs.tipe',
                'barangs.keterangan',
                'barangs.status'
            )
            ->groupBy(
                'barangs.id',
                'barang_rusaks.id',
                'barangs.nama_barang',
                'barangs.nama_siswa',
                'barangs.kategori',
                'barangs.tipe',
                'barangs.keterangan',
                'barangs.status'
            )
            ->get();
        
            // $data = Barang::whereNotIn('status', [0,4])->with('barangRusak')->get();

        // return dd($data);

        return view('admin.payment.index', compact('data'));    
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
                'barangs.kategori as kategori',
                'barangs.tipe as tipe',
                'barangs.harga_awal as harga_awal',
                'barangs.kodeQR as kodeQR',
                'barangs.bukti as bukti',
                'barangs.keterangan as keterangan',
                'barangs.status as status',
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
        $items = BarangRusak::with('barang')->get()->find($id);
        $payment = Pembayaran::where('barang_rusaks_id', $items->id)->first();
        // return dd($pembayaran);
        return view('admin.payment.edit', compact('items', 'payment'));
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

        // return dd($request->all());  

        $barang = Barang::findOrFail($id);
        $barangRusak = BarangRusak::where('barang_id', $id)->first();
        $pinjaman = Pinjaman::where('barang_id', $id)->first();
        // \Log::info($request->all());

        // return dd($barang->id);

        // update for insert surat into barangRusak
        $pathSurat = null;
        if ($request->hasFile('surat')) {
            $file = $request->file('surat');
            $imagePath = Storage::disk('public')->put('surat', $file);

            $pathSurat = $file->storeAs(
                'images', // Direktori target di disk 'public'
                $file->getClientOriginalName(), // Nama file asli
                'public' // Disk 'public'
            ); 
        }
        $barangRusak->surat = $pathSurat;
        $barangRusak->save();

        // update for barangs
        $pathBukti = null;
        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $imagePath = Storage::disk('public')->put('bukti_pembelian', $file);

            $pathBukti = $file->storeAs(
                'images', // Direktori target di disk 'public'
                $file->getClientOriginalName(), // Nama file asli
                'public' // Disk 'public'
            ); 
        }

        $barang->keterangan = $request->keterangan;
        $barang->tipe = $request->tipe;
        $barang->status = 4;
        $barang->keterangan = $request->keterangan;

        // Generate ulang isi QR-nya
        $kategoriLabel = $barang->kategori == 1 ? 'Dipinjam oleh siswa' : 'Milik Sekolah';
        $tipeLabel = $barang->tipe == 1 ? 'Barang berpindah' : 'Barang tetap';
        $statusLabel = 'Diperbarui';

        $kodeQR = json_encode([
            'id' => $barang->id,
            'nama_barang' => $barang->nama_barang,
            'tipe' => $tipeLabel,
            'kategori' => $kategoriLabel,
            'status' => $statusLabel,
            'keterangan' => $request->keterangan,
            'harga_awal' => $request->harga_awal,
        ]);

        $barang->kodeQR = $kodeQR;

        // $barang->bukti = $pathBukti;
        $barang->save();

        // update for pembayaran
        $pembayaran = new Pembayaran();
        $dataChecked = Pembayaran::where('barang_rusaks_id', $barangRusak->id)->first();

        $pathTransfer = null;
        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $imagePath = Storage::disk('public')->put('bukti_transfer', $file);

            $pathTransfer = $file->storeAs(
                'images', // Direktori target di disk 'public'
                $file->getClientOriginalName(), // Nama file asli
                'public' // Disk 'public'
            ); 
        }
        if ($dataChecked) {
            $dataChecked->barang_rusaks_id = $barangRusak->id;
            $dataChecked->bukti_transfer = $pathTransfer;
            $dataChecked->biaya_perbaikan = $request->biaya_perbaikan;
            $dataChecked->save();
        } else {
            $pembayaran->barang_rusaks_id = $barangRusak->id;
            $pembayaran->bukti_transfer = $pathTransfer;
            $pembayaran->biaya_perbaikan = $request->biaya_perbaikan;
            $pembayaran->save();
        }
        

        
        // insert logs into item_status_log for history  
        $itemLogs = new itemStatusLog();
        $itemLogs->barang_id = $id;
        $itemLogs->status = $barang->status;
        $itemLogs->keterangan = $barang->keterangan;
        $itemLogs->save();

        return redirect()->route('pembayaran.index')->with('success', 'Update barang telah dibarui');

        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
