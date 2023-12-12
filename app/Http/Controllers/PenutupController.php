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
            where  a.id_klasifikasi ='1';"),

            'biaya' => DB::select("SELECT a.id_akun, a.nm_akun, b.debit, b.kredit
            FROM akun as a 
            left join (
            SELECT b.id_akun , sum(b.debit) as debit , sum(b.kredit) as kredit
                FROM jurnal as b 
                where b.tgl BETWEEN '$tgl1Tutup' and '$tgl2Tutup' and b.id_buku not in('8')
                GROUP by b.id_akun
            ) as b on b.id_akun = a.id_akun
            where  a.id_klasifikasi in (2,3);"),
            'tgl' => $tgl,
            'penutup' => $tgl->penutup,
            'tgl1Tutup' => $tgl1Tutup,
            'tgl2Tutup' => $tgl2Tutup,
            // 'total' => DB::selectOne("SELECT count(a.id_akun) as total FROM akun as a where  a.iktisar='T'"),

            'aktiva' => DB::selectOne("SELECT a.id_akun FROM jurnal as a where a.id_akun = 51 and a.tgl between '$tgl1Tutup' and '$tgl2Tutup' and a.id_buku = '7' "),
            'peralatan' => DB::selectOne("SELECT a.id_akun FROM jurnal as a where a.id_akun = 58 and a.tgl between '$tgl1Tutup' and '$tgl2Tutup' and a.id_buku = '7' "),
            'atk' => DB::selectOne("SELECT a.id_akun FROM jurnal as a where a.id_akun = 91 and a.tgl between '$tgl1Tutup' and '$tgl2Tutup' and a.id_buku = '7' "),
            'cancel' => DB::select("SELECT a.tgl FROM jurnal as a where a.id_buku = '5' group by a.tgl ")
        ];
        return view('pembukuan.penutup.index', $data);
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
            $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '5')->first();
            $no_nota = empty($max) ? '1000' : $max->nomor_nota + 1;
            DB::table('notas')->insert(['nomor_nota' => $no_nota, 'id_buku' => '5']);

            if ($kredit_pembelian[$x] + $debit_pembelian[$x] == 0) {
                # code...
            } else {
                $data = [
                    'tgl' => $tgl,
                    'no_nota' => "PEN-$no_nota",
                    'id_akun' => $id_akun_pembelian[$x],
                    'id_buku' => '5',
                    'ket' => 'Penutup Ikhtisar',
                    'debit' => $debit_pembelian[$x],
                    'kredit' => $kredit_pembelian[$x],
                    'admin' => Auth::user()->name,
                ];
                DB::table('jurnal')->create($data);
            }
        }
        for ($x = 0; $x < count($id_akun_biaya); $x++) {
            $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '5')->first();
            $no_nota = empty($max) ? '1000' : $max->nomor_nota + 1;
            DB::table('notas')->insert(['nomor_nota' => $no_nota, 'id_buku' => '5']);

            if ($debit_biaya[$x] + $kredit_biaya[$x] == 0) {
                # code...
            } else {
                $data = [
                    'tgl' => $tgl,
                    'no_nota' => "PEN-$no_nota",
                    'id_akun' => $id_akun_biaya[$x],
                    'id_buku' => '5',
                    'ket' => 'Penutup Ikhtisar',
                    'debit' => $debit_biaya[$x],
                    'kredit' => $kredit_biaya[$x],
                    'admin' => Auth::user()->name,
                ];
                DB::table('jurnal')->create($data);
            }
        }
        for ($x = 0; $x < count($id_akun_modal); $x++) {
            $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '5')->first();
            $no_nota = empty($max) ? '1000' : $max->nomor_nota + 1;
            DB::table('notas')->insert(['nomor_nota' => $no_nota, 'id_buku' => '5']);

            if ($debit_modal[$x] + $kredit_modal[$x] == 0) {
                # code...
            } else {
                $data = [
                    'tgl' => $tgl,
                    'no_nota' => "PEN-$no_nota",
                    'id_akun' => $id_akun_modal[$x],
                    'id_buku' => '5',
                    'ket' => 'Penutup Ikhtisar',
                    'debit' => $debit_modal[$x],
                    'kredit' => $kredit_modal[$x],
                    'admin' => Auth::user()->name,
                ];
                DB::table('jurnal')->create($data);
            }
        }


        $max_prive = DB::table('notas')->latest('nomor_nota')->where('id_buku', '5')->first();
        $no_nota_prive = empty($max_prive) ? '1000' : $max_prive->nomor_nota + 1;
        DB::table('notas')->insert(['nomor_nota' => $no_nota_prive, 'id_buku' => '5']);

        if ($prive_biasa == 0) {
            # code...
        } else {
            $data = [
                'tgl' => $tgl2,
                'no_nota' => "PEN-$no_nota_prive",
                'id_akun' => 95,
                'id_buku' => '5',
                'ket' => 'Penutup Ikhtisar',
                'debit' => $prive_biasa,
                'kredit' => 0,
                'admin' => Auth::user()->name,
            ];
            DB::table('jurnal')->create($data);
            $data = [
                'tgl' => $tgl2,
                'no_nota' => "PEN-$no_nota_prive",
                'id_akun' => 57,
                'id_buku' => '5',
                'ket' => 'Penutup Ikhtisar',
                'debit' => 0,
                'kredit' => $prive_biasa,
                'admin' => Auth::user()->name,
            ];
            DB::table('jurnal')->create($data);
        }

        // if ($r->laba_independent > 0) {
        //     $data = [
        //         'id_akun' => 95,
        //         'kredit' => $r->laba_independent,
        //         'debit' => 0,
        //         'ket' => 'Saldo Penutup',
        //         'id_buku' => '5',
        //         'no_nota' => "LB-$no_nota",
        //         'tgl' => $tgl2,
        //         'tgl_dokumen' => $tgl2,
        //         'admin' => auth()->user()->name,

        //     ];
        //     DB::table('jurnal')->insert($data);
        // } else {
        //     $data = [
        //         'id_akun' => 95,
        //         'kredit' => 0,
        //         'debit' => $r->laba_independent * -1,
        //         'ket' => 'Saldo Penutup',
        //         'id_buku' => '5',
        //         'no_nota' => "LB-$no_nota",
        //         'tgl' => $tgl2,
        //         'tgl_dokumen' => $tgl2,
        //         'admin' => auth()->user()->name,
        //     ];
        //     DB::table('jurnal')->insert($data);
        // }

        $uang_ditarik = DB::selectOne("SELECT b.id_akun, sum(b.debit) as debit , sum(b.kredit) as kredit
        FROM jurnal as b
        left join akun as c on c.id_akun = b.id_akun
        where b.tgl BETWEEN '$tgl1' and '$tgl2' and b.id_buku = '6' and c.id_akun in(SELECT t.id_akun FROM akuncash_ibu as t where t.kategori = '3');");

        $uang_keluar = DB::selectOne("SELECT a.id_akun , sum(a.debit) as debit , sum(a.kredit) as kredit
        FROM jurnal as a 
        left join (
            SELECT j.no_nota, j.id_akun
            FROM jurnal as j
            LEFT JOIN akun as b ON b.id_akun = j.id_akun
            WHERE j.debit != '0'
            GROUP BY j.no_nota
        ) d ON a.no_nota = d.no_nota AND d.id_akun != a.id_akun
        left join akun as e on e.id_akun = a.id_akun
        WHERE  a.tgl between '$tgl1' and '$tgl2'  and a.id_buku in ('2','12','10') and 
        e.id_akun in (SELECT t.id_akun FROM akuncash_ibu as t where t.kategori = '6');");

        $biaya_admin = DB::selectOne("SELECT sum(a.debit) as debit FROM jurnal as a where a.id_akun = '8' and a.tgl between
        '$tgl1' and '$tgl2' and a.id_buku = '6' ");


        $hutang = $uang_ditarik->debit - $biaya_admin->debit - $uang_keluar->kredit;

        $saldo_penutup = DB::select("SELECT a.id_akun, b.debit, b.kredit
        FROM akun as a
        left join(
            SELECT b.id_akun, sum(b.debit) as debit, sum(b.kredit) as kredit
            FROM jurnal as b
            where b.tgl BETWEEN '$tgl1' and '$tgl2' 
            group by b.id_akun
        ) as b on b.id_akun  = a.id_akun;");

        foreach ($saldo_penutup as $s) {
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
            where b.tgl BETWEEN '$tgl1' and '$tgl2' and b.id_buku != '5'
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
    }
}
