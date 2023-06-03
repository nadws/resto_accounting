<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class NeracaController extends Controller
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
    }
    public function index(Request $r)
    {
        $tgl1 =  $this->tgl1;
        $tgl2 =  $this->tgl2;
        $data = [
            'title' => 'Laporan Neraca',
            'tgl1' => $tgl1,
            'tgl2' => $tgl2
        ];
        return view('neraca.index', $data);
    }

    public function loadneraca(Request $r)
    {
        $tgl1 =  $r->tgl1;
        $tgl2 = $r->tgl2;
        $aktiva_lancar = DB::select("SELECT a.id_sub_ketagori_neraca , a.nama_sub_kategori , b.debit, b.kredit
        FROM sub_kategori_neraca as a 
        left join (
        SELECT b.id_sub_kategori, sum(c.debit) debit, sum(c.kredit) as kredit
            FROM akun_neraca as b
            left join (
            SELECT c.id_akun , sum(c.debit) as debit, sum(c.kredit) as kredit
                FROM jurnal as c 
                where c.tgl BETWEEN '$tgl1' and '$tgl2'
                group by c.id_akun
            ) as c on c.id_akun = b.id_akun
            group by b.id_sub_kategori
        ) as b on b.id_sub_kategori  = a.id_sub_ketagori_neraca
        where a.id_kategori  ='1';");
        $aktiva_tetap = DB::select("SELECT a.id_sub_ketagori_neraca , a.nama_sub_kategori , b.debit, b.kredit
        FROM sub_kategori_neraca as a 
        left join (
        SELECT b.id_sub_kategori, sum(c.debit) debit, sum(c.kredit) as kredit
            FROM akun_neraca as b
            left join (
            SELECT c.id_akun , sum(c.debit) as debit, sum(c.kredit) as kredit
                FROM jurnal as c 
                where c.tgl BETWEEN '$tgl1' and '$tgl2'
                group by c.id_akun
            ) as c on c.id_akun = b.id_akun
            group by b.id_sub_kategori
        ) as b on b.id_sub_kategori  = a.id_sub_ketagori_neraca
        where a.id_kategori  ='3';");

        $hutang = DB::select("SELECT a.id_sub_ketagori_neraca , a.nama_sub_kategori , b.debit, b.kredit
        FROM sub_kategori_neraca as a 
        left join (
        SELECT b.id_sub_kategori, sum(c.debit) debit, sum(c.kredit) as kredit
            FROM akun_neraca as b
            left join (
            SELECT c.id_akun , sum(c.debit) as debit, sum(c.kredit) as kredit
                FROM jurnal as c 
                where c.tgl BETWEEN '$tgl1' and '$tgl2'
                group by c.id_akun
            ) as c on c.id_akun = b.id_akun
            group by b.id_sub_kategori
        ) as b on b.id_sub_kategori  = a.id_sub_ketagori_neraca
        where a.id_kategori  ='2';");

        $ekuitas = DB::select("SELECT a.id_sub_ketagori_neraca , a.nama_sub_kategori , b.debit, b.kredit
        FROM sub_kategori_neraca as a 
        left join (
        SELECT b.id_sub_kategori, sum(c.debit) debit, sum(c.kredit) as kredit
            FROM akun_neraca as b
            left join (
            SELECT c.id_akun , sum(c.debit) as debit, sum(c.kredit) as kredit
                FROM jurnal as c 
                where c.tgl BETWEEN '$tgl1' and '$tgl2'
                group by c.id_akun
            ) as c on c.id_akun = b.id_akun
            group by b.id_sub_kategori
        ) as b on b.id_sub_kategori  = a.id_sub_ketagori_neraca
        where a.id_kategori  ='4';");

        $akumulasi_aktiva = DB::selectOne("SELECT sum(a.b_penyusutan) as total_akumulasi FROM depresiasi_aktiva as a");

        $data = [
            'aktiva_lancar' => $aktiva_lancar,
            'aktiva_tetap' => $aktiva_tetap,
            'hutang' => $hutang,
            'ekuitas' => $ekuitas,
            'akumulasi' => $akumulasi_aktiva
        ];
        return view('neraca.load', $data);
    }

    public function loadinputSub_neraca(Request $r)
    {
        $data = [
            'subkategori' => DB::table('sub_kategori_neraca')->where('id_kategori', $r->kategori)->get(),
            'kategori' => $r->kategori
        ];
        return view('neraca.inputSub', $data);
    }

    public function saveSub_neraca(Request $r)
    {
        $data = [
            'nama_sub_kategori' => $r->nama_sub_kategori,
            'id_kategori' => $r->kategori,
            'urutan' => $r->urutan
        ];
        DB::table('sub_kategori_neraca')->insert($data);
    }

    public function loadinputAkun_neraca(Request $r)
    {
        $data = [
            'akun_neraca' => DB::select("SELECT a.id_akun_neraca, a.id_akun, b.nm_akun, c.debit , c.kredit
            FROM akun_neraca as a
            left join akun as b on b.id_akun = a.id_akun
            left join (
            SELECT c.id_akun, sum(c.debit) as debit, sum(c.kredit) as kredit
                FROM jurnal as c
                where c.tgl BETWEEN '$r->tgl1' and '$r->tgl2'
                group by c.id_akun
            ) as c on c.id_akun = a.id_akun
            WHERE a.id_sub_kategori = '$r->id_sub_kategori';"),
            'id_sub_kategori' => $r->id_sub_kategori,
            'akun' => DB::table('akun')->get()
        ];
        return view('neraca.inputAKun', $data);
    }

    public function saveAkunNeraca(Request $r)
    {
        $data = [
            'id_akun' => $r->id_akun,
            'id_sub_kategori' => $r->id_sub_kategori,
        ];
        DB::table('akun_neraca')->insert($data);
    }
}
