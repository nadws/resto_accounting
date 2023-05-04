<?php

namespace App\Http\Controllers;

use App\Models\Buku_besar;
use App\Models\Jurnal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Buku_besarExport;

class BukuBesarController extends Controller
{
    protected $tgl1, $tgl2, $id_akun;
    public function __construct(Request $r)
    {
        $this->tgl1 = $r->tgl1 ?? date('Y-m-01');
        $this->tgl2 = $r->tgl2 ?? date('Y-m-t');
        $this->id_akun = $r->id_akun;
    }
    public function index(Request $r)
    {
        if (empty($r->tgl1)) {
            $tgl1 =  '2023-01-01';
            $tgl2 =  date('Y-m-t');
        } else {
            $tgl1 =  $r->tgl1;
            $tgl2 =  $r->tgl2;
        }

        $data =  [
            'title' => 'Summary Buku Besar',
            'buku' => DB::select("SELECT a.no_nota,a.id_akun, b.kode_akun, b.nm_akun, sum(a.debit) as debit , sum(a.kredit) as kredit 
            FROM jurnal as a 
            left join akun as b on b.id_akun = a.id_akun
            WHERE a.tgl BETWEEN '$tgl1' and '$tgl2' 
            group by a.id_akun
            ORDER by b.kode_akun ASC;"),
            'tgl1' => $tgl1,
            'tgl2' => $tgl2

        ];
        return view('sum_buku.index', $data);
    }

    public function detail(Request $r)
    {
        $tgl1 =  $r->tgl1;
        $tgl2 =  $r->tgl2;
        $data = [
            'title' => 'Detail Buku Besar',
            'detail' => DB::select("SELECT d.ket as ket2, a.ket, a.tgl,a.id_akun, d.nm_akun, a.no_nota, a.debit, a.kredit, a.saldo FROM `jurnal` as a
                        LEFT JOIN (
                            SELECT j.no_nota, j.id_akun, GROUP_CONCAT(DISTINCT j.ket SEPARATOR ', ') as ket, GROUP_CONCAT(DISTINCT b.nm_akun SEPARATOR ', ') as nm_akun 
                            FROM jurnal as j
                            LEFT JOIN akun as b ON b.id_akun = j.id_akun
                            WHERE j.id_akun != '$r->id_akun'
                            GROUP BY j.no_nota
                        ) d ON a.no_nota = d.no_nota AND d.id_akun != a.id_akun
                        WHERE a.id_akun = '$r->id_akun' and a.tgl between '$tgl1' and '$tgl2'
                        order by a.saldo DESC, a.id_jurnal ASC
            "),
            'id_akun' => $r->id_akun,
            'tgl1' => $tgl1,
            'tgl2' => $tgl2
        ];
        return view('sum_buku.detail', $data);
    }

    public function export_detail(Request $r)
    {
        $tgl1 =  $this->tgl1;
        $tgl2 =  $this->tgl2;
        $id_akun =  $this->id_akun;

        $total = DB::selectOne("SELECT count(a.id_jurnal) as jumlah FROM jurnal as a where a.id_akun = '$id_akun' and a.tgl between '$tgl1' and '$tgl2'");

        $totalrow = $total->jumlah;


        return Excel::download(new Buku_besarExport($tgl1, $tgl2, $id_akun, $totalrow), 'detail_buku_besar.xlsx');
    }
}
