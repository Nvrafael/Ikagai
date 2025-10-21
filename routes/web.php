<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NutritionistController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NutritionalPlanController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceController;

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
});

Route::middleware(['auth', 'role:admin'])->get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

Route::middleware(['auth', 'role:nutritionist'])->get('/nutritionist/dashboard', [NutritionistController::class, 'index'])->name('nutritionist.dashboard');

Route::middleware(['auth', 'role:client'])->get('/client/dashboard', [ClientController::class, 'index'])->name('client.dashboard');

// ==========================================
// RUTAS PÚBLICAS (sin autenticación)
// ==========================================

// Categorías
Route::get('/categorias', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categorias/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');

// Productos
Route::get('/productos', [ProductController::class, 'index'])->name('products.index');
Route::get('/productos/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// Servicios
Route::get('/servicios', [ServiceController::class, 'index'])->name('services.index');
Route::get('/servicios/{service:slug}', [ServiceController::class, 'show'])->name('services.show');

// ==========================================
// RUTAS AUTENTICADAS (requieren login)
// ==========================================

Route::middleware(['auth'])->group(function () {
    
    // RESEÑAS
    Route::post('/productos/{product}/resenas', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/resenas/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/resenas/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // RESERVAS (BOOKINGS)
    Route::get('/mis-reservas', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/reservar/{service}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/reservas', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/reservas/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::put('/reservas/{booking}/cancelar', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // PEDIDOS (ORDERS)
    Route::get('/mis-pedidos', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/pedidos/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/pedidos', [OrderController::class, 'store'])->name('orders.store');

    // PLANES NUTRICIONALES
    Route::get('/mis-planes', [NutritionalPlanController::class, 'index'])->name('nutritional-plans.index');
    Route::get('/planes/{nutritionalPlan}', [NutritionalPlanController::class, 'show'])->name('nutritional-plans.show');

    // MENSAJES
    Route::get('/mensajes', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/mensajes/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/mensajes', [MessageController::class, 'store'])->name('messages.store');
    Route::delete('/mensajes/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::post('/mensajes/{message}/leer', [MessageController::class, 'markAsRead'])->name('messages.read');
    Route::get('/mensajes-no-leidos', [MessageController::class, 'unreadCount'])->name('messages.unread-count');
    Route::get('/contactar-nutricionista', [MessageController::class, 'newConversation'])->name('messages.new-conversation');
});

// ==========================================
// RUTAS PARA NUTRICIONISTA
// ==========================================

Route::middleware(['auth', 'role:nutritionist,admin'])->prefix('nutricionista')->group(function () {
    
    // Gestión de Servicios
    Route::post('/servicios', [ServiceController::class, 'store'])->name('nutritionist.services.store');
    Route::put('/servicios/{service}', [ServiceController::class, 'update'])->name('nutritionist.services.update');
    Route::delete('/servicios/{service}', [ServiceController::class, 'destroy'])->name('nutritionist.services.destroy');

    // Gestión de Reservas
    Route::get('/reservas', [BookingController::class, 'adminIndex'])->name('nutritionist.bookings.index');
    Route::put('/reservas/{booking}/estado', [BookingController::class, 'updateStatus'])->name('nutritionist.bookings.update-status');

    // Gestión de Planes Nutricionales
    Route::get('/planes', [NutritionalPlanController::class, 'adminIndex'])->name('nutritionist.plans.index');
    Route::get('/planes/crear', [NutritionalPlanController::class, 'create'])->name('nutritionist.plans.create');
    Route::post('/planes', [NutritionalPlanController::class, 'store'])->name('nutritionist.plans.store');
    Route::put('/planes/{nutritionalPlan}', [NutritionalPlanController::class, 'update'])->name('nutritionist.plans.update');
    Route::delete('/planes/{nutritionalPlan}', [NutritionalPlanController::class, 'destroy'])->name('nutritionist.plans.destroy');

    // Gestión de Productos
    Route::post('/productos', [ProductController::class, 'store'])->name('nutritionist.products.store');
    Route::put('/productos/{product}', [ProductController::class, 'update'])->name('nutritionist.products.update');
    Route::delete('/productos/{product}', [ProductController::class, 'destroy'])->name('nutritionist.products.destroy');

    // Gestión de Categorías
    Route::post('/categorias', [CategoryController::class, 'store'])->name('nutritionist.categories.store');
    Route::put('/categorias/{category}', [CategoryController::class, 'update'])->name('nutritionist.categories.update');
    Route::delete('/categorias/{category}', [CategoryController::class, 'destroy'])->name('nutritionist.categories.destroy');
});

// ==========================================
// RUTAS PARA ADMINISTRADOR
// ==========================================

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    
    // Gestión de Pedidos
    Route::get('/pedidos', [OrderController::class, 'adminIndex'])->name('admin.orders.index');
    Route::put('/pedidos/{order}/estado', [OrderController::class, 'updateStatus'])->name('admin.orders.update-status');

    // Gestión de Reseñas
    Route::post('/resenas/{review}/aprobar', [ReviewController::class, 'approve'])->name('admin.reviews.approve');
});

require __DIR__.'/auth.php';
