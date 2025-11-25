<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\ServiceApiController;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\CategoryApiController;

// ==========================================
// RUTAS PÚBLICAS DE LA API
// ==========================================

// Productos
Route::prefix('products')->group(function () {
    Route::get('/', [ProductApiController::class, 'index'])->name('api.products.index');
    Route::get('/search', [ProductApiController::class, 'quickSearch'])->name('api.products.search');
    Route::get('/{id}', [ProductApiController::class, 'show'])->name('api.products.show');
    Route::post('/{id}/check-stock', [ProductApiController::class, 'checkStock'])->name('api.products.check-stock');
});

// Servicios
Route::prefix('services')->group(function () {
    Route::get('/', [ServiceApiController::class, 'index'])->name('api.services.index');
    Route::get('/{id}', [ServiceApiController::class, 'show'])->name('api.services.show');
});

// Categorías
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryApiController::class, 'index'])->name('api.categories.index');
    Route::get('/{id}', [CategoryApiController::class, 'show'])->name('api.categories.show');
});

// Carrito de compras (sin autenticación, usa sesión)
Route::prefix('cart')->group(function () {
    Route::get('/', [CartApiController::class, 'index'])->name('api.cart.index');
    Route::post('/add', [CartApiController::class, 'add'])->name('api.cart.add');
    Route::put('/{productId}', [CartApiController::class, 'update'])->name('api.cart.update');
    Route::delete('/{productId}', [CartApiController::class, 'remove'])->name('api.cart.remove');
    Route::delete('/', [CartApiController::class, 'clear'])->name('api.cart.clear');
});

// ==========================================
// RUTAS PROTEGIDAS (requieren autenticación)
// ==========================================

Route::middleware('auth:sanctum')->group(function () {
    // Usuario actual
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    });

    // Aquí puedes agregar más rutas protegidas en el futuro
    // Por ejemplo: pedidos, favoritos, perfil, etc.
});
