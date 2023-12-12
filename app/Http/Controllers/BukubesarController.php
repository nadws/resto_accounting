<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BukubesarController extends Controller
{
    protected $tgl1, $tgl2, $id_akun;
    public function __construct(Request $r)
    {
        if (empty($r->period)) {
            $this->tgl1 = date('2022-01-01');
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

        $this->id_akun = $r->id_akun;
    }
    public function index(Request $r)
    {
        $tgl1 =  $this->tgl1;
        $tgl2 =  $this->tgl2;

        $buku = DB::select("SELECT a.id_akun, a.kode_akun , a.nm_akun, b.debit , b.kredit
        FROM akun as a
        left JOIN(
            SELECT b.id_akun , sum(b.debit) as debit, sum(b.kredit) as kredit
            FROM jurnal as b
            where b.penutup = 'T' and b.tgl BETWEEN '$tgl1' and '$tgl2'
            group by b.id_akun
        ) as b on b.id_akun = a.id_akun

        -- left JOIN (
        --     SELECT c.id_akun , sum(c.debit) as debit, sum(c.kredit) as kredit
        --     FROM jurnal_saldo as c 
        --     where  c.tgl BETWEEN '$tgl1' and '$tgl2'
        --     group by c.id_akun
        -- ) as c on c.id_akun = a.id_akun
        group by a.id_akun
        ORDER by a.kode_akun ASC;
        ");

        $ditutup = DB::selectOne("SELECT * FROM `jurnal` as a WHERE tgl BETWEEN '2023-05-01' AND '2023-05-31';");

        $data =  [
            'title' => 'Buku Besar',
            'buku' => $buku,
            'penutup' => $ditutup,
            'tgl1' => $tgl1,
            'tgl2' => $tgl2

        ];
        return view('pembukuan.buku_besar.index', $data);
    }

    function detail_buku_besar(Request $r)
    {
        $tgl1 =  $r->tgl1;
        $tgl2 =  $r->tgl2;
        $data = [
            'title' => 'Detail ',
            'detail' => DB::select("SELECT d.no_cfm, d.ket as ket2, a.ket, a.tgl,a.id_akun, d.nm_akun, a.no_nota, a.debit, a.kredit, a.saldo , b.nm_post
            FROM `jurnal` as a
            left join tb_post_center as b on b.id_post_center = a.id_post_center
            LEFT JOIN (
                SELECT j.no_nota, j.id_akun, GROUP_CONCAT(DISTINCT j.no_urut SEPARATOR ', ') as no_cfm, GROUP_CONCAT(DISTINCT j.ket SEPARATOR ', ') as ket, GROUP_CONCAT(DISTINCT b.nm_akun SEPARATOR ', ') as nm_akun 
                FROM jurnal as j
                LEFT JOIN akun as b ON b.id_akun = j.id_akun
                WHERE j.id_akun != '$r->id_akun'
                GROUP BY j.no_nota
            ) d ON a.no_nota = d.no_nota AND d.id_akun != a.id_akun
            WHERE a.id_akun = '$r->id_akun' and a.tgl between '$tgl1' and '$tgl2' 
            order by a.saldo DESC, a.tgl ASC
            "),
            'id_akun' => $r->id_akun,
            'id_klasifikasi' => DB::table('akun')->where('id_akun', $r->id_akun)->first()->id_klasifikasi,
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'nm_akun' => DB::table('akun')->where('id_akun', $r->id_akun)->first()
        ];
        return view('pembukuan.buku_besar.detail', $data);
    }
}
