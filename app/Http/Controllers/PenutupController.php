<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenutupController extends Controller
{
    public function index(Request $r)
    {

        $tgl1 =  $r->tgl1 ?? '2023-01-01';
        $tgl2 =  $r->tgl2 ?? date('Y-m-t');

        $tgl = DB::selectOne("SELECT min(a.tgl) as tgl, a.penutup FROM jurnal as a WHERE a.penutup = 'T' and a.id_buku != '5'");
        $tgl1Tutup = date('Y-m-01', strtotime($tgl->tgl));
        $tgl2Tutup = date('Y-m-t', strtotime($tgl->tgl));

        $data = [
            'title' => 'Jurnal Penutup',
            'pendapatan' => DB::select("SELECT a.no_nota,a.id_akun, b.kode_akun, b.nm_akun, sum(a.debit) as debit , sum(a.kredit) as kredit 
            FROM jurnal as a 
            left join akun as b on b.id_akun = a.id_akun
            WHERE a.tgl BETWEEN '$tgl1Tutup' and '$tgl2Tutup' AND a.penutup = 'T' and b.iktisar='Y' and b.id_klasifikasi = '3'
            group by a.id_akun
            ORDER by b.kode_akun ASC;"),
            'biaya' => DB::select("SELECT a.no_nota,a.id_akun, b.kode_akun, b.nm_akun, sum(a.debit) as debit , sum(a.kredit) as kredit 
            FROM jurnal as a 
            left join akun as b on b.id_akun = a.id_akun
            WHERE a.tgl BETWEEN '$tgl1Tutup' and '$tgl2Tutup' AND a.penutup = 'T' and b.iktisar='Y' and b.id_klasifikasi = '2'
            group by a.id_akun
            ORDER by b.kode_akun ASC;"),
            'tgl' => $tgl,
            'penutup' => $tgl->penutup,
            'tgl1Tutup' => $tgl1Tutup,
            'tgl2Tutup' => $tgl2Tutup,
        ];
        return view('penutup.penutup2', $data);
    }

    public function saldo()
    {
        $tgl = DB::selectOne("SELECT min(a.tgl) as tgl FROM jurnal as a WHERE a.penutup = 'T' and a.id_buku not in(1,5)")->tgl;

        $tgl1 = date('Y-m-01', strtotime($tgl));
        $tgl2 = date('Y-m-t', strtotime($tgl));
        $nextMonth = Carbon::parse($tgl1)->addMonth()->toDateString();


        // Ikhtisar
        $pendapatan = DB::select("SELECT a.no_nota,a.id_akun, b.kode_akun, b.nm_akun, sum(a.debit) as debit , sum(a.kredit) as kredit 
        FROM jurnal as a 
        left join akun as b on b.id_akun = a.id_akun
        WHERE a.tgl BETWEEN '$tgl1' and '$tgl2'  and b.iktisar='Y' and b.id_klasifikasi = '3'
        group by a.id_akun
        ORDER by b.kode_akun ASC;");

        $biaya = DB::select("SELECT a.no_nota,a.id_akun, b.kode_akun, b.nm_akun, sum(a.debit) as debit , sum(a.kredit) as kredit 
        FROM jurnal as a 
        left join akun as b on b.id_akun = a.id_akun
        WHERE a.tgl BETWEEN '$tgl1' and '$tgl2'  and b.iktisar='Y' and b.id_klasifikasi = '2'
        group by a.id_akun
        ORDER by b.kode_akun ASC;");

        foreach ($pendapatan as $p) {
            $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '5')->first();

            $no_nota = empty($max) ? '1000' : $max->nomor_nota + 1;
            DB::table('notas')->insert(['nomor_nota' => $no_nota, 'id_buku' => '5']);
            $data = [
                'tgl' => $tgl2,
                'no_nota' => "PEN-$no_nota",
                'id_akun' => $p->id_akun,
                'id_buku' => '5',
                'ket' => 'Penutup Ikhtisar',
                'debit' => $p->kredit,
                'kredit' => 0,
                'admin' => Auth::user()->name,
            ];
            Jurnal::create($data);
            $data = [
                'tgl' => $tgl2,
                'no_nota' => "PEN-$no_nota",
                'id_akun' => '515',
                'id_buku' => '5',
                'ket' => 'Penutup Ikhtisar',
                'debit' => 0,
                'kredit' => $p->kredit,
                'admin' => Auth::user()->name,
            ];
            Jurnal::create($data);
        }

        foreach ($biaya as $b) {
            $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '5')->first();

            $no_nota = empty($max) ? '1000' : $max->nomor_nota + 1;
            DB::table('notas')->insert(['nomor_nota' => $no_nota, 'id_buku' => '5']);
            $data = [
                'tgl' => $tgl2,
                'no_nota' => "PEN-$no_nota",
                'id_akun' => $b->id_akun,
                'id_buku' => '5',
                'ket' => 'Penutup Ikhtisar',
                'debit' => 0,
                'kredit' => $p->debit,
                'admin' => Auth::user()->name,
            ];
            Jurnal::create($data);
            $data = [
                'tgl' => $tgl2,
                'no_nota' => "PEN-$no_nota",
                'id_akun' => '515',
                'id_buku' => '5',
                'ket' => 'Penutup Ikhtisar',
                'debit' => $p->debit,
                'kredit' => 0,
                'admin' => Auth::user()->name,
            ];
            Jurnal::create($data);
        }







        $saldo = DB::select("SELECT b.iktisar , a.no_nota,a.id_akun, b.kode_akun, b.nm_akun, sum(a.debit) as debit , sum(a.kredit) as kredit 
            FROM jurnal as a 
            left join akun as b on b.id_akun = a.id_akun
            WHERE a.tgl BETWEEN '$tgl1' and '$tgl2' AND a.penutup = 'T' 
            group by a.id_akun
            ORDER by b.kode_akun ASC;");


        foreach ($saldo as $d) {
            $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '5')->first();

            $no_nota = empty($max) ? '1000' : $max->nomor_nota + 1;
            DB::table('notas')->insert(['nomor_nota' => $no_nota, 'id_buku' => '5']);

            if ($d->iktisar == 'T') {
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
                    'penutup' => 'T',
                    'saldo' => 'Y'
                ];
            } else {
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
                    'penutup' => 'T',
                    'saldo' => 'T'
                ];
            }
            Jurnal::create($data);
            Jurnal::whereBetween('tgl', [$tgl1, $tgl2])->update(['penutup' => 'Y', 'saldo' => 'T']);
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
