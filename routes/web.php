<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\InputController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\PurchaseController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('inputs', InputController::class);
    Route::resource('productions', ProductionController::class);
    Route::patch('/productions/{production}/status', 
    [ProductionController::class, 'changeStatus']
    )->name('productions.change-status');
    Route::resource('purchases', PurchaseController::class);
    Route::patch('/purchases/{purchase}/status',
    [PurchaseController::class, 'changeStatus']
    )->name('purchases.change-status');
    Route::patch(
    '/purchases/{purchase}/cancel',
    [PurchaseController::class, 'cancel']
    )->name('purchases.cancel');


});

require __DIR__.'/auth.php';
