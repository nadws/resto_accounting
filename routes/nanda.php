<?php

use App\Http\Controllers\AktivaController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\AtkController;
use App\Http\Controllers\BukubesarController;
use App\Http\Controllers\CashflowController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportApiInvoiceController;
use App\Http\Controllers\JurnalPenyesuaianController;
use App\Http\Controllers\NeracaController;
use App\Http\Controllers\PenutupController;
use App\Http\Controllers\PeralatanController;
use App\Http\Controllers\PersediaanController;
use App\Http\Controllers\SaldoAwalController;
use App\Http\Controllers\SaldoPenutupController;
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
            Route::get('/load', 'load')->name('load');
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
            Route::get('/export_detail', 'export_detail')->name('export_detail');
        });
    Route::controller(SaldoAwalController::class)
        ->prefix('saldoawal')
        ->name('saldoawal.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/saveSaldo', 'saveSaldo')->name('saveSaldo');
            Route::get('/export_saldo', 'export_saldo')->name('export_saldo');
        });
    Route::controller(PenutupController::class)
        ->prefix('penutup')
        ->name('penutup.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/saldo', 'saldo')->name('saldo');
            Route::get('/akun', 'akun')->name('akun');
            Route::post('/edit_akun', 'edit_akun')->name('edit_akun');
            Route::post('/cancel_penutup', 'cancel_penutup')->name('cancel_penutup');
        });
    Route::controller(AktivaController::class)
        ->prefix('aktiva')
        ->name('aktiva.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/print_aktiva', 'print_aktiva')->name('print_aktiva');
            Route::get('/add', 'add')->name('add');
            Route::get('/load_aktiva', 'load_aktiva')->name('load_aktiva');
            Route::post('/save_aktiva', 'save_aktiva')->name('save_aktiva');
            Route::get('/tambah_baris_aktiva', 'tambah_baris_aktiva')->name('tambah_baris_aktiva');
            Route::get('/get_data_kelompok', 'get_data_kelompok')->name('get_data_kelompok');
        });
    Route::controller(PeralatanController::class)
        ->prefix('peralatan')
        ->name('peralatan.')
        ->group(function () {
            Route::get('/get_data_kelompok', 'get_data_kelompok')->name('get_data_kelompok');
            Route::get('/', 'index')->name('index');
        });
    Route::controller(AtkController::class)
        ->prefix('atk')
        ->name('atk.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/save', 'save')->name('save');
            Route::get('/stok_masuk', 'stok_masuk')->name('stok_masuk');
            Route::get('/load_produk_stok', 'load_produk_stok')->name('load_produk_stok');
            Route::get('/tmbh_stok', 'tmbh_stok')->name('tmbh_stok');
            Route::get('/load_edit', 'load_edit')->name('load_edit');
            Route::post('/update', 'update')->name('update');
            Route::post('/save_stk_masuk', 'save_stk_masuk')->name('save_stk_masuk');
        });
    Route::controller(JurnalPenyesuaianController::class)
        ->prefix('jurnalpenyesuaian')
        ->name('jurnalpenyesuaian.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/aktiva', 'aktiva')->name('aktiva');
            Route::post('/save_penyesuaian_aktiva', 'save_penyesuaian_aktiva')->name('save_penyesuaian_aktiva');
            Route::get('/peralatan', 'peralatan')->name('peralatan');
            Route::post('/save_peralatan', 'save_peralatan')->name('save_peralatan');
            Route::get('/atk', 'atk')->name('atk');
            Route::post('/save_atk', 'save_atk')->name('save_atk');
        });
    Route::controller(SaldoPenutupController::class)
        ->prefix('saldopenutup')
        ->name('saldopenutup.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
        });
});
