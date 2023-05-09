<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuplierController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Data Suplier'
        ];
        return view('suplier.suplier',$data);
    }
}
