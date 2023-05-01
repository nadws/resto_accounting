<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FakturPenjualanController extends Controller
{
    public function index(Request $r)
    {
        $data =  [
            'title' => 'Faktur Penjualan',

        ];
        return view('faktur_penjualan.index', $data);
    }
}
