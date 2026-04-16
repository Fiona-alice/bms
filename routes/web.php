<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\StockAdjustmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
->middleware(['auth'])
->name('dashboard');

Route::middleware(['auth'])->group(function () {

Route::get('/', [DashboardController::class, 'index']);

Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');

Route::resource('products', ProductController::class);

Route::get('/sales/export', [SaleController::class, 'export'])->name('sales.export');

Route::resource('sales', SaleController::class);

Route::get('/expenses/export', [ExpenseController::class, 'export'])->name('expenses.export');

Route::resource('expenses', ExpenseController::class);

Route::get('/purchases/export', [PurchaseController::class, 'export'])->name('purchases.export');

Route::resource('purchases', PurchaseController::class);

Route::get('/categories/export', [CategoryController::class, 'export'])->name('categories.export');

Route::resource('categories', CategoryController::class);

Route::get('/clients/export', [ClientController::class, 'export'])->name('clients.export');

Route::resource('clients', ClientController::class);

Route::get('/rentals/export', [RentalController::class, 'export'])->name('rentals.export');

Route::resource('rentals', RentalController::class);

Route::get('/rentals/return/{id}', [RentalController::class, 'returnItem'])->name('rentals.return');

Route::resource('users', UserController::class);

Route::resource('adjustments', StockAdjustmentController::class);

});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('users', UserController::class);
});
require __DIR__.'/auth.php';
