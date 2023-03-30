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
                'judul' => 'Daftar Akun',
                'route' => 'user',
                'img' => 'accounting.png',
                'deskripsi' => 'membuat dan menyunting data rekening',
            ],
            [
                'judul' => 'Data User',
                'route' => 'user',
                'img' => 'team.png',
                'deskripsi' => 'ini adalah data user',
            ],
            [
                'judul' => 'Daftar Akun',
                'route' => 'user',
                'img' => 'accounting.png',
                'deskripsi' => 'membuat dan menyunting data rekening',
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
                'route' => 'produk',
                'img' => 'product.png',
                'deskripsi' => 'mengelola data barang',
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
}
