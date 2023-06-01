<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeralatanController extends Controller
{

    public function index()
    {
        $data = [
            'title' => 'Data Peralatan',
            'peralatan' => DB::select("SELECT a.*, b.*, c.beban FROM peralatan as a 
            left join kelompok_peralatan as b on b.id_kelompok = a.id_kelompok
            left join(
            SELECT sum(c.b_penyusutan) as beban , c.id_aktiva
                FROM depresiasi_peralatan as c
                group by c.id_aktiva
            ) as c on c.id_aktiva = a.id_aktiva
            order by a.id_aktiva DESC")
        ];
        return view('persediaan_barang.peralatan.index', $data);
    }

    public function add()
    {
        $data = [
            'title' => 'Tambah Peralatan',
            'kelompok' => DB::table('kelompok_peralatan')->get()
        ];
        return view('persediaan_barang.peralatan.add', $data);
    }

    public function save_kelompok(Request $r)
    {
        DB::table('kelompok_peralatan')->insert([
            'nm_kelompok' => $r->nm_kelompok,
            'umur' => $r->umur,
            'periode' => $r->periode,
            'tarif' => $r->tarif,
            'barang_kelompok' => $r->barang_kelompok,
        ]);

        return redirect()->route('peralatan.add')->with('sukses', 'Data Kelompok Berhasil ditambahkan');
    }

    public function load_aktiva()
    {
        $data = [
            'title' => 'Aktiva',
            'kelompok' => DB::table('kelompok_peralatan')->get()
        ];
        return view('persediaan_barang.peralatan.load_aktiva', $data);
    }

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

    public function save_aktiva(Request $r)
    {
        $id_kelompok = $r->id_kelompok;
        $nm_aktiva = $r->nm_aktiva;
        $tgl = $r->tgl;
        $h_perolehan = $r->h_perolehan;

        for ($x = 0; $x < count($id_kelompok); $x++) {
            $kelompok =  DB::table('kelompok_peralatan')->where('id_kelompok', $id_kelompok[$x])->first();

            $biaya_depresiasi = $kelompok->periode === 'bulan' ? $h_perolehan[$x] / $kelompok->umur : $h_perolehan[$x] / ($kelompok->umur * 12);

            $data = [
                'id_kelompok' => $id_kelompok[$x],
                'nm_aktiva' => $nm_aktiva[$x],
                'tgl' => $tgl[$x],
                'h_perolehan' => $h_perolehan[$x],
                'biaya_depresiasi' => $biaya_depresiasi,
                'admin' => auth()->user()->name,
            ];
            DB::table('peralatan')->insert($data);
        }

        return redirect()->route('peralatan.index')->with('sukses', 'Data berhasil ditambahkan');
    }

    public function load_edit(Request $r)
    {
        $data = [
            'title' => 'asd',
            'd' => DB::table('kelompok_peralatan')->where('id_kelompok', $r->id_kelompok)->first()
        ];
        return view('persediaan_barang.peralatan.load_edit',$data);
    }

    public function edit_kelompok(Request $r)
    {
        DB::table('kelompok_edit')->where('id_kelompok', $r->id_kelompok)->update([
            'nm_kelompok' => $r->nm_kelompok,
            'umur' => $r->umur,
            'periode' => $r->periode,
            'barang_kelompok' => $r->barang_kelompok,
        ]);
        return redirect()->route('peralatan.add')->with('sukses', 'Data berhasil dihapus');
    }

    public function delete_kelompok(Request $r)
    {
        DB::table('kelompok_peralatan')->where('id_kelompok', $r->id_kelompok)->delete();
        return redirect()->route('peralatan.add')->with('sukses', 'Data berhasil dihapus');
    }
}
