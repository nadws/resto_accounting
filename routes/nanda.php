<?php

use App\Http\Controllers\AktivaController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\BukuBesarController;
use App\Http\Controllers\CrudPermissionController;
use App\Http\Controllers\FakturPenjualanController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\JurnalPenyesuaianController;
use App\Http\Controllers\NavbarController;
use App\Http\Controllers\PembayaranBkController;
use App\Http\Controllers\PembelianBahanBakuController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfitController;
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
        Route::get('/penjualan', 'penjualan')->name('penjualan');
        Route::get('/pembelian', 'pembelian')->name('pembelian');
        Route::get('/pembayaran', 'pembayaran')->name('pembayaran');
        Route::get('/persediaan_barang', 'persediaan_barang')->name('persediaan_barang');
        Route::get('/asset', 'asset')->name('asset');
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
        Route::get('/get_post', 'get_post')->name('get_post');
    });

    Route::controller(AkunController::class)->group(function () {
        Route::get('/akun', 'index')->name('akun');
        Route::post('/akun', 'create')->name('akun');
        Route::post('/akun-update', 'update')->name('akun.update');
        Route::get('/akun-delete', 'delete')->name('akun.delete');
        Route::get('/akun-sub', 'add_sub')->name('akun.add_sub');
        Route::get('/remove_sub', 'remove_sub')->name('akun.remove_sub');
        Route::get('/get_kode', 'get_kode')->name('get_kode');
        Route::get('/get_edit_akun/{id_akun}', 'get_edit_akun')->name('get_edit_akun');
        Route::get('/load_sub_akun/{id_akun}', 'load_sub_akun')->name('load_sub_akun');
    });
    Route::controller(SaldoController::class)->group(function () {
        Route::get('/saldo_awal', 'index')->name('saldo_awal');
        Route::get('/saveSaldo', 'saveSaldo')->name('saveSaldo');
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
            Route::post('/store', 'store')->name('store');
            Route::get('/load', 'load_menu')->name('load_menu');
            Route::get('/tbh_baris', 'tbh_baris')->name('tbh_baris');
            Route::get('/get_stok_sebelumnya', 'get_stok_sebelumnya')->name('get_stok_sebelumnya');
            Route::get('/cetak', 'cetak')->name('cetak');
            Route::get('/{gudang_id}', 'index')->name('detail');
            Route::get('/delete/{no_nota}', 'delete')->name('delete');
            Route::get('/edit/{no_nota}', 'edit')->name('edit_load');
            Route::post('/edit', 'update')->name('edit');
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
        Route::get('/export_detail', 'export_detail')->name('export_detail');
    });

Route::controller(ProyekController::class)->group(function () {
    Route::get('/proyek', 'index')->name('proyek');
    Route::post('/proyek', 'add')->name('proyek');
    Route::get('/proyek_delete', 'delete')->name('proyek_delete');
    Route::get('/proyek_selesai', 'proyek_selesai')->name('proyek_selesai');
    Route::get('/get_proyek_selesai', 'get_proyek_selesai')->name('get_proyek_selesai');
});
Route::controller(FakturPenjualanController::class)->group(function () {
    Route::get('/faktur_penjualan', 'index')->name('faktur_penjualan');
});

Route::controller(ProfitController::class)->group(function () {
    Route::get('/profit', 'index')->name('profit');
    Route::get('/profit_print', 'print')->name('profit_print');
});

Route::controller(AktivaController::class)->group(function () {
    Route::get('/aktiva', 'index')->name('aktiva');
    Route::get('/aktiva.add', 'add')->name('aktiva.add');
    Route::get('/load_aktiva', 'load_aktiva')->name('load_aktiva');
    Route::get('/tambah_baris_aktiva', 'tambah_baris_aktiva')->name('tambah_baris_aktiva');
    Route::get('/get_data_kelompok', 'get_data_kelompok')->name('get_data_kelompok');
    Route::post('/save_aktiva', 'save_aktiva')->name('save_aktiva');
    Route::get('/print_aktiva', 'print')->name('print_aktiva');
});

// Route::controller(JurnalPenyesuaianController::class)->group(function () {
//     Route::get('/jurnal_penyesuaian', 'index')->name('jurnal_penyesuaian');
//     Route::get('/jurnal_aktiva', 'jurnal')->name('jurnal_aktiva');
//     Route::post('/save_penyesuaian_aktiva', 'save_penyesuaian_aktiva')->name('save_penyesuaian_aktiva');
// });
Route::controller(PembelianBahanBakuController::class)->group(function () {
    Route::get('/pembelian_bk', 'index')->name('pembelian_bk');
    Route::get('/pembelian_bk.add', 'add')->name('pembelian_bk.add');
    Route::get('/get_satuan_produk', 'get_satuan_produk')->name('get_satuan_produk');
    Route::get('/tambah_baris_bk', 'tambah_baris_bk')->name('tambah_baris_bk');
    Route::post('/save_pembelian_bk', 'save_pembelian_bk')->name('save_pembelian_bk');
    Route::get('/print_bk', 'print')->name('print_bk');
    Route::get('/delete_bk', 'delete_bk')->name('delete_bk');
    Route::get('/edit_pembelian_bk', 'edit_pembelian_bk')->name('edit_pembelian_bk');
    Route::post('/edit_pembelian_bk', 'edit_save')->name('edit_pembelian_bk');
    Route::post('/grading', 'grading')->name('grading');
    Route::post('/approve_invoice_bk', 'approve_invoice_bk')->name('approve_invoice_bk');
    Route::get('/get_grading', 'get_grading')->name('get_grading');
});

Route::controller(PembayaranBkController::class)->group(function () {
    Route::get('/pembayaranbk', 'index')->name('pembayaranbk');
    Route::get('/pembayaranbk.add', 'add')->name('pembayaranbk.add');
    Route::get('/pembayaranbk.tambah', 'tambah')->name('pembayaranbk.tambah');
    Route::post('/pembayaranbk.save_pembayaran', 'save_pembayaran')->name('pembayaranbk.save_pembayaran');
    Route::get('/pembayaranbk.edit', 'edit')->name('pembayaranbk.edit');
    Route::post('/pembayaranbk.save_edit', 'save_edit')->name('pembayaranbk.save_edit');
});
