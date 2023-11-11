<?php

namespace App\Http\Controllers;

use App\Models\NeracaModel;
use Illuminate\Http\Request;

class NeracaController extends Controller
{
    function index(Request $r)
    {
        $tgl1 =  '2020-01-01';
        $tgl2 = $r->tgl2;
        $data = [
            'kas' => NeracaModel::Getaktiva_tetap($tgl1, $tgl2, 1),
            'bank' => NeracaModel::Getaktiva_tetap($tgl1, $tgl2, 2),
            'piutang' => NeracaModel::Getaktiva_tetap($tgl1, $tgl2, 3),
            'persediaan' => NeracaModel::Getaktiva_tetap($tgl1, $tgl2, 4),
            'hutang' => NeracaModel::Getaktiva_tetap($tgl1, $tgl2, 5),
        ];
        return view('neraca.index', $data);
    }
}
