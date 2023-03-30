<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Data Produk'
        ];
        return view('persediaan_barang.produk.produk',$data);
    }
}
