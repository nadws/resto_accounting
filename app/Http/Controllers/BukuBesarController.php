<?php

namespace App\Http\Controllers;

use App\Models\Buku_besar;
use App\Models\Jurnal;
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
            'buku' => DB::select("SELECT a.no_nota,a.id_akun, b.kode_akun, b.nm_akun, sum(a.debit) as debit , sum(a.kredit) as kredit 
            FROM jurnal as a 
            left join akun as b on b.id_akun = a.id_akun
            WHERE a.tgl BETWEEN '2023-01-01' and '$tgl2' 
            group by a.id_akun
            ORDER by b.kode_akun ASC;"),

        ];
        return view('sum_buku.index', $data);
    }

    public function detail(Request $r)
    {
        $data = [
            'title' => 'Detail Buku Besar',
            'detail' => DB::select("SELECT d.ket as ket2, a.ket, a.tgl,a.id_akun, d.nm_akun, a.no_nota, a.debit, a.kredit, a.saldo FROM `jurnal` as a
                        LEFT JOIN (
                            SELECT j.no_nota, j.id_akun, GROUP_CONCAT(DISTINCT j.ket SEPARATOR ', ') as ket, GROUP_CONCAT(DISTINCT b.nm_akun SEPARATOR ', ') as nm_akun 
                            FROM jurnal as j
                            LEFT JOIN akun as b ON b.id_akun = j.id_akun
                            WHERE j.debit > 0
                            GROUP BY j.no_nota
                        ) d ON a.no_nota = d.no_nota AND d.id_akun != a.id_akun
                        WHERE a.id_akun = '$r->id_akun'
                        order by a.saldo DESC
            ")
        ];
        return view('sum_buku.detail', $data);
    }
}
