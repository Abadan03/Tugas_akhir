<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminController;



Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/dashboard', [UserController::class, 'homepage'])->name('dashboard');
    // Route::resource('products', ProductController::class);
    // Inventarory Routes
    Route::resource('inventaris', InventoryController::class);
    Route::get('inventaris/details/{id}', [InventoryController::class, 'details'])->name('inventaris.details');
    // Route::get('/inventaris', [InventoryController::class, 'index'])->name('inventaris');
    // Route::get('/inventaris-details', [InventoryController::class, 'details'])->name('inventaris.details');
    // Route::get('/inventaris-edit', [InventoryController::class, 'edit'])->name('inventaris.edit');
    // Route::get('/inventaris-delete', [InventoryController::class, 'delete'])->name('inventaris.delete');

    // Route::get('/peminjaman', [LoanController::class, 'index'])->name('peminjaman');
    Route::resource('peminjaman', LoanController::class);

    Route::get('/pembayaran', [PaymentController::class, 'index'])->name('pembayaran');

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
