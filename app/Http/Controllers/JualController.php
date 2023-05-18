<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JualController extends Controller
{
    public $route = 'jual.index';
    protected $tgl1, $tgl2, $id_proyek, $period, $id_buku;
    public function __construct(Request $r)
    {

        
        if (empty($r->period)) {
            $this->tgl1 = date('Y-m-01');
            $this->tgl2 = date('Y-m-t');
        } elseif ($r->period == 'daily') {
            $this->tgl1 = date('Y-m-d');
            $this->tgl2 = date('Y-m-d');
        } elseif ($r->period == 'weekly') {
            $this->tgl1 = date('Y-m-d', strtotime("-6 days"));
            $this->tgl2 = date('Y-m-d');
        } elseif ($r->period == 'mounthly') {
            $this->tgl1 = date('Y-m-01');
            $this->tgl2 = date('Y-m-t');
        } elseif ($r->period == 'costume') {
            $this->tgl1 = $r->tgl1;
            $this->tgl2 = $r->tgl2;
        }


        $this->id_proyek = $r->id_proyek ?? 0;
        $this->id_buku = $r->id_buku ?? 2;
    }

    public function index()
    {
        $tgl1 =  $this->tgl1;
        $tgl2 =  $this->tgl2;
        $data = [
            'title' => 'Terima Pembayaran',
            'akun' => DB::table('akun')->get(),
            'jual' => DB::table('tb_jual')->get(),
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
        ];
        return view('jual.jual', $data);
    }

    public function piutang(Request $r)
    {
        $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '2')->first();

        $no_nota = empty($max) ? '1000' : $max->nomor_nota + 1;
        DB::table('notas')->insert(['nomor_nota' => $no_nota, 'id_buku' => '2']);

        // masuk penjualan di debit
        $dataD = [
            'tgl' => $r->tgl,
            'no_nota' => 'PNJL-' . $no_nota,
            'id_akun' => 135,
            'id_buku' => '2',
            'ket' => 'Penjualan-' . $r->no_penjualan,
            'kredit' => 0,
            'debit' => $r->total_rp,
            'admin' => auth()->user()->name,
        ];
        Jurnal::create($dataD);

        // masuk penjualan di kredit
        $dataK = [
            'tgl' => $r->tgl,
            'no_nota' => 'PNJL-' . $no_nota,
            'id_akun' => 134,
            'id_buku' => '2',
            'ket' => 'Penjualan-' . $r->no_penjualan,
            'debit' => 0,
            'kredit' => $r->total_rp,
            'admin' => auth()->user()->name,
        ];
        Jurnal::create($dataK);

        DB::table('tb_jual')->insert([
            'no_penjualan' => $r->no_penjualan,
            'no_nota' => 'PNJL-' . $no_nota,
            'total_rp' => $r->total_rp,
            'setor' => $r->setor,
            'status' => 'paid',
            'tgl' => date('Y-m-d'),
            'admin' => auth()->user()->name
        ]);
        return redirect()->route($this->route)->with('sukses', 'Data Berhasil Dibuat');
    }

    public function create(Request $r)
    {
        $no_nota = $r->no_nota;

        // masuk penjualan di debit
        $dataD = [
            'tgl' => $r->tgl,
            'no_nota' => 'PNJL-' . $no_nota,
            'id_akun' => 135,
            'id_buku' => '2',
            'ket' => 'Penjualan-' . $r->no_penjualan,
            'kredit' => 0,
            'debit' => $r->total_rp,
            'admin' => auth()->user()->name,
        ];
        Jurnal::create($dataD);

        // masuk penjualan di kredit
        $dataK = [
            'tgl' => $r->tgl,
            'no_nota' => 'PNJL-' . $no_nota,
            'id_akun' => 134,
            'id_buku' => '2',
            'ket' => 'Penjualan-' . $r->no_penjualan,
            'debit' => 0,
            'kredit' => $r->total_rp,
            'admin' => auth()->user()->name,
        ];
        Jurnal::create($dataK);

        DB::table('tb_jual')->where('no_nota', $no_nota)->update(['status' => 'unpaid']);
        return redirect()->route($this->route)->with('sukses', 'Data Berhasil Dibuat');
    }

    public function delete(Request $r)
    {

    }
}
