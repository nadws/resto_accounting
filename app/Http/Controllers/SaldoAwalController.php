<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaldoAwalController extends Controller
{
    function index(Request $r)
    {
        $data =  [
            'title' => 'Saldo Awal',
            'akun' => DB::select("SELECT a.* , b.debit, b.kredit, b.penutup
            FROM akun as a 
            left join (
            SELECT b.id_akun, b.penutup, sum(b.debit) as debit , sum(b.kredit) as kredit FROM jurnal as b 
            where b.saldo = 'Y'
            group by b.id_akun
            ) as b on b.id_akun = a.id_akun
            order by a.kode_akun ASC
            "),
            'tgl_saldo' => DB::SelectOne("SELECT a.tgl FROM jurnal as a where a.saldo = 'Y'")
        ];
        return view("pembukuan.saldo.index", $data);
    }

    public function saveSaldo(Request $r)
    {
        $id_akun = $r->id_akun;
        DB::table('jurnal')->where('saldo', 'Y')->delete();
        for ($i = 0; $i < count($id_akun); $i++) {
            $data = [
                'id_akun' => $r->id_akun[$i],
                'debit' => $r->debit[$i],
                'kredit' => $r->kredit[$i],
                'ket' => 'Saldo Awal',
                'id_buku' => '1',
                'tgl' => date('Y-12-31'),
                'admin' => Auth::user()->name,
                'saldo' => 'Y'
            ];
            DB::table('jurnal')->insert($data);
        }
        return redirect()->route('saldoawal.index')->with('sukses', 'Data berhasil ditambahkan');
    }
}
