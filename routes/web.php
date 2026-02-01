<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;

// Rota inicial
Route::get('/', function () {
    return view('welcome');
});

// Agrupamento de rotas protegidas por login
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    
    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD de Transações (Simplicash)
    Route::resource('transactions', TransactionController::class);

    // Rotas de Perfil (do Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';