<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashflowController extends Controller
{
    protected $id_departemen = 1;
    public function index()
    {
        $data = [
            'title' => 'Cashflow',


        ];
        return view('cashflow.cashflow', $data);
    }

    public function load(Request $r)
    {
        $pemasukan = DB::select("SELECT a.id,  a.sub_kategori, b.debit , b.kredit
        FROM sub_kategori_cashflow as a 
        left join (
        SELECT b.id_akun_cashflow, b.id_sub_klasifikasi_akun, sum(c.debit) as debit , sum(c.kredit) as kredit
            FROM akuncashflow as b 
            left join (
            SELECT c.id_akun , sum(c.debit) as debit , sum(c.kredit) as kredit
                FROM jurnal as c
                group by c.id_akun
            ) as c on c.id_akun = b.id_akun
            group by b.id_sub_klasifikasi_akun
        ) as b on b.id_sub_klasifikasi_akun = a.id
        where a.jenis = '1'
        order by a.urutan ASC
        ");
        $data = [
            'title' => 'Cashflow',
            'subKategori1' => DB::table('sub_kategori_cashflow')->where('jenis', 1)->orderBy('urutan', 'ASC')->get(),
            'subKategori2' => DB::table('sub_kategori_cashflow')->where('jenis', 2)->orderBy('urutan', 'ASC')->get(),
            'tgl1' => $r->tgl1,
            'tgl2' => $r->tgl2,
            'pemasukan' => $pemasukan,
            'kategori' => DB::table("kategori_cashflow")->get()
        ];
        return view('cashflow.load', $data);
    }

    public function loadSubKategori(Request $r)
    {
        $data = [
            'subKategori' => DB::table('sub_kategori_cashflow')->where('jenis', $r->jenis)->orderBy('urutan', 'ASC')->get()
        ];
        return view('cashflow.load_sub_kategori', $data);
    }

    public function saveSubKategori(Request $r)
    {
        // dd($r->all());
        DB::table('sub_kategori_cashflow')->insert([
            'id_departemen' => $this->id_departemen,
            'urutan' => $r->urutan,
            'sub_kategori' => $r->sub_kategori,
            'jenis' => $r->jenis
        ]);
    }

    public function editSubKategori(Request $r)
    {

        for ($i = 0; $i < count($r->urutan_edit); $i++) {
            $data = [
                'urutan' => $r->urutan_edit[$i],
                'sub_kategori' => $r->sub_kategori_edit[$i],
                'jenis' => $r->jenis_edit[$i],

            ];
            DB::table('sub_kategori_cashflow')->where('id', $r->id_edit[$i])->update($data);
        }
    }

    public function tmbahAkunCashflow(Request $r)
    {
        $id_sub = $r->id_sub;
        $data = [
            'id_sub' => $id_sub,
            'akun' => DB::Select("SELECT * FROM akun as a where a.id_akun not in (SELECT b.id_akun FROM akuncashflow as b ) "),
            'akun_cashflow' => DB::select("SELECT a.id_akun, b.nm_akun FROM akuncashflow as a left join akun as b on b.id_akun = a.id_akun where a.id_sub_klasifikasi_akun = '$id_sub'")
        ];

        return view('cashflow.tmbahAkunCashflow', $data);
    }

    public function savetbhAkun(Request $r)
    {
        $data = [
            'id_sub_klasifikasi_akun' => $r->id_sub_klasifikasi_akun,
            'id_akun' => $r->id_akun
        ];
        DB::table('akuncashflow')->insert($data);
    }

    public function hapus_akunCashflow(Request $r)
    {
        $id_akun = $r->id_akun;

        DB::table('akuncashflow')->where('id_akun', $id_akun)->delete();
    }
}
