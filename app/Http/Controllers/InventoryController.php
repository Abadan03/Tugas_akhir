<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Log;

use App\Models\Barang;
use App\Models\Pinjaman;
use App\Models\BarangRusak;
use App\Models\Pembayaran;
use App\Models\itemStatusLog;


class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // $data = Barang::where('status', 0)->get();
        $data = Barang::all();
        
        return view('admin.inventory.index', compact('data')); // kirim ke view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.inventory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'status' => 'required|numeric',
            'harga_awal' => 'required|numeric',
            'kodeQR' => 'required|string|max:255',
            'bukti' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
            // 'image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $barang = new Barang();
        

        $path = null;
        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $imagePath = Storage::disk('public')->put('bukti_pembelian', $file);

            $path = $file->storeAs(
                'images', // Direktori target di disk 'public'
                $file->getClientOriginalName(), // Nama file asli
                'public' // Disk 'public'
            ); 
        }

        $barang->nama_barang = $request->nama_barang;
        $barang->kategori = $request->kategori;
        $barang->nama_siswa = $request->nama_siswa;
        $barang->tipe = $request->tipe;
        $barang->status = $request->status;
        $barang->harga_awal = $request->harga_awal;
        $barang->kodeQR = $request->kodeQR;
        $barang->bukti = $path;
        $barang->save();

        if ($request->kategori ==  1) {
            Pinjaman::create([
                // 'user_id' => $request->user_id,
                'barang_id' => $barang->id,
            ]);
        }


        return redirect()->route('inventaris.index')->with('success', 'Barang berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    public function show(string $id)
    {
        //
        $barang = Barang::findOrFail($id);
         // Ambil barang rusak yang berhubungan dengan barang ini
        $barangRusaks = BarangRusak::where('barang_id', $id)->with('barang')->get();

        // Ambil pembayaran yang terkait barang ini melalui barang rusak
        $pembayaran = Pembayaran::whereHas('barangRusak', function ($query) use ($id) {
            $query->where('barang_id', $id);
        })->with('barangRusak.barang')->first();
        // return dd($pembayaran->biaya_perbaikan);

        // $history = itemStatusLog::where('barang_id', $id)
        // ->orderBy('created_at', 'desc')
        // ->get();

        // $history = DB::table('item_status_logs as isl')
        // ->leftJoin('pembayaran as p', 'isl.barang_id', '=', 'p.barang_id')
        // ->where('isl.barang_id', $id)
        // ->orderBy('isl.created_at', 'desc')
        // ->select(
        //     'isl.status',
        //     'isl.keterangan',
        //     'isl.created_at',
        //     'p.bukti_transfer',
        //     'p.biaya_perbaikan'
        // )
        // ->get();

        $history = DB::table('item_status_logs as isl')
        ->leftJoin('barang_rusaks as br', 'isl.barang_id', '=', 'br.barang_id')
        ->leftJoin('pembayaran as p', 'br.id', '=', 'p.barang_rusaks_id')
        ->where('isl.barang_id', $id)
        ->orderBy('isl.created_at', 'desc')
        ->select(
            'isl.status',
            'isl.keterangan',
            'isl.created_at',
            'p.bukti_transfer',
            'p.biaya_perbaikan'
        )
        ->get();

        // return $history;


        return view('admin.inventory.details', compact('barang', 'pembayaran', 'history'));
    }

    public function details(string $id) 
    {
        $barang = Barang::findOrFail($id);
          // Ambil barang rusak yang berhubungan dengan barang ini
        $barangRusaks = BarangRusak::where('barang_id', $id)->with('barang')->get();

        // Ambil pembayaran yang terkait barang ini melalui barang rusak
        $pembayaran = Pembayaran::whereHas('barangRusak', function ($query) use ($id) {
            $query->where('barang_id', $id);
        })->with('barangRusak.barang')->get();
        return dd($pembayaran);

        return view('admin.inventory.details', compact('barang', 'pembayaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $barang = Barang::findOrFail($id);
        $barangRusaks = BarangRusak::with('barang')->get();

        $barangRusakIds = BarangRusak::pluck('barang_id');
        $data = Barang::whereIn('id', $barangRusakIds)->get();
        return view('admin.inventory.edit', compact('barang', 'barangRusaks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $barang = Barang::findOrFail($id);

        $validatedData = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'harga_awal' => 'required|numeric',
            'kodeQR' => 'required|string|max:255',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'nama_siswa' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        // Update atribut dasar
        $barang->nama_barang = $request->nama_barang;
        $barang->nama_siswa = $request->nama_siswa;
        $barang->kategori = $request->kategori;
        $barang->tipe = $request->tipe;
        $barang->status = $request->status;
        $barang->harga_awal = $request->harga_awal;
        $barang->kodeQR = $request->kodeQR;
        $barang->keterangan = $request->keterangan;

        // Update nama siswa jika kategori 1

        if ($request->kategori ==  1) {
            Pinjaman::create([
                // 'user_id' => $request->user_id,
                'barang_id' => $barang->id,
            ]);
        }

        // Handle file bukti pembelian
        if ($request->hasFile('bukti')) {
            if ($barang->bukti && Storage::disk('public')->exists($barang->bukti)) {
                Storage::disk('public')->delete($barang->bukti);
            }
            $file = $request->file('bukti');
            $path = $file->storeAs('images', $file->getClientOriginalName(), 'public');
            $barang->bukti = $path;
        }

        // Jika status barang bukan 0 dan terdapat surat, simpan ke barang_rusak
        if ($request->status != 0 && $request->hasFile('surat')) {
            if ($request->status != 4) {
                $file = $request->file('surat');
                $suratPath = $file->storeAs('surat', $file->getClientOriginalName(), 'public');
    
                BarangRusak::create([
                    'barang_id' => $id,
                    'pinjaman_id' => null,
                    'surat' => $suratPath,
                ]);
            }
        }

        // Simpan perubahan pada barang
        $barang->save();

        

        return redirect()->route('inventaris.index')->with('success', 'Data barang berhasil diperbarui');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
