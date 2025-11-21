<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('products.index');
});
Route::get('/test', function () {
    return view('test');
});
Route::get('/categories', function () {
    return redirect()->route('categories.index');
});
Route::get('/sales', function () {
    return redirect()->route('sales.create');
});
// Rutas del CRUD de categorías
Route::resource('categories', CategoryController::class);
Route::resource('products', ProductController::class);
Route::resource('customers', CustomerController::class);
Route::resource('sales', SaleController::class);
// Rutas de configuración actualizadas
Route::get('settings', [SettingController::class, 'index'])->name('settings.index'); // <--- CAMBIO AQUÍ
Route::post('settings', [SettingController::class, 'update'])->name('settings.update'); // <--- CAMBIO AQUÍ

// Rutas personalizadas
Route::post('products/{product}/ajustar-stock', [ProductController::class, 'ajustarStock'])
    ->name('products.ajustarStock');

Route::get('buscar-producto', [ProductController::class, 'buscarProducto'])
    ->name('products.buscarproducto');
