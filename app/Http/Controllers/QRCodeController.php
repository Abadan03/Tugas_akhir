<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QRCodeController extends Controller
{
    //

    public function render(Request $request)
    {
        $from = $request->query('from', 'inventaris');
        $backRoute = $from === 'peminjaman' ? route('peminjaman.index') : route('inventaris.index');

        return view('admin.qrcode.scan', compact('backRoute'));
    }
}
