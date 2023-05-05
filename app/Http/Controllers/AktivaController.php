<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AktivaController extends Controller
{
    public function index()
    {
        $data =  [
            'title' => 'Aktiva',
            'aktiva' => DB::select("SELECT * FROM aktiva as a left join kelompok_aktiva as b on b.id_kelompok = a.id_kelompok")

        ];
        return view('aktiva.index', $data);
    }

    public function add()
    {
        $data =  [
            'title' => 'Add Aktiva',
        ];
        return view('aktiva.add', $data);
    }

    public function load_aktiva()
    {
        $data =  [
            'title' => 'Add Aktiva',
            'kelompok' => DB::table('kelompok_aktiva')->get()
        ];
        return view('aktiva.load_aktiva', $data);
    }

    public function tambah_baris_aktiva(Request $r)
    {
        $data =  [
            'kelompok' => DB::table('kelompok_aktiva')->get(),
            'count' => $r->count

        ];
        return view('aktiva.tbh_baris', $data);
    }
    public function get_data_kelompok(Request $r)
    {
        $id_kelompok = $r->id_kelompok;
        $kelompok =  DB::table('kelompok_aktiva')->where('id_kelompok', $id_kelompok)->first();

        $data = [
            'nilai_persen' => $kelompok->tarif,
            'tahun' => $kelompok->umur
        ];
        echo json_encode($data);
    }

    public function save_aktiva(Request $r)
    {
        $id_kelompok = $r->id_kelompok;
        $nm_aktiva = $r->nm_aktiva;
        $tgl = $r->tgl;
        $h_perolehan = $r->h_perolehan;

        for ($x = 0; $x < count($id_kelompok); $x++) {
            $kelompok =  DB::table('kelompok_aktiva')->where('id_kelompok', $id_kelompok[$x])->first();
            $biaya_depresiasi = ($h_perolehan[$x] * $kelompok->tarif) / 12;

            $data = [
                'id_kelompok' => $id_kelompok[$x],
                'nm_aktiva' => $nm_aktiva[$x],
                'tgl' => $tgl[$x],
                'h_perolehan' => $h_perolehan[$x],
                'biaya_depresiasi' => $biaya_depresiasi,
                'admin' => Auth::user()->name,
            ];
            DB::table('aktiva')->insert($data);
        }

        return redirect()->route('aktiva')->with('sukses', 'Data berhasil ditambahkan');
    }
}
