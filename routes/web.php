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
use App\Http\Controllers\LandingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AiAssistantController;
use App\Services\AiAssistant\AIService;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingController::class, 'index'])->name('landing');


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
| AI Assistant Routes
|--------------------------------------------------------------------------
*/

Route::prefix('ai-assistant')
    ->name('ai-assistant.')
    ->middleware(['auth'])
    ->group(function () {
        // Vistas principales
        Route::get('/', [AiAssistantController::class, 'index'])->name('index');
        Route::get('/context', [AiAssistantController::class, 'businessContext'])->name('context');
        Route::get('/module/{module}', [AiAssistantController::class, 'module'])->name('module');
        Route::get('/test', [AiAssistantController::class, 'test'])->name('test');
        
        // Acciones del chat
        Route::post('/ask', [AiAssistantController::class, 'ask'])->name('ask');
        Route::get('/prompt', [AiAssistantController::class, 'prompt'])->name('prompt'); // Cambiado a GET
        
        // API / JSON Routes
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/dashboard', [AiAssistantController::class, 'dashboardJson'])->name('dashboard');
            Route::get('/module/{module}', [AiAssistantController::class, 'moduleJson'])->name('module');
            Route::get('/context', [AiAssistantController::class, 'contextJson'])->name('context');
            Route::get('/alerts', [AiAssistantController::class, 'alerts'])->name('alerts');
        });
    });

    Route::get('/test-ai', function () {
    $aiService = app(AIService::class);
    $response = $aiService->generate('Di solo: Hola, funcionando correctamente. No digas nada más.');
    
    return response()->json([
        'success' => true,
        'response' => $response,
    ]);
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
    Route::resource('customers', CustomerController::class);

    Route::resource('productions', ProductionController::class);
    Route::patch(
        '/productions/{production}/status',
        [ProductionController::class, 'changeStatus']
    )->name('productions.change-status');
    Route::patch('products/{product}/change-status', 
    [ProductController::class, 'changeStatus']
    )->name('products.change-status');

    // Asistente de IA 
    Route::get('/asistente-ia/ai-assistant', [AiAssistantController::class, 'stockCritical'])
    ->name('ai-assistant.stock-critical');

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