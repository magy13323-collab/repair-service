<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Маршрут для панели управления (теперь берет данные из контроллера)
Route::get('/dashboard', [RequestController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Маршрут для сохранения заявки
Route::post('/requests', [RequestController::class, 'store'])->name('requests.store');
// Маршрут для изменения статуса
Route::patch('/requests/{id}/status', [RequestController::class, 'updateStatus'])->name('requests.update-status');
// Маршрут для назначения мастера
Route::patch('/requests/{id}/assign', [RequestController::class, 'assign'])->name('requests.assign');