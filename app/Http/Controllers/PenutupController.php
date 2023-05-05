<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenutupController extends Controller
{
    public function index(Request $r)
    {
        $tgl1 = $r->tgl1 ?? date('Y-m-d');
        $tgl2 = $r->tgl2 ?? date('Y-m-t');

        $data = [
            'title' => 'Saldo Penutup',
            'buku' => DB::select("SELECT a.no_nota,a.id_akun, b.kode_akun, b.nm_akun, sum(a.debit) as debit , sum(a.kredit) as kredit 
            FROM jurnal as a 
            left join akun as b on b.id_akun = a.id_akun
            WHERE a.tgl BETWEEN '$tgl1' and '$tgl2' 
            group by a.id_akun
            ORDER by b.kode_akun ASC;"),
            'tgl1' => $tgl1,
            'tgl2' => $tgl2
        ];
        return view('penutup.penutup',$data);
    }

    public function saldo()
    {
        $tgl = DB::selectOne("SELECT min(a.tgl) as tgl FROM jurnal as a WHERE a.penutup = 'T'")->tgl;

        $tgl1 = date('Y-m-01', strtotime($tgl));
        $tgl2 = date('Y-m-t', strtotime($tgl));

        $saldo = DB::select("SELECT a.no_nota,a.id_akun, b.kode_akun, b.nm_akun, sum(a.debit) as debit , sum(a.kredit) as kredit 
            FROM jurnal as a 
            left join akun as b on b.id_akun = a.id_akun
            WHERE a.tgl BETWEEN '$tgl1' and '$tgl2' 
            group by a.id_akun
            ORDER by b.kode_akun ASC;");

        foreach($saldo as $d) {
            echo $d->nm_akun . " = " . number_format($d->debit - $d->kredit,0);
            echo "<br>";
        }
        // $data = [
        //     'id_akun' => $r->id_akun[$i],
        //     'debit' => $r->debit[$i],
        //     'kredit' => $r->kredit[$i],
        //     'ket' => 'Saldo Awal',
        //     'id_buku' => '1',
        //     'tgl' => date('Y-01-01'),
        //     'admin' => Auth::user()->name,
        //     'saldo' => 'Y'
        // ];

        // Jurnal::create($data);
    }
}
