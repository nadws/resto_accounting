<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AkunController extends Controller
{
    function index(Request $r)
    {
        $data = [
            'akun' => DB::table('akun as a')
                ->join('subklasifikasi_akun as b', 'a.id_klasifikasi', 'b.id_subklasifikasi_akun')
                ->where('is_active', 'Y')
                ->get()
        ];
        return view('akun.index', $data);
    }
}
