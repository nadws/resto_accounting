<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SinkronController extends Controller
{
    function index(Request $r)
    {
        $tgl = date('Y-m-d', strtotime('- 1 days'));

        $data = [
            'title' => 'Data sinkron',
            'menu' => DB::selectOne("SELECT count(a.tgl) as ttl
            FROM jurnal as a 
            where a.id_buku = '3' and a.tgl = '$tgl'"),
            'tgl' => $tgl

        ];
        return view("sinkron.index", $data);
    }
}
