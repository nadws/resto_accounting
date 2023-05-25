<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenutupController extends Controller
{
    public function index(Request $r)
    {

        $tgl1 =  $r->tgl1 ?? '2023-01-01';
        $tgl2 =  $r->tgl2 ?? date('Y-m-t');

        $tgl = DB::selectOne("SELECT min(a.tgl) as tgl, a.penutup FROM jurnal as a WHERE a.penutup = 'T'");
        $tgl1Tutup = date('Y-m-01', strtotime($tgl->tgl));
        $tgl2Tutup = date('Y-m-t', strtotime($tgl->tgl));

        $data = [
            'title' => 'Saldo Penutup',
            'buku' => DB::select("SELECT a.no_nota,a.id_akun, b.kode_akun, b.nm_akun, sum(a.debit) as debit , sum(a.kredit) as kredit 
            FROM jurnal as a 
            left join akun as b on b.id_akun = a.id_akun
            WHERE a.tgl BETWEEN '$tgl1' and '$tgl2' AND a.penutup = 'T'
            group by a.id_akun
            ORDER by b.kode_akun ASC;"),
            'tgl' => $tgl,
            'penutup' => $tgl->penutup,
            'tgl1Tutup' => $tgl1Tutup,
            'tgl2Tutup' => $tgl2Tutup,
        ];
        return view('penutup.penutup', $data);
    }

    public function saldo()
    {
        $tgl = DB::selectOne("SELECT min(a.tgl) as tgl FROM jurnal as a WHERE a.penutup = 'T'")->tgl;

        $tgl1 = date('Y-m-01', strtotime($tgl));
        $tgl2 = date('Y-m-t', strtotime($tgl));
        $nextMonth = Carbon::parse($tgl1)->addMonth()->toDateString();

        $saldo = DB::select("SELECT a.no_nota,a.id_akun, b.kode_akun, b.nm_akun, sum(a.debit) as debit , sum(a.kredit) as kredit 
            FROM jurnal as a 
            left join akun as b on b.id_akun = a.id_akun
            WHERE a.tgl BETWEEN '$tgl1' and '$tgl2' AND a.penutup = 'T'
            group by a.id_akun
            ORDER by b.kode_akun ASC;");

        foreach ($saldo as $d) {
            $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '5')->first();

            $no_nota = empty($max) ? '1000' : $max->nomor_nota + 1;
            DB::table('notas')->insert(['nomor_nota' => $no_nota, 'id_buku' => '5']);

            $data = [
                'id_akun' => $d->id_akun,
                'debit' => $d->debit,
                'kredit' => $d->kredit,
                'ket' => 'Saldo Penutup',
                'id_buku' => '5',
                'no_nota' => "PEN-$no_nota",
                'tgl' => $nextMonth,
                'tgl_dokumen' => $tgl2,
                'admin' => auth()->user()->name,
                'penutup' => 'T'
            ];
            Jurnal::create($data);

            Jurnal::whereBetween('tgl', [$tgl1, $tgl2])->update(['penutup' => 'Y', 'saldo' => 'Y']);
        }

        return redirect()->route('penutup.index')->with('sukses', 'Berhasil Tutup Saldo');
    }

    public function history()
    {
        $saldo = DB::select("SELECT a.tgl,a.tgl_dokumen,a.no_nota,a.id_akun, b.kode_akun, b.nm_akun, sum(a.debit) as debit , sum(a.kredit) as kredit 
            FROM jurnal as a 
            left join akun as b on b.id_akun = a.id_akun
            WHERE a.ket = 'Saldo Penutup'
            group by a.no_nota
            ORDER by b.kode_akun ASC;");

        $data = [
            'title' => 'History',
            'history' => $saldo
        ];
        return view('penutup.history', $data);
    }
}
