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
                'route' => 'user.index',
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
                'judul' => 'Jurnal Penutup',
                'route' => 'penutup.index',
                'img' => 'penutup.png',
                'deskripsi' => 'membuat dan menyunting data rekening',
            ],

        ];
        $title = 'Data Master';
        return view('navbar.data_master', compact(['data', 'title']));
    }
}
