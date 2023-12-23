<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SatuanController extends Controller
{
    function index(Request $r)
    {
        $data = [
            'title' => 'Data Satuan',
            'satuan' => DB::table('tb_satuan')->get(),
        ];
        return view('datamaster.satuan.index', $data);
    }
}
