<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiagnosticController;
use App\Http\Controllers\VacationController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\DiscController;

Route::get('/', [AuthController::class, 'index'])->name('auth.index');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

Route::middleware(['auth', 'session.expired'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    
    Route::get('/diagnostico', [DiagnosticController::class, 'index'])->name('diagnostic.index');
    Route::get('/diagnostico/create', [DiagnosticController::class, 'create'])->name('diagnostic.create');
    Route::post('/diagnostico', [DiagnosticController::class, 'store'])->name('diagnostic.store');        
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/agradecimento', [DashboardController::class, 'agradecimento'])->name('dashboard.agradecimento');

    Route::middleware(['role:collaborator,admin'])->group(function () {
        Route::get('/usuarios', [AuthController::class, 'user'])->name('usuario.user');
        Route::get('/usuarios/edit/{user}', [AuthController::class, 'edit'])->name('usuario.edit');
        Route::patch('/usuarios/edit/{user}', [AuthController::class, 'update'])->name('usuario.update');    

        Route::get('/ferias', [VacationController::class, 'index'])->name('vacation.index'); 
        
        Route::get('/feedbacks', [FeedbackController::class, 'index'])->name('feedback.index');    

        Route::post('/mood', [DashboardController::class, 'addMoodDaily'])->name('mood.addMoodDaily');        

        Route::get('/disc', [DiscController::class, 'index'])->name('disc.index');
        Route::get('/disc/create', [DiscController::class, 'create'])->name('disc.create');
        Route::post('/disc', [DiscController::class, 'store'])->name('disc.store');
    });
    
    Route::middleware(['role:collaborator'])->group(function () {

    });

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/usuarios/create', [AuthController::class, 'create'])->name('usuario.create');
        Route::post('/usuarios', [AuthController::class, 'store'])->name('usuario.store');
        Route::delete('/usuarios/delete/{user}', [AuthController::class, 'destroy'])->name('usuario.destroy');    
        Route::get('/usuarios/{user}/respostas', [AuthController::class, 'respostas'])->name('usuario.respostas');    
        
        Route::get('/ferias/create', [VacationController::class, 'create'])->name('vacation.create');    
        Route::post('/ferias/store', [VacationController::class, 'store'])->name('vacation.store');    
        Route::get('/ferias/edit/{vacation}', [VacationController::class, 'edit'])->name('vacation.edit');    
        Route::patch('/ferias/edit/{vacation}', [VacationController::class, 'update'])->name('vacation.update');           
        Route::delete('/ferias/{vacation}/periods/{index}', [VacationController::class, 'destroyPeriod'])->name('vacation.period.destroy');    
        Route::patch('/ferias/{vacation}/markAsRead/{periodIndex}', [VacationController::class, 'markAsRead'])->name('vacations.markAsRead');

        Route::get('/feedbacks/create', [FeedbackController::class, 'create'])->name('feedback.create');    
        Route::post('/feedbacks/store', [FeedbackController::class, 'store'])->name('feedback.store');    
        Route::get('/feedbacks/edit/{feedback}', [FeedbackController::class, 'edit'])->name('feedback.edit');    
        Route::patch('/feedbacks/edit/{feedback}', [FeedbackController::class, 'update'])->name('feedback.update');    
        Route::delete('/feedbacks/delete/{feedback}', [FeedbackController::class, 'destroy'])->name('feedback.destroy');    
    });
});