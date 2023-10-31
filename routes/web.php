<?php

use App\Http\Controllers\CashflowController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('login');
});
Route::middleware('auth')->group(function () {
    Route::get('/template1', function () {
        return view('template-notable');
    })->name('template1');

    Route::get('/dashboard', function () {
        return view('template-notable');
    })->name('dashboard');

    Route::get('/template2', function () {
        return view('template-table');
    })->name('template2');

    Route::get('/403', function () {
        view('error.403');
    })->name('403');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });


    Route::controller(NavbarController::class)->group(function () {
        Route::get('/buku_besar', 'buku_besar')->name('buku_besar');
    });

    Route::controller(CashflowController::class)
        ->prefix('cashflow')
        ->name('cashflow.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/add', 'add')->name('add');
            Route::get('/edit', 'edit')->name('edit');
            Route::post('/update', 'update')->name('update');
            Route::get('/keluar', 'keluar')->name('keluar');
            Route::post('/destroy', 'destroy')->name('destroy');
        });
    Route::controller(UserController::class)
        ->prefix('user')
        ->name('user.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/create', 'create')->name('create');
            Route::get('/update', 'update')->name('update');
            Route::get('/delete', 'delete')->name('delete');
            Route::get('/edit', 'edit')->name('edit');
        });
});

require __DIR__ . '/auth.php';
