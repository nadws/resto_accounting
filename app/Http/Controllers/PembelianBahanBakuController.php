<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianBahanBakuController extends Controller
{
    public function index(Request $r)
    {
        $data =  [
            'title' => 'Pembelian Bahan Baku',

        ];
        return view('pembelian_bk.index', $data);
    }

    public function add(Request $r)
    {
        $max = DB::table('pembelian')->latest('urutan_nota')->first();

        if (empty($max)) {
            $nota_t = '1000';
        } else {
            $nota_t = $max->nomor_nota + 1;
        }
        $data =  [
            'title' => 'Tambah Bahan Baku',
            'suplier' => DB::table('tb_suplier')->get(),
            'nota' => $nota_t,
            'produk' => DB::table('tb_produk')->get()

        ];
        return view('pembelian_bk.add', $data);
    }

    public function get_satuan_produk(Request $r)
    {
        $id_produk = $r->id_produk;
        $produk = DB::table('tb_produk')->where('id_produk', $id_produk)->first();
        $satuan = DB::table('tb_satuan')->where('id_satuan', $produk->satuan_id)->get();

        foreach ($satuan as $k) {
            echo "<option value='" . $k->id_satuan  . "'>" . $k->nm_satuan . "</option>";
        }
    }

    public function tambah_baris_bk(Request $r)
    {
        $data =  [
            'produk' => DB::table('tb_produk')->get(),
            'count' => $r->count

        ];
        return view('pembelian_bk.tambah_baris', $data);
    }
}
