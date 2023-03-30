<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\NavbarController;
use App\Http\Controllers\OpnameController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/template1', function () {
    return view('template-notable');
})->name('template1');
Route::get('/template2', function () {
    return view('template-table');
})->name('template2');




Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // 
    Route::controller(NavbarController::class)->group(function () {
        Route::get('/data_master', 'data_master')->name('data_master');
        Route::get('/persediaan_barang', 'persediaan_barang')->name('persediaan_barang');
    });

    
    Route::controller(JurnalController::class)->group(function () {
        Route::get('/jurnal', 'index')->name('jurnal');
        Route::post('/jurnal-create', 'create')->name('jurnal.create');
        Route::post('/jurnal-update', 'update')->name('jurnal.update');
        Route::get('/jurnal-delete', 'delete')->name('jurnal.delete');
    });

    Route::controller(AkunController::class)->group(function () {
        Route::get('/akun', 'index')->name('akun');
        Route::post('/akun-create', 'create')->name('akun.create');
        Route::post('/akun-update', 'update')->name('akun.update');
        Route::get('/akun-delete', 'delete')->name('akun.delete');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/user', 'index')->name('user');
        Route::post('/user-create', 'create')->name('user.create');
        Route::post('/user-update', 'update')->name('user.update');
        Route::get('/user-delete', 'delete')->name('user.delete');
    });

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    Route::controller(ProdukController::class)->group(function () {
        Route::get('/produk', 'index')->name('produk');
    });

    Route::controller(OpnameController::class)->group(function () {
        Route::get('/opname', 'index')->name('opname');
    });


});

require __DIR__ . '/auth.php';
