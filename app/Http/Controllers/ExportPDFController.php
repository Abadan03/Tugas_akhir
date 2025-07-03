<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use PDF;

class ExportPDFController extends Controller
{
    public function exportSelected(Request $request)
    {
        $selectedIds = $request->input('selected_items');
        // return response()->json($request->all());
        // abort(500, 'Masuk controller bro: ' . json_encode($request->all()));
        // return dd($request->all());

        if (!$selectedIds || !is_array($selectedIds)) {
            return back()->with('error', 'Tidak ada barang yang dipilih');
        }

        $barangs = Barang::whereIn('id', $selectedIds)->get();

        $pdf = PDF::loadView('admin.exports.barang_qr_pdf', compact('barangs'))->setPaper('A4', 'portrait');

        return $pdf->download('barang_qrcode.pdf');
    }
}
