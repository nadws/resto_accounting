<?php

namespace App\Http\Controllers;

use App\Models\NeracaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NeracaController extends Controller
{
    function index(Request $r)
    {
        $tgl1 =  '2020-01-01';

        if (empty($r->bulan)) {
            $tgl2 = date('Y-m-t');
        } else {
            $bln = $r->tahun . '-' . $r->bulan . '-' . '01';
            $tgl2 = date('Y-m-t', strtotime($bln));
        }
        $bulin = date('m', strtotime($tgl2));
        $data = [
            'kas' => NeracaModel::Getaktiva_tetap($tgl1, $tgl2, ['1']),
            'bank' => NeracaModel::Getaktiva_tetap($tgl1, $tgl2, ['2']),
            'piutang' => NeracaModel::Getaktiva_tetap($tgl1, $tgl2, ['3']),
            'persediaan' => NeracaModel::Getaktiva_tetap($tgl1, $tgl2, ['4']),
            'hutang' => NeracaModel::Getaktiva_tetap($tgl1, $tgl2, ['5']),
            'aktiva_tetap' => NeracaModel::Getaktiva_tetap($tgl1, $tgl2, ['6', '7']),
            'akumlasi_aktiva' => NeracaModel::Getaktiva_tetap($tgl1, $tgl2, ['8']),
            'ekuitas' => NeracaModel::Getaktiva_tetap($tgl1, $tgl2, ['9']),
            'bulan' => DB::table('bulan')->get(),
            'bulan_values' => $bulin

        ];
        return view('neraca.index', $data);
    }
}
