<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiagnosticController;
use App\Http\Controllers\VacationController;

Route::get('/', [AuthController::class, 'index'])->name('auth.index');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/usuarios', [AuthController::class, 'user'])->name('usuario.user');
    Route::get('/usuarios/edit/{user}', [AuthController::class, 'edit'])->name('usuario.edit');
    Route::patch('/usuarios/edit/{user}', [AuthController::class, 'update'])->name('usuario.update');
    
    Route::middleware(['user'])->group(function () {
        Route::get('/diagnostico', [DiagnosticController::class, 'index'])->name('diagnostic.index');
        Route::post('/diagnostico', [DiagnosticController::class, 'store'])->name('diagnostic.store');
    });

    Route::middleware(['admin'])->group(function () {
        Route::get('/usuarios/create', [AuthController::class, 'create'])->name('usuario.create');
        Route::post('/usuarios', [AuthController::class, 'store'])->name('usuario.store');
        Route::delete('/usuarios/delete/{user}', [AuthController::class, 'destroy'])->name('usuario.destroy');    
        Route::get('/usuarios/{user}/respostas', [AuthController::class, 'respostas'])->name('usuario.respostas');    
        Route::get('/ferias', [VacationController::class, 'index'])->name('vacation.index');    
    });
});