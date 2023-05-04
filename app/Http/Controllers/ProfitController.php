<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfitController extends Controller
{
    protected $tgl1, $tgl2;
    public function __construct(Request $r)
    {
        $this->tgl1 = $r->tgl1 ?? date('Y-m-01');
        $this->tgl2 = $r->tgl2 ?? date('Y-m-t');
    }
    public function index()
    {
        $tgl1 =  $this->tgl1;
        $tgl2 =  $this->tgl2;

        $profit = DB::select("SELECT b.nm_akun, sum(a.debit) as debit , sum(a.kredit) as kredit
        FROM jurnal as a 
        left join akun as b on  b.id_akun = a.id_akun
        WHERE a.tgl BETWEEN '$tgl1' and '$tgl2' and b.id_klasifikasi ='3'
        group by a.id_akun;");

        $loss = DB::select("SELECT b.nm_akun, sum(a.debit) as debit , sum(a.kredit) as kredit
        FROM jurnal as a 
        left join akun as b on  b.id_akun = a.id_akun
        WHERE a.tgl BETWEEN '$tgl1' and '$tgl2' and b.id_klasifikasi ='2'
        group by a.id_akun;");

        $data =  [
            'title' => 'Profit and Loss',
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'profit' => $profit,
            'loss' => $loss

        ];
        return view('profit.index', $data);
    }

    public function print(Request $r)
    {
        $tgl1 =  $this->tgl1;
        $tgl2 =  $this->tgl2;

        $profit = DB::select("SELECT b.nm_akun, sum(a.debit) as debit , sum(a.kredit) as kredit
        FROM jurnal as a 
        left join akun as b on  b.id_akun = a.id_akun
        WHERE a.tgl BETWEEN '$tgl1' and '$tgl2' and b.id_klasifikasi ='3'
        group by a.id_akun;");

        $loss = DB::select("SELECT b.nm_akun, sum(a.debit) as debit , sum(a.kredit) as kredit
        FROM jurnal as a 
        left join akun as b on  b.id_akun = a.id_akun
        WHERE a.tgl BETWEEN '$tgl1' and '$tgl2' and b.id_klasifikasi ='2'
        group by a.id_akun;");

        $data =  [
            'title' => 'Profit and Loss',
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'profit' => $profit,
            'loss' => $loss

        ];
        return view('profit.print', $data);
    }
}
