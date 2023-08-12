<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class CashflowController extends Controller
{
    protected $tgl1, $tgl2;
    public function __construct(Request $r)
    {
        if (empty($r->period)) {
            $this->tgl1 = date('2022-01-01');
            $this->tgl2 = date('Y-m-t');
        } elseif ($r->period == 'daily') {
            $this->tgl1 = date('Y-m-d');
            $this->tgl2 = date('Y-m-d');
        } elseif ($r->period == 'weekly') {
            $this->tgl1 = date('Y-m-d', strtotime("-6 days"));
            $this->tgl2 = date('Y-m-d');
        } elseif ($r->period == 'mounthly') {
            $bulan = $r->bulan;
            $tahun = $r->tahun;
            $tglawal = "$tahun" . "-" . "$bulan" . "-" . "01";
            $tglakhir = "$tahun" . "-" . "$bulan" . "-" . "01";

            $this->tgl1 = date('Y-m-01', strtotime($tglawal));
            $this->tgl2 = date('Y-m-t', strtotime($tglakhir));
        } elseif ($r->period == 'costume') {
            $this->tgl1 = $r->tgl1;
            $this->tgl2 = $r->tgl2;
        } elseif ($r->period == 'years') {
            $tahun = $r->tahunfilter;
            $tgl_awal = "$tahun" . "-" . "01" . "-" . "01";
            $tgl_akhir = "$tahun" . "-" . "12" . "-" . "01";

            $this->tgl1 = date('Y-m-01', strtotime($tgl_awal));
            $this->tgl2 = date('Y-m-t', strtotime($tgl_akhir));
        }
    }

    public function index(Request $r)
    {
        $tgl1 =  $this->tgl1;
        $tgl2 =  $this->tgl2;

        $datas = DB::table('tb_transaksi as a')
            ->where('user_id', auth()->user()->id)
            ->whereBetween('tgl', [$tgl1, $tgl2])
            ->orderBy('id_transaksi', 'DESC')
            ->get();

        $ttlDebit = 0;
        $ttlKredit = 0;
        foreach ($datas as $d) {
            $ttlDebit += $d->debit;
            $ttlKredit += $d->kredit;
        }

        $data = [
            'title' => "Cashflow",
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'datas' => $datas,
            'ttlDebit' => $ttlDebit,
            'ttlKredit' => $ttlKredit,
            'sisa' =>  $ttlDebit - $ttlKredit,
        ];
        return view('cashflow.cashflow', $data);
    }

    public function add()
    {
        $data = [
            'title' => 'Tambah Cashflow'
        ];
        return view('dashboard', $data);
    }

    public function keluar(Request $r)
    {
        $nominal = str_replace('.', '', $r->nominal);
        $pilihan = $r->pilihan;
        $nominal = Crypt::encrypt($nominal);
        dd($nominal .  ' = ' . Crypt::decrypt($nominal));
        DB::table('tb_transaksi')->insert([
            'user_id' => auth()->user()->id,
            'debit' => $pilihan == 'uangMasuk' ? $nominal : 0,
            'kredit' => $pilihan == 'uangMasuk' ? 0 : $nominal,
            'tgl' => $r->tgl,
            'tes' => $nominal,
            'ket' => $r->ket
        ]);
    }

    public function destroy(Request $r)
    {
        DB::table('tb_transaksi')->where('id_transaksi', $r->no_nota)->delete();
        return redirect()->route('cashflow.index')->with('sukses', 'Berhasil hapus data');
    }
}
