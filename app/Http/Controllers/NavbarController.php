<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NavbarController extends Controller
{
    public function data_master()
    {
        $data = [
            [
                'judul' => 'Data User',
                'route' => 'user',
                'img' => 'team.png',
                'deskripsi' => 'ini adalah data user',
            ],
            [
                'judul' => 'Gudang',
                'route' => 'gudang.index',
                'img' => 'gudang.png',
                'deskripsi' => 'membuat dan mengelola data gudang',
            ],
            [
                'judul' => 'Data Proyek',
                'route' => 'proyek',
                'img' => 'clipboard.png',
                'deskripsi' => 'Membuat dan mengelola data proyek beserta anggaran pendapatan dan biaya.',
            ],
            [
                'judul' => 'Daftar Akun',
                'route' => 'user',
                'img' => 'accounting.png',
                'deskripsi' => 'membuat dan menyunting data rekening',
            ],
            [
                'judul' => 'Data Harta Tetap',
                'route' => 'user',
                'img' => 'asset.png',
                'deskripsi' => 'Mengelola harta tetap, akun berkaitan, dan penyusutannya menurut metode yang tersedia.',
            ],
            [
                'judul' => 'Data Satuan',
                'route' => 'user',
                'img' => 'measure-cup.png',
                'deskripsi' => 'Mengelola harta tetap, akun berkaitan, dan penyusutannya menurut metode yang tersedia.',
            ],
        ];
        $title = 'Data Master';
        return view('navbar.data_master', compact(['data', 'title']));
    }

    public function persediaan_barang()
    {
        $data = [

            [
                'judul' => 'Data Produk',
                'route' => 'produk.index',
                'img' => 'product.png',
                'deskripsi' => 'mengelola data barang',
            ],
            [
                'judul' => 'Stok Masuk',
                'route' => 'stok_masuk.index',
                'img' => 'warehouse.png',
                'deskripsi' => 'mengelola data barang masuk',
            ],
            [
                'judul' => 'Stok Opname',
                'route' => 'opname',
                'img' => 'box.png',
                'deskripsi' => 'menyelaraskan jumlah barang antara tersedia fisik dan buku',
            ],
        ];
        $title = 'Persediaan Barang';
        return view('navbar.data_master', compact(['data', 'title']));
    }
    public function buku_besar()
    {
        $data = [

            [
                'judul' => 'Daftar Akun',
                'route' => 'akun',
                'img' => 'accounting.png',
                'deskripsi' => 'membuat dan menyunting data rekening',
            ],
            [
                'judul' => 'Buku Besar',
                'route' => 'summary_buku_besar',
                'img' => 'ledger.png',
                'deskripsi' => 'Menampilkan ikhtisar jurnal dan perubahannya pada berbagai rekening.',
            ],
            [
                'judul' => 'Jurnal Umum',
                'route' => 'jurnal',
                'img' => 'newspaper.png',
                'deskripsi' => 'Mencatat berbagai transaksi keuangan dengan menetapkan langsung rekening di sisi debit dan kredit.',
            ],
        ];
        $title = 'Buku Besar';
        return view('navbar.data_master', compact(['data', 'title']));
    }

    public function pembelian()
    {
        $data = [
            [
                'judul' => 'Pengajuan Pembelian',
                'route' => 'po.index',
                'img' => 'box.png',
                'deskripsi' => 'membuat pengajuan pembelian ke pemasok',
            ],
           
        ];
        $title = 'Buku Besar';
        return view('navbar.data_master', compact(['data', 'title']));
    }
}
