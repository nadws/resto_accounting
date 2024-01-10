<?php

namespace App\Http\Controllers;

use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SinkronController extends Controller
{
    function index(Request $r)
    {
        $tglAwal = "2024-01-01";
        $tgl = date('Y-m-d', strtotime('- 1 days'));

        $cekStok = DB::table('stok_bahan')->where('invoice', 'LIKE', '%KLR%')->whereBetween('tgl', [$tglAwal, $tgl])->distinct()
            ->pluck('tgl')
            ->toArray();

        // Tentukan seluruh tanggal antara dua tanggal
        $tanggalLengkap = collect(CarbonPeriod::create($tglAwal, $tgl))
            ->map(function ($date) {
                return $date->format('Y-m-d');
            })
            ->toArray();

        // Hitung jumlah hari yang tidak ada di database
        $hariTidakAda = array_diff($tanggalLengkap, $cekStok);
        $tglTidakAda = array_fill_keys($hariTidakAda, null);
        $data = [
            'title' => 'Data sinkron',
            'menu' => DB::selectOne("SELECT count(a.tgl) as ttl
            FROM jurnal as a 
            where a.id_buku = '3' and a.tgl = '$tgl'"),
            'tgl' => $tgl,
            'cekStok' => $cekStok,
            'countBelumExport' => count($hariTidakAda),
            'tglTidakAda' => $tglTidakAda

        ];
        return view("sinkron.index", $data);
    }
}
