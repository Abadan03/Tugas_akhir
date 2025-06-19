<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\QRCodeController;



Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/dashboard', [UserController::class, 'homepage'])->name('dashboard');

    // Inventarory Routes
    Route::resource('inventaris', InventoryController::class);
    Route::get('inventaris/details/{id}', [InventoryController::class, 'details'])->name('inventaris.details');
    // Route::get('/qrcode/', [InventoryController::class, 'showFromQR'])->name('qrcode.render');
    
    // Loan Routes
    Route::resource('peminjaman', LoanController::class);
    // Route::get('/qrcode/', [LoanController::class, 'showFromQR'])->name('qrcode.render');

    Route::get('/qrcode', [QRCodeController::class, 'render'])->name('qrcode.render');

    Route::resource('pembayaran', PaymentController::class);

    Route::post('/logout', [RegisterController::class, 'logout'])->name('logout');
});


Route::get('/login', [AdminController::class, 'loginpage'])->name('login');

Route::post('/login', [RegisterController::class, 'login'])->name('login.submit');

Route::get('/register', function () {
    return view('admin.register');
});

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', [AdminController::class, 'loginpage'])->name('/');
