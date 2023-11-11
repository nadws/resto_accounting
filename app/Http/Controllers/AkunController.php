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
                ->orderBy('id_akun', 'DESC')
                ->get()
        ];
        return view('akun.index', $data);
    }

    function save(Request $r)
    {
        $data = [
            'kode_akun' => $r->kode_akun,
            'nm_akun' => $r->nm_akun,
            'id_klasifikasi' => $r->id_klasifikasi,
        ];

        DB::table('akun')->insert($data);
    }
}
