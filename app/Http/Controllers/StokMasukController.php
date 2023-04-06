<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StokMasukController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Stok Masuk'
        ];
        return view('persediaan_barang.stok_masuk.stok_masuk',$data);
    }
}
