<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardKandangController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Dashboard Kandang',
            'kandang' => DB::table('kandang')->get(),
            'telur' => DB::table('telur_produk')->get()
        ];
        return view('dashboard_kandang.index',$data);
    }

    public function tambah_telur(Request $r)
    {
        DB::table('stok_telur')->where([['id_kandang', $r->id_kandang], ['tgl', $r->tgl]])->delete();
        for ($i=0; $i < count($r->id_telur); $i++) { 
            DB::table('stok_telur')->insert([
                'id_kandang' => $r->id_kandang,
                'id_telur' => $r->id_telur[$i],
                'tgl' => $r->tgl,
                'pcs' => $r->pcs[$i],
                'kg' => $r->kg[$i],
                'pcs_kredit' => 0,
                'kg_kredit' => 0,
                'admin' => auth()->user()->name,
                'id_gudang' => 1,
                'nota_transfer' => '',
                'ket' => '',
            ]);
        }

        return redirect()->route('dashboard_kandang.index')->with('sukses', 'Data Berhasil Ditambahkan');
    }
}
