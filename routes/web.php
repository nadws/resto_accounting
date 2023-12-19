<?php

use App\Http\Controllers\BahanController;
use App\Http\Controllers\CashflowController;
use App\Http\Controllers\JurnalAktivaController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\NavbarController;
use App\Http\Controllers\PeralatanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('hal_awal.index');
});
Route::get('/login', function () {
    return redirect('login');
});
Route::middleware('auth')->group(function () {
    Route::get('/template1', function () {
        return view('template-notable');
    })->name('template1');

    // Route::get('/dashboard', function () {
    //     return view('template-notable');
    // })->name('dashboard');

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
        Route::get('/pembukuan', 'pembukuan')->name('pembukuan');
        Route::get('/persediaan', 'persediaan')->name('persediaan');
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

    Route::controller(JurnalController::class)
        ->prefix('jurnal')
        ->name('jurnal.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/add', 'add')->name('add');
            Route::get('/load_menu', 'load_menu')->name('load_menu');
            Route::get('/get_post', 'get_post')->name('get_post');
            Route::get('/edit_jurnal', 'edit_jurnal')->name('edit_jurnal');
        Route::get('/export_jurnal', 'export_jurnal')->name('export_jurnal');
            Route::post('/update_jurnal', 'update_jurnal')->name('update_jurnal');
            Route::get('/tambah_baris_jurnal', 'tambah_baris_jurnal')->name('tambah_baris_jurnal');
            Route::get('/delete', 'delete')->name('delete');
            Route::post('/create', 'create')->name('create');
        });
    Route::controller(JurnalAktivaController::class)
        ->prefix('jurnal')
        ->name('jurnal.')
        ->group(function () {
            Route::get('/add_balik_aktiva', 'add_balik_aktiva')->name('add_balik_aktiva');
            Route::get('/get_total_post', 'get_total_post')->name('get_total_post');
            Route::get('/cek_aktiva', 'cek_aktiva')->name('cek_aktiva');
            Route::get('/cek_aktiva', 'cek_aktiva')->name('cek_aktiva');
            Route::get('/get_post_pembalikan', 'get_post_pembalikan')->name('get_post_pembalikan');
            Route::get('/get_data_kelompok', 'get_data_kelompok')->name('get_data_kelompok');
            Route::post('/save_aktiva', 'save_aktiva')->name('save_aktiva');
            Route::post('/save_atk_pembalik', 'save_atk_pembalik')->name('save_atk_pembalik');
            Route::post('/save_jurnal_aktiva', 'save_jurnal_aktiva')->name('save_jurnal_aktiva');

        });
    Route::controller(PeralatanController::class)
        ->prefix('peralatan')
        ->name('peralatan.')
        ->group(function () {
            Route::post('/save_aktiva', 'save_aktiva')->name('save_aktiva');

        });
    Route::controller(ProfitController::class)
        ->prefix('profit')
        ->name('profit.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/createAkun', 'createAkun')->name('createAkun');
            Route::get('/importLaporan', 'importLaporan')->name('importLaporan');
            Route::get('/loadListAkunProfit', 'loadListAkunProfit')->name('loadListAkunProfit');
            Route::get('/loadEdit', 'loadEdit')->name('loadEdit');
            Route::get('/updateAkun', 'updateAkun')->name('updateAkun');
            Route::get('/hapusAkun', 'hapusAkun')->name('hapusAkun');
        });
    Route::controller(BahanController::class)
        ->prefix('bahan')
        ->name('bahan.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
        });
});

require __DIR__ . '/auth.php';
require __DIR__ . '/nanda.php';
