<?php

namespace App\Http\Controllers;

use App\Models\Buku_besar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BukuBesarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $r)
    {
        if (empty($r->tgl1)) {
            $tgl1 =  date('Y-m-01');
            $tgl2 =  date('Y-m-t');
        } else {
            $tgl1 =  $r->tgl1;
            $tgl2 =  $r->tgl2;
        }
        $data =  [
            'title' => 'Summary Buku Besar',
            'buku' => DB::select("SELECT a.id_akun, b.kode_akun, b.nm_akun, sum(a.debit) as debit , sum(a.kredit) as kredit 
            FROM jurnal as a 
            left join akun as b on b.id_akun = a.id_akun
            WHERE a.tgl BETWEEN '$tgl1' and '$tgl2' 
            group by a.id_akun
            ORDER by b.kode_akun ASC;"),

        ];
        return view('sum_buku.index', $data);
    }
}
