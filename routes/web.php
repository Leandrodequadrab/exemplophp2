<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VeiculoController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::resource('veiculos', VeiculoController::class);
});

require __DIR__.'/settings.php';
