<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\BukuBesarController;
use App\Http\Controllers\CrudPermissionController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\NavbarController;
use App\Http\Controllers\OpnameController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\Saldo;
use App\Http\Controllers\SaldoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('login');
});

Route::get('/template1', function () {
    return view('template-notable');
})->name('template1');
Route::get('/template2', function () {
    return view('template-table');
})->name('template2');




Route::get('/dashboard', function () {
    return view('dashboard', ['title' => 'Administrator']);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // 
    Route::controller(NavbarController::class)->group(function () {
        Route::get('/data_master', 'data_master')->name('data_master');
        Route::get('/buku_besar', 'buku_besar')->name('buku_besar');
        Route::get('/pembelian', 'pembelian')->name('pembelian');
        Route::get('/persediaan_barang', 'persediaan_barang')->name('persediaan_barang');
    });


    Route::controller(JurnalController::class)->group(function () {
        Route::get('/jurnal', 'index')->name('jurnal');
        Route::post('/jurnal-update', 'update')->name('jurnal.update');
        Route::get('/jurnal-delete', 'delete')->name('jurnal-delete');
        Route::get('/jurnal-add', 'add')->name('jurnal.add');
        Route::get('/load_menu', 'load_menu')->name('load_menu');
        Route::get('/tambah_baris_jurnal', 'tambah_baris_jurnal')->name('tambah_baris_jurnal');
        Route::get('/export_jurnal', 'export')->name('export_jurnal');
        Route::post('/save_jurnal', 'save_jurnal')->name('save_jurnal');
        Route::get('/edit_jurnal', 'edit')->name('edit_jurnal');
        Route::post('/edit_jurnal', 'edit_save')->name('edit_jurnal');
        Route::get('/detail_jurnal', 'detail_jurnal')->name('detail_jurnal');
        Route::post('/import_jurnal', 'import_jurnal')->name('import_jurnal');
        Route::get('/saldo_akun', 'saldo_akun')->name('saldo_akun');
    });

    Route::controller(AkunController::class)->group(function () {
        Route::get('/akun', 'index')->name('akun');
        Route::post('/akun', 'create')->name('akun');
        Route::post('/akun-update', 'update')->name('akun.update');
        Route::get('/akun-delete', 'delete')->name('akun.delete');
        Route::get('/get_kode', 'get_kode')->name('get_kode');
        Route::get('/get_edit_akun', 'get_edit_akun')->name('get_edit_akun');
    });
    Route::controller(SaldoController::class)->group(function () {
        Route::get('/saldo_awal', 'index')->name('saldo_awal');
        Route::get('/saveSaldo', 'saveSaldo')->name('saveSaldo');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/user', 'index')->name('user');
        Route::post('/user-create', 'create')->name('users.create');
        Route::post('/user-update', 'update')->name('users.edit');
        Route::get('/user.delete', 'delete')->name('users.delete');
    });

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    Route::controller(ProdukController::class)
        ->prefix('produk')
        ->name('produk.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'create')->name('create');
            Route::get('/{gudang_id}', 'index')->name('detail');
            Route::get('/edit/{id_produk}', 'edit_load')->name('edit_load');
            Route::post('/edit', 'edit')->name('edit');
            Route::get('/delete/{id_produk}', 'delete')->name('delete');
        });

    Route::controller(StokMasukController::class)
        ->prefix('stok_masuk')
        ->name('stok_masuk.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/add', 'add')->name('add');
            Route::post('/create', 'create')->name('create');
            Route::get('/create_add', 'create_add')->name('create_add');
            Route::post('/store', 'store')->name('store');
            Route::get('/load', 'load_menu')->name('load_menu');
            Route::get('/{gudang_id}', 'index')->name('detail');
        });

    Route::controller(OpnameController::class)->group(function () {
        Route::get('/opname', 'index')->name('opname');
    });

    Route::controller(GudangController::class)
        ->prefix('gudang')
        ->name('gudang.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'create')->name('create');
            Route::get('/edit/{id_gudang}', 'edit_load')->name('edit_load');
            Route::post('/edit', 'edit')->name('edit');
            Route::get('/delete/{id_gudang}', 'delete')->name('delete');
        });

    Route::controller(CrudPermissionController::class)
        ->prefix('permis')
        ->name('permis.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'create')->name('create');
            Route::get('/edit/{id_permis}', 'edit')->name('edit');
        });
});

Route::controller(BukuBesarController::class)
    ->prefix('summary_buku_besar')
    ->name('summary_buku_besar.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/detail', 'detail')->name('detail');
    });

Route::controller(ProyekController::class)->group(function () {
    Route::get('/proyek', 'index')->name('proyek');
    Route::post('/proyek', 'add')->name('proyek');
    Route::get('/proyek_delete', 'delete')->name('proyek_delete');
});
