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
            'title' => 'Cashflow'
        ];
        return view('cashflow.cashflow', $data);
    }

    public function load(Request $r)
    {
        $data = [
            'title' => 'Cashflow',
            'subKategori1' => DB::table('sub_kategori_cashflow')->where('jenis', 1)->orderBy('urutan', 'ASC')->get(),
            'subKategori2' => DB::table('sub_kategori_cashflow')->where('jenis', 2)->orderBy('urutan', 'ASC')->get(),
            'tgl1' => $r->tgl1,
            'tgl2' => $r->tgl2,
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
        dd($r->all());
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
}
