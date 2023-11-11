<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NeracaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('login');
});
Route::middleware('auth')->group(function () {
    Route::controller(DashboardController::class)
        ->prefix('dashboard')
        ->name('dashboard.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
        });
    Route::controller(NeracaController::class)
        ->prefix('neraca')
        ->name('neraca.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
        });
    Route::controller(AkunController::class)
        ->prefix('akun')
        ->name('akun.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
        });
});
