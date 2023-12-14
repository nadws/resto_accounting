<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeralatanController extends Controller
{
    public function get_data_kelompok(Request $r)
    {
        $id_kelompok = $r->id_kelompok;
        $kelompok =  DB::table('kelompok_peralatan')->where('id_kelompok', $id_kelompok)->first();
        $data = [
            'nilai_persen' => $kelompok->tarif,
            'tahun' => $kelompok->umur,
            'periode' => ucwords($kelompok->periode),
        ];
        echo json_encode($data);
    }
}