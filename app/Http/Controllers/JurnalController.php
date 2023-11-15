<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JurnalController extends Controller
{
    public function index(Request $r)
    {
        $tgl = tanggalFilter($r);
        $tgl1 = $tgl['tgl1'];
        $tgl2 = $tgl['tgl2'];
        $id_buku = $r->id_buku ?? 2;
        $data = [
            'title' => 'Jurnal',
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'id_buku' => $id_buku,
        ];
        return view('pembukuan.jurnal.index',$data);
    }

    public function add(Request $r)
    {
        $kategori = [
            2 => 'biaya',
            12 => 'pengeluaran aktiva gantung',
            13 => 'pembalikan aktiva gantung',
            6 => 'penjualan',
            7 => 'kas & bank',
            10 => 'pemeblian asset',
            14 => 'Hutang',
        ];
        $nota_t = 1000;
        // $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '2')->first();

        $data = [
            'title' => "Tambah Jurnal " . ucwords($kategori[$r->id_buku]),
            'max' => $nota_t,
            'suplier' => DB::table('tb_suplier')->get(),
            'id_buku' => $r->id_buku,
        ];
        return view('pembukuan.jurnal.add',$data);
    }

    public function load_add_menu(Request $r)
    {
        $data = [
            'akun' => DB::table('akun')->get()
        ];
        return view('pembukuan.jurnal.load_menu',$data);
    }

    public function create(Request $r)
    {
        dd($r->all());
    }
}
