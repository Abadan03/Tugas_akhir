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

// QR CODE Generator
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

use Illuminate\Pagination\Paginator;


class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // $data = Barang::all();
        Paginator::useBootstrap(); // Tambahkan ini
        $data = Barang::paginate(20);
        
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
            'kategori' => 'required|integer|max:255',
            'tipe' => 'required|integer|max:255',
            'status' => 'required|numeric',
            'harga_awal' => 'required|numeric',
            'kodeQR' => 'nullable|string|max:255',
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

        // $history = DB::table('item_status_logs as isl')
        // ->leftJoin('barang_rusaks as br', 'isl.barang_id', '=', 'br.barang_id')
        // ->leftJoin('pembayaran as p', 'br.id', '=', 'p.barang_rusaks_id')
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
        $history = itemStatusLog::where('barang_id', $id)
            ->orderBy('created_at', 'desc')
            ->get(); // ?? collect();

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
        })->with('barangRusak.barang')->first();
        // return dd($pembayaran);

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
            'kategori' => 'required|integer|max:255',
            'tipe' => 'required|integer|max:255',
            'status' => 'required|integer|max:255',
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
        if (!in_array((int) $request->status, [0, 4])) {
            $barang->keterangan = $request->keterangan;
        }
        // $barang->keterangan = $request->keterangan;

        // Update nama siswa jika kategori 1
        $pinjamans = Pinjaman::where('barang_id', $barang->id)->first();
        // return dd($barang->toArray(), $request->all());
        // \Log::info('UPDATE DIPANGGIL');
        // return dd('update masuk');

        if ((int) $request->kategori == 1) {
            Pinjaman::create([
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
        // if ($request->status != 0 && $request->hasFile('surat')) {
        //     if ($request->status != 4) {
        //         $file = $request->file('surat');
        //         $suratPath = $file->storeAs('surat', $file->getClientOriginalName(), 'public');
    
        //         BarangRusak::create([
        //             'barang_id' => $id,
        //             'pinjaman_id' => null,
        //             'surat' => $suratPath,
        //         ]);
        //     }
        // }
        if ($request->status != 0) {
            $suratPath = null;

            if ($request->hasFile('surat')) {
                $file = $request->file('surat');
                $suratPath = $file->storeAs('surat', $file->getClientOriginalName(), 'public');
            }

            // Jika status != 4, maka kita buat entry di barang_rusaks
            if ($request->status != 4) {
                BarangRusak::create([
                    'barang_id' => $id,
                    'pinjaman_id' => null,
                    'surat' => $suratPath, // bisa null kalau tidak ada file
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
    public function destroy($id)
        {
            $barang = Barang::with('barangRusaks.pembayaran', 'itemStatusLogs')->findOrFail($id);

            // Hapus semua pembayaran (kalau ada)
            if ($barang->barangRusaks->count()) {
                foreach ($barang->barangRusaks as $rusak) {
                    if ($rusak->pembayaran()->exists()) {
                        $rusak->pembayaran()->delete();
                    }
                }
                $barang->barangRusaks()->delete();
            }

            // Hapus status log (kalau ada)
            if ($barang->itemStatusLogs->count()) {
                $barang->itemStatusLogs()->delete();
            }

            // Hapus barang
            $barang->delete();

            return redirect()->route('inventaris.index')->with('success', 'Barang berhasil dihapus.');
        }


    public function showFromQR()
    {
        // $barang = Barang::findOrFail($id);
        // $barangRusaks = BarangRusak::where('barang_id', $id)->with('barang')->get();

        return view('admin.inventory.scan-QR');
    }

    // di InventoryController paling bawah
    public function importCSV()
    {
        return view('admin.inventory.import'); // tampilan upload form
    }

    public function handleImportCSV(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getPathname(), "r");
        $header = fgetcsv($handle, 1000, ","); // Ambil header csv

        $count = 0;
        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $data = array_combine($header, $row);

            $barang = Barang::create([
                'nama_barang' => $data['nama_barang'],
                'kategori' => $data['kategori'],
                'nama_siswa' => $data['nama_siswa'] ?? null,
                'tipe' => $data['tipe'],
                'status' => $data['status'],
                'harga_awal' => $data['harga_awal'] ?? 0,
                'kodeQR' => null, // sementara null, nanti update
                'bukti' => null,
            ]);

            // generate kodeQR otomatis setelah tahu id
            $barang->update([
                'kodeQR' => json_encode(['id' => (string) $barang->id])
            ]);

            // kalau kategori = 1 otomatis masuk ke tabel pinjamans
            if ((int)$data['kategori'] === 1) {
                Pinjaman::create([
                    'barang_id' => $barang->id,
                ]);
            }

            // kalau status != 0 dan != 4 otomatis masuk ke barang_rusaks
            if ((int)$data['status'] !== 0 && (int)$data['status'] !== 4) {
                BarangRusak::create([
                    'barang_id' => $barang->id,
                    'pinjaman_id' => null,
                    'surat' => null, // karena saat import CSV belum ada file
                ]);
            }

            $count++;
        }
        fclose($handle);

        return redirect()->route('inventaris.index')->with('success', $count.' Barang berhasil diimport.');
    }
}
