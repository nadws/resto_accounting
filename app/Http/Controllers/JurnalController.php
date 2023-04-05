<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Jurnal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class JurnalController extends Controller
{
    public function index(Request $r)
    {
        if (empty($r->tgl1)) {
            $tgl1 =  date('Y-m-01');
            $tgl2 =  date('Y-m-t');
        } else {
            $tgl1 =  $r->tgl1;
            $tgl2 =  $r->tgl2;
        }

        $data =  [
            'title' => 'Jurnal Umum',
            'jurnal' => Jurnal::whereBetween('tgl', [$tgl1, $tgl2])->get()

        ];
        return view('Jurnal.index', $data);
    }

    public function add()
    {
        $data =  [
            'title' => 'Jurnal Umum',

        ];
        return view('Jurnal.add', $data);
    }

    public function load_menu()
    {
        $data =  [
            'title' => 'Jurnal Umum',
            'akun' => Akun::all()

        ];
        return view('Jurnal.load_menu', $data);
    }
    public function tambah_baris_jurnal(Request $r)
    {
        $data =  [
            'title' => 'Jurnal Umum',
            'akun' => Akun::all(),
            'count' => $r->count

        ];
        return view('Jurnal.tbh_baris', $data);
    }
}
