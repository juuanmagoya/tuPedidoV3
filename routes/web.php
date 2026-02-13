<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\InputController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SaleController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard (todos los autenticados)
|--------------------------------------------------------------------------
*/

//Route::get('/dashboard', function () {
    //return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');
Route::resource('users', UserController::class);

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated users (perfil)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

/*
|--------------------------------------------------------------------------
| Production Manager
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:production_manager'])->group(function () {

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('inputs', InputController::class);

    Route::resource('productions', ProductionController::class);
    Route::patch(
        '/productions/{production}/status',
        [ProductionController::class, 'changeStatus']
    )->name('productions.change-status');
    Route::patch('products/{product}/change-status', 
    [ProductController::class, 'changeStatus']
    )->name('products.change-status');


});

/*
|--------------------------------------------------------------------------
| Purchase Manager
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:purchase_manager'])->group(function () {

    Route::resource('suppliers', SupplierController::class);

    Route::resource('purchases', PurchaseController::class);
    Route::patch(
        '/purchases/{purchase}/status',
        [PurchaseController::class, 'changeStatus']
    )->name('purchases.change-status');

    Route::patch(
        '/purchases/{purchase}/cancel',
        [PurchaseController::class, 'cancel']
    )->name('purchases.cancel');

    Route::resource('orders', OrderController::class);
    Route::patch(
        '/orders/{order}/change-status',
        [OrderController::class, 'changeStatus']
    )->name('orders.change-status');

    Route::patch(
        '/orders/{order}/cancel',
        [OrderController::class, 'cancel']
    )->name('orders.cancel');

    Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])
    ->name('orders.invoice');

    Route::resource('sales', SaleController::class)
    ->only(['index', 'show']);

});

/*
|--------------------------------------------------------------------------
| Auth routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
