<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StokTelurMtdController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Stok Telur MTD'
        ];
        return view('stok_telur_mtd.stok_telur', $data);
    }
}
