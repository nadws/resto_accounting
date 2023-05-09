<?php

use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\CashflowController;
use App\Http\Controllers\OpnameController;
use App\Http\Controllers\PenutupController;
use App\Http\Controllers\PoController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::controller(PoController::class)
        ->prefix('po')
        ->name('po.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/add', 'add')->name('add');
            Route::get('/load_view_add', 'load_view_add')->name('load_view_add');
            Route::get('/tbh_baris', 'tbh_baris')->name('tbh_baris');
            Route::post('/', 'create')->name('create');
            Route::get('/{gudang_id}', 'index')->name('detail');
            Route::get('/edit/{id_produk}', 'edit_load')->name('edit_load');
            Route::post('/edit', 'edit')->name('edit');
            Route::get('/delete/{id_produk}', 'delete')->name('delete');
        });

    Route::controller(OpnameController::class)
        ->prefix('opname')
        ->name('opname.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/add', 'add')->name('add');
            Route::get('/add/{gudang_id}', 'add')->name('add_detail');
            Route::get('/delete/{no_nota}', 'delete')->name('delete');
            Route::post('/', 'save')->name('save');
            Route::get('/cetak', 'cetak')->name('cetak');
            Route::get('/edit/{no_nota}', 'edit')->name('edit');
            Route::post('/update', 'update')->name('update');
            Route::get('/detail/{no_nota}', 'detail')->name('detail');
            Route::get('/{gudang_id}', 'index')->name('detail');
        });

    Route::controller(BahanBakuController::class)
        ->prefix('bahan_baku')
        ->name('bahan_baku.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/create', 'create')->name('create');
            Route::get('/stok_masuk', 'stokMasuk')->name('stok_masuk');
            Route::get('/add', 'add')->name('stok_masuk_add');
            Route::get('/opname', 'opname')->name('opname');
            Route::post('/edit', 'edit')->name('edit');
            Route::post('/store', 'store')->name('store');
            Route::get('/load_menu', 'load_menu')->name('load_menu');
            Route::get('/tbh_baris', 'tbh_baris')->name('tbh_baris');
            Route::get('/opname', 'opname')->name('opname');
            Route::get('/opname/add', 'opname_add')->name('opname.add');
            Route::post('/opname/add', 'opname_store')->name('opname.save');
            Route::get('/opname/add/{gudang_id}', 'opname_add')->name('opname.add_detail');
            Route::get('/opname/cetak/{no_nota}', 'opname_cetak')->name('opname.cetak');
            Route::get('/opname/detail/{gudang_id}', 'opname_detail')->name('opname.detail');
            Route::get('/stok_masuk/{gudang_id}', 'stokMasuk')->name('stok_masuk_segment');
            Route::get('/{gudang_id}', 'index')->name('detail');
            Route::get('/edit/{id_produk}', 'edit_load')->name('edit_load');
            Route::get('/opname/{gudang_id}', 'opname')->name('opname.detail');
        });

    Route::controller(CashflowController::class)
        ->prefix('cashflow')
        ->name('cashflow.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/load', 'load')->name('load');
            Route::get('/loadSubKategori', 'loadSubKategori')->name('loadSubKategori');
            Route::get('/editSubKategori', 'editSubKategori')->name('editSubKategori');
            Route::get('/saveSubKategori', 'saveSubKategori')->name('saveSubKategori');
        });

    Route::controller(PenutupController::class)
        ->prefix('penutup')
        ->name('penutup.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/saldo', 'saldo')->name('saldo');
            Route::get('/history', 'history')->name('history');
        });


});
