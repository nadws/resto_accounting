<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NavbarController extends Controller
{
    public function buku_besar()
    {
        $data = [
            [
                'judul' => 'Daftar Akun',
                'route' => 'akun.index',
                'img' => 'invoice.png',
                'deskripsi' => 'membuat dan menyunting data rekening',
            ],

        ];
        $title = 'Data Master';
        return view('navbar.data_master', compact(['data', 'title']));
    }
    public function pembukuan()
    {
        $data = [

            [
                'judul' => 'Saldo Awal',
                'route' => 'saldoawal.index',
                'img' => 'report.png',
                'deskripsi' => 'membuat dan menyunting data rekening',
            ],
            [
                'judul' => 'Jurnal',
                'route' => 'jurnal.index',
                'img' => 'invoice.png',
                'deskripsi' => 'membuat dan menyunting data rekening',
            ],
            [
                'judul' => 'Buku Besar',
                'route' => 'bukubesar.index',
                'img' => 'ledger.png',
                'deskripsi' => 'membuat dan menyunting data rekening',
            ],
            [
                'judul' => 'Jurnal Penyesuaian',
                'route' => 'jurnalpenyesuaian.index',
                'img' => 'journalism.png',
                'deskripsi' => 'membuat dan menyunting data rekening',
            ],
            [
                'judul' => 'Jurnal Penutup',
                'route' => 'penutup.index',
                'img' => 'penutup.png',
                'deskripsi' => 'membuat dan menyunting data rekening',
            ],
            [
                'judul' => 'Saldo Penutup',
                'route' => 'saldopenutup.index',
                'img' => 'law-book.png',
                'deskripsi' => 'membuat dan menyunting data rekening',
            ],

        ];
        $title = 'Data Master';
        return view('navbar.data_master', compact(['data', 'title']));
    }
    public function persediaan()
    {
        $data = [
            [
                'judul' => 'Aktiva',
                'route' => 'aktiva.index',
                'img' => 'asset.png',
                'deskripsi' => 'membuat dan menyunting data rekening',
            ],
            [
                'judul' => 'Peralatan',
                'route' => 'peralatan.index',
                'img' => 'peralatan.png',
                'deskripsi' => 'membuat dan menyunting data rekening',
            ],
            [
                'judul' => 'ATK',
                'route' => 'atk.index',
                'img' => 'stationery.png',
                'deskripsi' => 'membuat dan menyunting data rekening',
            ],
            [
                'judul' => 'Persediaan Bahan Makanan',
                'route' => 'bahan.index',
                'img' => 'grocery.png',
                'deskripsi' => 'membuat dan menyunting data rekening',
            ],

        ];
        $title = 'Data Master';
        return view('navbar.data_master', compact(['data', 'title']));
    }
    public function datamenu()
    {
        $data = [
            [
                'judul' => 'Data Menu',
                'route' => 'menu.index',
                'img' => 'menu.png',
                'deskripsi' => 'membuat dan menyunting data rekening',
            ],

        ];
        $title = 'Data Master';
        return view('navbar.data_master', compact(['data', 'title']));
    }
}
