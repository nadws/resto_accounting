<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use App\Models\Stok;
use Illuminate\Http\Request;

class OpnameController extends Controller
{
    public function index($gudang_id = null)
    {
        $produk = Stok::getProduk($gudang_id, 'Y');

        $data = [
            'title' => 'Opname',
            'gudang' => Gudang::all(),
            'produk' => $produk,
        ];
        return view('persediaan_barang.opname.opname',$data);
    }

    public function save(Request $r)
    {
        for ($i=0; $i < count($r->id_produk); $i++) { 
            $total = $r->buku[$i] - $r->fisik[$i];
            
            $debit = $total < 0 ? $total * -1 : 0;
            $kredit = $total < 0 ? 0 : $total;

            $data = [
                'id_produk' => $r->id_produk[$i],
                'tgl' => date('Y-m-d'),
                'urutan' => '0',
                'no_nota' => 'Opname',
                'departemen_id' => '1',
                'status' => 'opname',
                'jenis' => 'selesai',
                'gudang_id' => $r->gudang_id[$i],
                'jml_sebelumnya' => $r->buku[$i],
                'jml_sesudahnya' => $r->fisik[$i],
                'selisih' => $r->selisih[$i],
                'debit' => $debit,
                'kredit' => $kredit,
                'ket' => 'Opname ',
                'rp_satuan' => '0',
                'admin' => auth()->user()->name,
            ];

            Stok::create($data);
        }

        return redirect()->route('opname.index')->with('sukses', 'Berhasil opname');
    }
}
