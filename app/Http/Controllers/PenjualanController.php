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
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'history' => DB::select("SELECT 
            a.invoice,
            sum(a.kredit) as kredit,
            b.nm_bahan,
            a.id_bahan,
            c.nm_menu,
        e.nm_satuan,
            a.tgl
            FROM `stok_bahan` as a
            JOIN tb_list_bahan as b on a.id_bahan = b.id_list_bahan
            JOIN tb_menu as c on a.id_menu = c.id_menu
        JOIN tb_satuan as e on b.id_satuan = e.id_satuan
            where a.invoice LIKE '%KLR%' AND a.tgl BETWEEN '$tgl1' AND '$tgl2' group by a.id_bahan,a.tgl;")
        ];
        return view('datamenu.penjualan.history', $data);
    }

    public function detail(Request $r)
    {
        $detail = DB::select("SELECT 
        a.kredit,
        sum.kredit as ttl,
        b.nm_bahan,
        a.id_bahan,
        c.id_menu,
        c.nm_menu,
        e.nm_satuan,
        d.qty,
        terjual.terjual,
        a.tgl
        FROM `stok_bahan` as a
        JOIN tb_list_bahan as b on a.id_bahan = b.id_list_bahan
        JOIN tb_menu as c on a.id_menu = c.id_menu
        join resep as d on c.id_menu = d.id_menu AND d.id_bahan = b.id_list_bahan
        JOIN tb_satuan as e on b.id_satuan = e.id_satuan
        JOIN (
            select sum(kredit) as kredit,id_bahan from stok_bahan where invoice like '%KLR%'  AND tgl = '$r->tgl' group by id_bahan,tgl
        ) sum on a.id_bahan = sum.id_bahan
        join (
            select id_menu,tgl,sum(qty) as terjual from penjualan_peritem GROUP BY id_menu,tgl
        ) terjual on terjual.id_menu = c.id_menu and terjual.tgl = a.tgl
        where a.invoice LIKE '%KLR%' AND a.tgl = '$r->tgl' AND a.id_bahan = '$r->id_bahan';");
        $data = [
            'history' => $detail
        ];
        return view('datamenu.penjualan.detail', $data);
    }
}
