<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PenjualanController extends Controller
{
    public function __construct()
    {
        
    }
    public function index(Request $r)
    {
        $id_lokasi = app('id_lokasi');
        $tglKemarin = now()->subDay()->format('Y-m-d');
        $tgl1 = $r->tgl1 ?? $tglKemarin;
        $tgl2 = $r->tgl2 ?? $tglKemarin;
        $datas = DB::table('penjualan_peritem as a')
            ->join('tb_menu as b', 'a.id_menu', 'b.id_menu')
            ->join('tb_station as c', 'c.id_station', 'b.id_station')
            ->whereBetween('a.tgl', [$tgl1, $tgl2])
            ->orderBy('b.nm_menu', 'ASC')
            ->get();
        $data  = [
            'title' => 'Data Penjualan',
            'datas' => $datas
        ];
        return view('datamenu.penjualan.index', $data);
    }

    public function history(Request $r)
    {
        $tglKemarin = now()->subDay()->format('Y-m-d');
        $tgl1 = $r->tgl1 ?? $tglKemarin;
        $tgl2 = $r->tgl2 ?? $tglKemarin;
        $data = [
            'title' => 'History bahan keluar',
            'history' => DB::select("SELECT 
            a.invoice,
            a.kredit,
            b.nm_bahan,
            c.nm_menu,
            a.tgl
            FROM `stok_bahan` as a
            JOIN tb_list_bahan as b on a.id_bahan = b.id_list_bahan
            JOIN tb_menu as c on a.id_menu = c.id_menu
            where a.invoice LIKE '%KLR%' AND a.tgl BETWEEN '$tgl1' AND '$tgl2';")
        ];
        return view('datamenu.penjualan.history', $data);
    }
}
