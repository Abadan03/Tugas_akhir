<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\ExportPDFController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\TipeController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\BarangController; 



Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/dashboard', [UserController::class, 'homepage'])->name('dashboard');
    Route::get('/dashboard/details/{id}', [UserController::class, 'details'])->name('dashboard.details');

    // Inventarory Routes
    Route::resource('inventaris', InventoryController::class);
    // Route::get('inventaris/', [InventoryController::class, 'details'])->name('inventaris.details');
    Route::get('inventaris/details/{id}', [InventoryController::class, 'details'])->name('inventaris.details');

    // Data Master
    Route::resource('kategori', KategoriController::class);
    Route::get('MastersKategori', [KategoriController::class, 'index'])->name('admin.category_masters.index');
    Route::resource('tipe', TipeController::class);
    Route::get('MastersTipe', [TipeController::class, 'index'])->name('type_masters.index');
    Route::resource('status', StatusController::class);
    Route::get('MastersStatus', [StatusController::class, 'index'])->name('status_masters.index');
    // Route::prefix('admin')->name('admin.')->group(function () {
        // resource untuk item master (nama path dan route names bisa kamu sesuaikan)
    Route::resource('barang', BarangController::class);
    Route::get('MastersItem', [BarangController::class, 'index'])->name('items_masters.index');
    // });

    // IMPORT CSV
    Route::get('importCSV', [InventoryController::class, 'importCSV'])->name('import.csv');
    Route::post('importCSV', [InventoryController::class, 'handleImportCSV'])->name('import.csv.handle');
    Route::get('importCSV/template', [InventoryController::class, 'downloadTemplate'])->name('import.csv.template');
    
    // Loan Routes
    Route::resource('peminjaman', LoanController::class);
    // Route::get('/qrcode/', [LoanController::class, 'showFromQR'])->name('qrcode.render');

    Route::get('/qrcode', [QRCodeController::class, 'render'])->name('qrcode.render');
    Route::get('/qrcode/fetch/{id}', [QRCodeController::class, 'fetch'])->name('qrcode.fetch');
    Route::post('/qrcode/update/{id}', [QRCodeController::class, 'update'])->name('qrcode.update');


    // PEMBAYARAN
    Route::resource('pembayaran', PaymentController::class);

    



    // Ini untuk export PDF dan generate qr code with server-side generator
    Route::post('/inventaris/export-pdf', [ExportPDFController::class, 'exportSelected'])->name('inventaris.exportPDF');

    // Search Get Query
    Route::get('inventaris/search', [InventoryController::class, 'searchQuery'])->name('inventaris.cari');


    Route::post('/logout', [RegisterController::class, 'logout'])->name('logout');
});


Route::get('/login', [AdminController::class, 'loginpage'])->name('login');

Route::post('/login', [RegisterController::class, 'login'])->name('login.submit');

Route::get('/register', function () {
    return view('admin.register');
});

// Route::get('/phpinfo', function () {
//     return view('');
// });


// Route::get('/', function () {
//     return view('');
// });

// Route::get('/', [AdminController::class, 'loginpage'])->name('/');

Route::get('/', function () {
    return redirect()->route('login');
});

