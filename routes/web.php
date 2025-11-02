<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\POSController;
use App\Livewire\HomePage;
use App\Livewire\POS;

Route::get('/', function () {
    return view('welcome');
});

// // POS Routes
// Route::prefix('pos')->group(function () {
//     Route::get('/', [POSController::class, 'index'])->name('pos.index');
//     Route::get('/products/{category?}', [POSController::class, 'getProducts'])->name('pos.products');
//     Route::post('/create-order', [POSController::class, 'createOrder'])->name('pos.create-order');
//     Route::get('/receipt/{order}', [POSController::class, 'printReceipt'])->name('pos.receipt');
//     Route::get('/order-history', [POSController::class, 'getOrderHistory'])->name('pos.order-history');
// });

// Dashboard route
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/', HomePage::class);
Route::get('/pos', POS::class);
Route::post('/create-order', [POSController::class, 'createOrder'])->name('pos.create-order');
Route::get('/products/{category?}', [POSController::class, 'getProducts'])->name('pos.products');
Route::post('/create-order', [POSController::class, 'createOrder'])->name('pos.create-order');
Route::get('/pos/receipt/{order}', [POSController::class, 'printReceipt'])->name('pos.receipt');
Route::get('/order-history', [POSController::class, 'getOrderHistory'])->name('pos.order-history');
