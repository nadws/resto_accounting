<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Satuan;
use Illuminate\Http\Request;

class PoController extends Controller
{
    public function index(Request $r)
    {
        $data = [
            'title' => 'Pengajuan Barang',
            'tgl1' => $r->tgl1 ?? date('Y-m-01'),
            'tgl2' => $r->tgl2 ?? date('Y-m-t'),
        ];
        return view('persediaan_barang.po.po', $data);
    }

    public function add()
    {
        $data = [
            'title' => 'Pengajuan Barang',
        ];
        return view('persediaan_barang.po.add', $data);
    }

    public function load_view_add()
    {
        $data = [
            'title' => 'Pengajuan Barang',
            'satuan' => Satuan::all(),
            'produk' => Produk::all(),
        ];
        return view('persediaan_barang.po.load_view_add', $data);
    }

    public function tbh_baris(Request $r)
    {
        $data = [
            'title' => 'Tambah Barang',
            'satuan' => Satuan::all(),
            'produk' => Produk::all(),
            'count' => $r->count
        ];
        return view('persediaan_barang.po.tbh_baris', $data);
    }
}
