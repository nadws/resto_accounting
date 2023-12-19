<?php

namespace App\Http\Controllers;

use App\Models\NeracaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NeracaController extends Controller
{
    function index(Request $r)
    {
        $tahun =  $r->tahun ?? date('Y');

        $data = [
            'title' => 'Laporan Neraca',
            'tahun' => DB::select("SELECT YEAR(a.tgl) as tahun FROM jurnal as a where YEAR(a.tgl) != 0 group by YEAR(a.tgl);"),
            'thn' => $tahun,
            'bulans' => DB::table('bulan')->get(),

        ];
        return view('neraca.index', $data);
    }
}
