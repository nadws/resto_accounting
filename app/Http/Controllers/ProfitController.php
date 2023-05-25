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


        $data =  [
            'title' => 'Profit and Loss',
            'tgl1' => $this->tgl1,
            'tgl2' => $this->tgl2,

        ];
        return view('profit.index', $data);
    }

    public function load(Request $r)
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

        $akun = DB::table('akun')->get();

        $data = [
            'title' => 'Load Profit',
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'profit' => $profit,
            'loss' => $loss,
            'akun' => $akun
        ];
        return view('profit.load', $data);
    }

    public function modal()
    {
        $akunProfit = DB::table('profit_akun')->get();
        $akun = DB::table('akun')->get();
        $data = [
            'akunProfit' => $akunProfit,
            'akun' => $akun
        ];
        return view('profit.modal',$data);
    }

    public function add(Request $r)
    {
        DB::table('profit_akun')->insert([
            'urutan' => $r->urutan,
            'id_akun' => $r->id_akun,
        ]);
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
