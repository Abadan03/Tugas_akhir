<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Log;

use App\Models\Barang;
use App\Models\ItemMasters;
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
        // $data = Barang::paginate(20);
        $data = Barang::with(['kategori', 'status', 'tipe', 'itemMaster'])->paginate(10);

        
        return view('admin.inventory.index', compact('data')); // kirim ke view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\CategoryMaster::all();
        $types = \App\Models\TypeMaster::all();
        $statuses = \App\Models\StatusMaster::all();
        $items = \App\Models\ItemMasters::all();
        

        return view('admin.inventory.create', compact('categories', 'types', 'statuses', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // Log::info('Data berhasil disimpan', ['data' => $request->all()]);

        $validatedData = $request->validate([
            // 'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|integer',
            'tipe_id' => 'required|integer',
            'status_id' => 'required|integer',
            'items_id' => 'required|integer',
            'harga_awal' => 'required|numeric',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', 
            'keterangan' => 'nullable|string|max:1000',
            'peminjam' => 'nullable|string|max:255', 
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

        $itemMaster = \App\Models\ItemMasters::findOrFail($request->items_id);

        $barang->nama_barang = $itemMaster->nama_barang;
        $barang->kategori_id = $request->kategori_id;
        $barang->peminjam = $request->peminjam;
        $barang->tipe_id = $request->tipe_id;
        $barang->status_id = $request->status_id;
        $barang->items_id = $request->items_id;
        $barang->harga_awal = $request->harga_awal;
        $barang->bukti = $path;
        $barang->keterangan = $request->keterangan; // 🟢 Tambahan penting
        $barang->save();

        $qrData = [
            'id' => $barang->id,
        ];

        $barang->kodeQR = json_encode($qrData, JSON_UNESCAPED_UNICODE);
        $barang->save();

         // Cek kategori secara dinamis berdasarkan nama di CategoryMaster
        $kategori = \App\Models\CategoryMaster::find($request->kategori_id);

        if ($kategori && $kategori->defaultTrigger == false) {
            // Pastikan nama siswa tidak kosong
            if (!$request->filled('peminjam')) {
                return redirect()->back()->withErrors(['peminjam' => 'Peminjam wajib diisi untuk kategori ini.'])->withInput();
            }

            // Simpan data ke tabel Pinjaman
            Pinjaman::create([
                'barang_id' => $barang->id,
                // 'tanggal_pinjam' => now(),
            ]);
        }

        // if ($request->kategori_id == 2) {
        //     Pinjaman::create([
        //         'barang_id' => $barang->id,
        //     ]);
        // }

        // jika status barang rusak atau hilang (2, 3, 4)
        if (in_array((int) $request->status_id, [2, 3, 4])) {
            $suratPath = null;

            if ($request->hasFile('surat')) {
                $file = $request->file('surat');
                $suratPath = $file->storeAs('surat', $file->getClientOriginalName(), 'public');
            }

            BarangRusak::create([
                'barang_id' => $barang->id,
                'pinjaman_id' => null,
                'surat' => $suratPath,
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

        $categories = \App\Models\CategoryMaster::all();
        $types = \App\Models\TypeMaster::all();
        $statuses = \App\Models\StatusMaster::all();
        $items = \App\Models\ItemMasters::all();

        $barangRusakIds = BarangRusak::pluck('barang_id');
        $data = Barang::whereIn('id', $barangRusakIds)->get();
        return view('admin.inventory.edit', compact('barang', 'barangRusaks', 'categories', 'types', 'statuses', 'items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Log::info('Update barang dipanggil', $request->all());
        $barang = Barang::findOrFail($id);

        $validatedData = $request->validate([
            // 'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|integer|max:255',
            'tipe_id' => 'required|integer|max:255',
            'items_id' => 'required|integer',
            'status_id' => 'required|integer|max:255',
            'harga_awal' => 'required|numeric',
            'kodeQR' => 'nullable|string|max:255',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'surat' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'peminjam' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $itemMaster = \App\Models\ItemMasters::findOrFail($request->items_id);

        // Update atribut dasar
        $barang->nama_barang = $itemMaster->nama_barang;
        $barang->peminjam = $request->peminjam;
        $barang->kategori_id = $request->kategori_id;
        $barang->tipe_id = $request->tipe_id;
        $barang->status_id = $request->status_id;
        $barang->harga_awal = $request->harga_awal;
        $qrData = [
            'id' => $barang->id,
        ];


        $barang->kodeQR = json_encode($qrData, JSON_UNESCAPED_UNICODE);
        if (!in_array((int) $request->status_id, [2, 4])) {
            $barang->keterangan = $request->keterangan;
        }
        // $barang->keterangan = $request->keterangan;

        // Update data pinjaman jika kategori memicu pinjaman
        $pinjamans = Pinjaman::where('barang_id', $barang->id)->first();
        $kategori = \App\Models\CategoryMaster::find($request->kategori_id);
        
        if ($kategori && $kategori->defaultTrigger == false) {
            if (!$pinjamans) {
                Pinjaman::create([
                    'barang_id' => $barang->id,
                ]);
            }
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
        if ((int)$request->status_id != 1) {
            $suratPath = null;

            if ($request->hasFile('surat')) {
                $file = $request->file('surat');
                $suratPath = $file->storeAs('surat', $file->getClientOriginalName(), 'public');
            }

            // Jika status != 4, maka kita buat entry di barang_rusaks
            if ((int)$request->status_id != 4) {
                $barangRusak = BarangRusak::where('barang_id', $id)->first();
                
                if ($barangRusak) {
                    if ($suratPath) {
                        $barangRusak->surat = $suratPath;
                        $barangRusak->save();
                    }
                } else {
                    BarangRusak::create([
                        'barang_id' => $id,
                        'pinjaman_id' => $pinjamans ? $pinjamans->id : null,
                        'surat' => $suratPath, // bisa null kalau tidak ada file
                    ]);
                }
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
        // return view('admin.inventory.import'); // tampilan upload form
        $categories = \App\Models\CategoryMaster::all();
        $types = \App\Models\TypeMaster::all();
        $statuses = \App\Models\StatusMaster::all();
        $items = \App\Models\ItemMasters::all();

        return view('admin.inventory.import', compact('categories', 'types', 'statuses', 'items'));
    }

    public function downloadTemplate()
    {
        $filename = "template_import_barang.csv";
        $content = "items_id,kategori_id,peminjam,tipe_id,status_id,harga_awal\n".
                "1,1,,1,1,500000\n".
                "2,2,Budi,1,2,300000\n";
                "2,2,Ahmad,1,2,300000\n";

        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename={$filename}");
    }

    public function handleImportCSV(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getPathname(), "r");
        $header = fgetcsv($handle, 1000, ",");

        $count = 0;
        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $data = array_combine($header, $row);

            // ✅ Validasi master existence
            if (!\App\Models\CategoryMaster::find($data['kategori_id']) ||
                !\App\Models\TypeMaster::find($data['tipe_id']) ||
                !\App\Models\StatusMaster::find($data['status_id']) ||
                !\App\Models\ItemMasters::find($data['items_id'])
                ) {
                fclose($handle);
                return redirect()->back()->with('error', "❌ Gagal import: Data master tidak ditemukan pada baris ke-".($count+1));
            }

            $getNamaBarang = ItemMasters::findOrFail($data['items_id']);

            // Buat barang
            $barang = \App\Models\Barang::create([
                'nama_barang' => $getNamaBarang->nama_barang,
                'items_id' => $data['items_id'],
                'kategori_id' => $data['kategori_id'],
                'peminjam' => $data['peminjam'] ?? null,
                'tipe_id' => $data['tipe_id'],
                'status_id' => $data['status_id'],
                'harga_awal' => $data['harga_awal'] ?? 0,
                'kodeQR' => null,
                'bukti' => null,
            ]);

            $barang->update([
                'kodeQR' => json_encode(['id' => (string) $barang->id])
            ]);

            if ((int)$data['kategori_id'] === 2) {
                \App\Models\Pinjaman::create(['barang_id' => $barang->id]);
            }

            if (in_array((int)$data['status_id'], [2, 3, 4])) {
                \App\Models\BarangRusak::create([
                    'barang_id' => $barang->id,
                    'pinjaman_id' => null,
                    'surat' => null,
                ]);
            }

            $count++;
        }
        fclose($handle);

        return redirect()->route('inventaris.index')->with('success', $count.' Barang berhasil diimport.');
    }

    public function searchQuery(Request $request) {

        $placeholder = $request->placeholder_search;

        $data = Barang::where('nama_barang', 'like', '%'.$placeholder. '%')->paginate(10);


        return view('admin.inventory.index', compact('data'));
    }
}
