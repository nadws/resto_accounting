<?php

use App\Http\Controllers\OpnameController;
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
});
