<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PoController extends Controller
{
    protected $suplier,$bahan,$satuan;
    public function __construct()
    {
        $this->suplier = DB::table('tb_suplier')->get();
        $this->bahan = DB::table('tb_list_bahan')->get();
        $this->satuan = DB::table('tb_satuan')->get();
    }
    public function index(Request $r)
    {
        $data = [
            'title' => 'Pesanan Pembelian'
        ];
        return view('datamenu.po.index',$data);
    }

    public function add()
    {
        $data = [
            'title' => 'Tambah Pesanan Pembelian',
            'no_po' => 1001,
            'suplier' => $this->suplier,
            'bahan' => $this->bahan,
            'satuan' => $this->satuan,
            'akunPembayaran' => DB::table('akun')->where('id_klasifikasi',5)->get()
        ];
        return view('datamenu.po.add',$data);
    }

    public function tbh_baris(Request $r)
    {
        $data = [
            'count' => $r->count,
            'suplier' => $this->suplier,
            'bahan' => $this->bahan,
            'satuan' => $this->satuan,
        ];
        return view('datamenu.po.tbh_baris',$data);
    }

    public function print(Request $r)
    {
        $data = [
            'title' => 'Pesanan Pembelian'
        ];
        return view('datamenu.po.print',$data);
    }

}
