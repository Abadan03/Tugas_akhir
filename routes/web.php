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



Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/dashboard', [UserController::class, 'homepage'])->name('dashboard');
    Route::get('/dashboard/details/{id}', [UserController::class, 'details'])->name('dashboard.details');

    // Inventarory Routes
    Route::resource('inventaris', InventoryController::class);
    Route::get('inventaris/details/{id}', [InventoryController::class, 'details'])->name('inventaris.details');
    // IMPORT CSV
    Route::get('importCSV', [InventoryController::class, 'importCSV'])->name('import.csv');
    Route::post('importCSV', [InventoryController::class, 'handleImportCSV'])->name('import.csv.handle');
    
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

