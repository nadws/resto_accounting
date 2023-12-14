<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\BukubesarController;
use App\Http\Controllers\CashflowController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportApiInvoiceController;
use App\Http\Controllers\NeracaController;
use App\Http\Controllers\PenutupController;
use App\Http\Controllers\SaldoAwalController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


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
            Route::get('/post_center', 'post_center')->name('post_center');
            Route::get('/create_post_center', 'create_post_center')->name('create_post_center');
            Route::get('/update_post_center', 'update_post_center')->name('update_post_center');
            Route::get('/delete_post_center', 'delete_post_center')->name('delete_post_center');
            Route::get('/edit_post', 'edit_post')->name('edit_post');
            Route::post('/save', 'save')->name('save');
        });
    Route::controller(CashflowController::class)
        ->prefix('cashflow')
        ->name('cashflow.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
        });
    Route::controller(ImportApiInvoiceController::class)
        ->prefix('importapi')
        ->name('importapi.')
        ->group(function () {
            Route::get('/invoice', 'invoice')->name('invoice');
        });
    Route::controller(BukubesarController::class)
        ->prefix('bukubesar')
        ->name('bukubesar.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/detail_buku_besar', 'detail_buku_besar')->name('detail_buku_besar');
        });
    Route::controller(SaldoAwalController::class)
        ->prefix('saldoawal')
        ->name('saldoawal.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/saveSaldo', 'saveSaldo')->name('saveSaldo');
        });
    Route::controller(PenutupController::class)
        ->prefix('penutup')
        ->name('penutup.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/saldo', 'saldo')->name('saldo');
        });
});
