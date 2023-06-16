<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class Stok_telur_alpaController extends Controller
{
    protected $tgl1, $tgl2, $period;
    public function __construct(Request $r)
    {
        if (empty($r->period)) {
            $this->tgl1 = date('Y-m-01');
            $this->tgl2 = date('Y-m-t');
        } elseif ($r->period == 'daily') {
            $this->tgl1 = date('Y-m-d');
            $this->tgl2 = date('Y-m-d');
        } elseif ($r->period == 'weekly') {
            $this->tgl1 = date('Y-m-d', strtotime("-6 days"));
            $this->tgl2 = date('Y-m-d');
        } elseif ($r->period == 'mounthly') {
            $bulan = $r->bulan;
            $tahun = $r->tahun;
            $tgl = "$tahun" . "-" . "$bulan" . "-" . "01";

            $this->tgl1 = date('Y-m-01', strtotime($tgl));
            $this->tgl2 = date('Y-m-t', strtotime($tgl));
        } elseif ($r->period == 'costume') {
            $this->tgl1 = $r->tgl1;
            $this->tgl2 = $r->tgl2;
        } elseif ($r->period == 'years') {
            $tahun = $r->tahunfilter;
            $tgl_awal = "$tahun" . "-" . "01" . "-" . "01";
            $tgl_akhir = "$tahun" . "-" . "12" . "-" . "01";

            $this->tgl1 = date('Y-m-01', strtotime($tgl_awal));
            $this->tgl2 = date('Y-m-t', strtotime($tgl_akhir));
        }
    }
    public function index(Request $r)
    {
        $tgl1 =  $this->tgl1;
        $tgl2 =  $this->tgl2;

        $data =  [
            'title' => 'Stok Telur',
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'stok' => DB::select("SELECT a.no_nota, sum(a.pcs) as pcs , sum(a.kg) as kg, a.admin, a.tgl
            FROM stok_telur_alpa as a 
            where a.tgl BETWEEN '$tgl1' and '$tgl2'
            group by a.no_nota
            order by a.urutan DESC
            ")

        ];
        return view('stok_teluralpa.index', $data);
    }

    public function detail_stok_telur_alpa(Request $r)
    {
        $data = [
            'stok' => DB::select("SELECT a.id_stok_telur_alpa, a.ket, a.tgl,c.nm_telur, a.pcs, a.kg, a.admin 
            FROM stok_telur_alpa as a 
            left join telur_produk as c on c.id_produk_telur = a.id_telur
            where a.no_nota = '$r->no_nota'
            order by a.id_stok_telur_alpa DESC"),
            'detail' => DB::selectOne("SELECT * FROM stok_telur_alpa as a where a.no_nota = '$r->no_nota'")
        ];
        return view('stok_teluralpa.detail', $data);
    }

    public function delete_transfer(Request $r)
    {
        DB::table('stok_telur_alpa')->where('no_nota', $r->no_nota)->delete();
        DB::table('stok_telur')->where('nota_transfer', $r->no_nota)->delete();

        return redirect()->route('stok_telur_alpa')->with('sukses', 'Data berhasil di dihapus');
    }
}
