<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeralatanController extends Controller
{
    protected $tgl1, $tgl2, $id_proyek, $period, $id_buku;
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
            $tglawal = "$tahun" . "-" . "$bulan" . "-" . "01";
            $tglakhir = "$tahun" . "-" . "$bulan" . "-" . "01";

            $this->tgl1 = date('Y-m-01', strtotime($tglawal));
            $this->tgl2 = date('Y-m-t', strtotime($tglakhir));
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

        $this->id_proyek = $r->id_proyek ?? 0;
        $this->id_buku = $r->id_buku ?? 2;
    }

    public function index()
    {
        $tgl1 =  $this->tgl1;
        $tgl2 =  $this->tgl2;
        $id_proyek = $this->id_proyek;
        $data = [
            'title' => 'Data Peralatan',
            'peralatan' => DB::select("SELECT a.*, b.*, c.beban FROM peralatan as a 
            left join kelompok_peralatan as b on b.id_kelompok = a.id_kelompok
            left join(
            SELECT sum(c.b_penyusutan) as beban , c.id_aktiva
                FROM depresiasi_peralatan as c
                group by c.id_aktiva
            ) as c on c.id_aktiva = a.id_aktiva
            order by a.id_aktiva DESC"),
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'id_proyek' => $id_proyek,
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

    public function delete_peralatan(Request $r)
    {
        $cek = DB::table('depresiasi_peralatan')->where('id_aktiva', $r->id_peralatan)->first();
        if(!$cek) {
            DB::table('peralatan')->where('id_aktiva', $r->id_aktiva)->delete();
            $status = 'sukses';
            $pesan = 'Data berhasil di hapus';
        }
        return redirect()->route('peralatan.index')->with($status ?? 'error', $pesan ?? 'Gagal dihapus ! peralatan tersedia di depresiasi');
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
