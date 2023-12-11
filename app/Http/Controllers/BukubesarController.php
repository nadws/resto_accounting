<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BukubesarController extends Controller
{
    public function index(Request $r)
    {
        $tgl = tanggalFilter($r);
        $tgl1 = $tgl['tgl1'];
        $tgl2 = $tgl['tgl2'];


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
            'title' => 'Summary Buku Besar',
            'buku' => $buku,
            'penutup' => $ditutup,
            'tgl1' => $tgl1,
            'tgl2' => $tgl2

        ];
        return view('pembukuan.buku_besar.index', $data);
    }
}
