<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PiutangController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Piutang AGL'
        ];
        return view('piutang.piutang',$data);
    }
}
