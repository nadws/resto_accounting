<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenutupController extends Controller
{
    function index(Request $r)
    {
        $tgl1 =  $r->tgl1 ?? '2023-01-01';
        $tgl2 =  $r->tgl2 ?? date('Y-m-t');


        $tgl = DB::selectOne("SELECT min(a.tgl) as tgl, a.penutup FROM jurnal as a WHERE a.penutup = 'T' and a.id_buku not in ('8','1')");
        $tgl1Tutup = date('Y-m-01', strtotime($tgl->tgl));
        $tgl2Tutup = date('Y-m-t', strtotime($tgl->tgl));

        $jpa = DB::selectOne("SELECT a.kode_penyesuaian FROM jurnal as a where a.kode_penyesuaian = 'JPA' and a.tgl BETWEEN '$tgl1Tutup' and '$tgl2Tutup'");
        $data = [
            'title' => 'Jurnal Penutup',
            'jpa' => $jpa,
            'pendapatan' => DB::select("SELECT a.id_akun, a.nm_akun, b.debit, b.kredit
            FROM akun as a 
            left join (
            SELECT b.id_akun , sum(b.debit) as debit , sum(b.kredit) as kredit
                FROM jurnal as b 
                where b.tgl BETWEEN '$tgl1Tutup' and '$tgl2Tutup'
                GROUP by b.id_akun
            ) as b on b.id_akun = a.id_akun
            where a.iktisar ='Y' AND  a.id_klasifikasi ='1';"),

            'biaya' => DB::select("SELECT a.id_akun, a.nm_akun, b.debit, b.kredit
            FROM akun as a 
            left join (
            SELECT b.id_akun , sum(b.debit) as debit , sum(b.kredit) as kredit
                FROM jurnal as b 
                where b.tgl BETWEEN '$tgl1Tutup' and '$tgl2Tutup' and b.id_buku not in('8')
                GROUP by b.id_akun
            ) as b on b.id_akun = a.id_akun
            where a.iktisar ='Y' AND  a.id_klasifikasi in (2,3,13);"),
            'tgl' => $tgl,
            'penutup' => $tgl->penutup,
            'tgl1Tutup' => $tgl1Tutup,
            'tgl2Tutup' => $tgl2Tutup,
            'total' => DB::selectOne("SELECT count(a.id_akun) as total FROM akun as a where  a.iktisar='T'"),
            'aktiva' => DB::selectOne("SELECT a.id_akun FROM jurnal as a where a.id_akun = 26 and a.tgl between '$tgl1Tutup' and '$tgl2Tutup' and a.id_buku = '7' "),
            'peralatan' => DB::selectOne("SELECT a.id_akun FROM jurnal as a where a.id_akun = 28 and a.tgl between '$tgl1Tutup' and '$tgl2Tutup' and a.id_buku = '7' "),
            'atk' => DB::selectOne("SELECT a.id_akun FROM jurnal as a where a.id_akun = 30 and a.tgl between '$tgl1Tutup' and '$tgl2Tutup' and a.id_buku = '7' "),
            'cancel' => DB::select("SELECT a.tgl FROM jurnal as a where a.id_buku = '8' group by a.tgl ")
        ];
        return view('pembukuan.penutup.index', $data);
    }


    public function akun(Request $r)
    {
        $data = [
            'akun' => DB::select("SELECT * FROM akun as a ")
        ];
        return view('pembukuan.penutup.akun', $data);
    }

    public function edit_akun(Request $r)
    {
        for ($x = 0; $x < count($r->id_akun); $x++) {

            $data = [
                'iktisar' => $r->iktisar[$x]
            ];


            DB::table('akun')->where('id_akun', $r->id_akun[$x])->update($data);
        }
        return redirect()->route('penutup.index')->with('sukses', 'Berhasil input akun');
    }


    public function saldo(Request $r)
    {

        $tgl = $r->tgl;

        $tgl1 = date('Y-m-01', strtotime($tgl));
        $tgl2 = date('Y-m-t', strtotime($tgl));


        $nextMonth = Carbon::parse($tgl1)->addMonth()->toDateString();
        $prive_biasa =  $r->prive_biasa;

        $id_akun_pembelian  =  $r->id_akun_pembelian;
        $debit_pembelian  =  $r->debit_pembelian;
        $kredit_pembelian  =  $r->kredit_pembelian;

        $id_akun_biaya  =  $r->id_akun_biaya;
        $debit_biaya  =  $r->debit_biaya;
        $kredit_biaya  =  $r->kredit_biaya;

        $id_akun_modal  =  $r->id_akun_modal;
        $debit_modal  =  $r->debit_modal;
        $kredit_modal  =  $r->kredit_modal;



        for ($x = 0; $x < count($id_akun_pembelian); $x++) {
            $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '8')->first();
            $no_nota = empty($max) ? '1000' : $max->nomor_nota + 1;
            DB::table('notas')->insert(['nomor_nota' => $no_nota, 'id_buku' => '8']);

            if ($kredit_pembelian[$x] + $debit_pembelian[$x] == 0) {
                # code...
            } else {
                $data = [
                    'tgl' => $tgl,
                    'no_nota' => "PEN-$no_nota",
                    'id_akun' => $id_akun_pembelian[$x],
                    'id_buku' => '8',
                    'ket' => 'Penutup Ikhtisar',
                    'debit' => $debit_pembelian[$x],
                    'kredit' => $kredit_pembelian[$x],
                    'admin' => Auth::user()->name,
                ];
                DB::table('jurnal')->insert($data);
            }
        }
        for ($x = 0; $x < count($id_akun_biaya); $x++) {
            $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '8')->first();
            $no_nota = empty($max) ? '1000' : $max->nomor_nota + 1;
            DB::table('notas')->insert(['nomor_nota' => $no_nota, 'id_buku' => '8']);

            if ($debit_biaya[$x] + $kredit_biaya[$x] == 0) {
                # code...
            } else {
                $data = [
                    'tgl' => $tgl,
                    'no_nota' => "PEN-$no_nota",
                    'id_akun' => $id_akun_biaya[$x],
                    'id_buku' => '8',
                    'ket' => 'Penutup Ikhtisar',
                    'debit' => $debit_biaya[$x],
                    'kredit' => $kredit_biaya[$x],
                    'admin' => Auth::user()->name,
                ];
                DB::table('jurnal')->insert($data);
            }
        }
        for ($x = 0; $x < count($id_akun_modal); $x++) {
            $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '8')->first();
            $no_nota = empty($max) ? '1000' : $max->nomor_nota + 1;
            DB::table('notas')->insert(['nomor_nota' => $no_nota, 'id_buku' => '8']);

            if ($debit_modal[$x] + $kredit_modal[$x] == 0) {
                # code...
            } else {
                $data = [
                    'tgl' => $tgl,
                    'no_nota' => "PEN-$no_nota",
                    'id_akun' => $id_akun_modal[$x],
                    'id_buku' => '8',
                    'ket' => 'Penutup Ikhtisar',
                    'debit' => $debit_modal[$x],
                    'kredit' => $kredit_modal[$x],
                    'admin' => Auth::user()->name,
                ];
                DB::table('jurnal')->insert($data);
            }
        }


        $max_prive = DB::table('notas')->latest('nomor_nota')->where('id_buku', '8')->first();
        $no_nota_prive = empty($max_prive) ? '1000' : $max_prive->nomor_nota + 1;
        DB::table('notas')->insert(['nomor_nota' => $no_nota_prive, 'id_buku' => '8']);

        if ($prive_biasa == 0) {
            # code...
        } else {
            $data = [
                'tgl' => $tgl2,
                'no_nota' => "PEN-$no_nota_prive",
                'id_akun' => 37,
                'id_buku' => '8',
                'ket' => 'Penutup Ikhtisar',
                'debit' => $prive_biasa,
                'kredit' => 0,
                'admin' => Auth::user()->name,
            ];
            DB::table('jurnal')->insert($data);
            $data = [
                'tgl' => $tgl2,
                'no_nota' => "PEN-$no_nota_prive",
                'id_akun' => 38,
                'id_buku' => '8',
                'ket' => 'Penutup Ikhtisar',
                'debit' => 0,
                'kredit' => $prive_biasa,
                'admin' => Auth::user()->name,
            ];
            DB::table('jurnal')->insert($data);
        }



        $saldo_penutup = DB::select("SELECT a.id_akun, b.debit, b.kredit
        FROM akun as a
        left join(
            SELECT b.id_akun, sum(b.debit) as debit, sum(b.kredit) as kredit
            FROM jurnal as b
            where b.tgl BETWEEN '$tgl1' and '$tgl2' 
            group by b.id_akun
        ) as b on b.id_akun  = a.id_akun;");

        foreach ($saldo_penutup as $s) {
            $max = DB::table('notas')->where('id_buku', '8')->max('nomor_nota');
            $no_nota = empty($max) ? '1000' : $max + 1;
            DB::table('notas')->insert(['nomor_nota' => $no_nota, 'id_buku' => '8']);
            $data = [
                'id_akun' => $s->id_akun,
                'debit' => empty($s->debit) ? 0 : $s->debit,
                'kredit' => empty($s->kredit) ? 0 : $s->kredit,
                'no_nota' => "PEN-$no_nota",
                'tgl' => $tgl2,
                'admin' => auth()->user()->name,
                'penutup' => 'T',
                'saldo' => 'T'
            ];
            DB::table('jurnal_saldo')->insert($data);
        }

        $saldo_penutup2 = DB::select("SELECT a.id_akun, b.debit, b.kredit
        FROM akun as a
        left join(
            SELECT b.id_akun, sum(b.debit) as debit, sum(b.kredit) as kredit
            FROM jurnal as b
            where b.tgl BETWEEN '$tgl1' and '$tgl2' and b.id_buku != '8'
            group by b.id_akun
        ) as b on b.id_akun  = a.id_akun;");

        foreach ($saldo_penutup2 as $s) {
            $max = DB::table('notas')->where('id_buku', '5')->max('nomor_nota');
            $no_nota = empty($max) ? '1000' : $max + 1;
            DB::table('notas')->insert(['nomor_nota' => $no_nota, 'id_buku' => '5']);
            $data = [
                'id_akun' => $s->id_akun,
                'debit' => empty($s->debit) ? 0 : $s->debit,
                'kredit' => empty($s->kredit) ? 0 : $s->kredit,
                'no_nota' => "PEN-$no_nota",
                'tgl' => $tgl2,
                'admin' => auth()->user()->name,
            ];
            DB::table('jurnal_saldo_sebelum_penutup')->insert($data);
        }
        DB::table('jurnal')->whereBetween('tgl', ['2023-01-01', $tgl2])->update(['penutup' => 'Y']);
        DB::table('jurnal_saldo')->whereBetween('tgl', [$tgl1, $tgl2])->update(['penutup' => 'Y']);

        return redirect()->route('penutup.index')->with('sukses', 'Berhasil Tutup Saldo');
    }

    function cancel_penutup(Request $r)
    {

        DB::table('jurnal')->where('id_buku', '8')->whereBetween('tgl', [$r->tgl1, $r->tgl2])->delete();
        DB::table('jurnal_saldo')->whereBetween('tgl', [$r->tgl1, $r->tgl2])->delete();
        DB::table('jurnal_saldo_sebelum_penutup')->whereBetween('tgl', [$r->tgl1, $r->tgl2])->delete();
        $tgl1 = date('Y-m-01', strtotime($r->tgl1));
        DB::table('jurnal')->where('id_buku', '!=', '8')->whereBetween('tgl', [$tgl1, $r->tgl2])->update(['penutup' => 'T']);
        return redirect()->route('penutup.index')->with('sukses', 'Jurnal penutup berhasil di cancel');
    }
}
