<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Produk_telurController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Data Produk Telur',

        ];
        return view('produk_telur.index', $data);
    }
}
