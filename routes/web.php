<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

// 1. Redirecionamento Inicial
Route::get('/', function () {
    return redirect('/login');
});

// 2. Rotas Protegidas (Precisa estar logado)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil do Usuário
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CATEGORIAS (Uma única linha resolve tudo)
    // Isso cria: categories.index, categories.store, categories.edit, categories.update, categories.destroy
    Route::resource('categories', CategoryController::class);

    // TRANSAÇÕES
    Route::resource('transactions', TransactionController::class);
});

require __DIR__.'/auth.php';