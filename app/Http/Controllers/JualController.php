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
            'jual' => DB::table('invoice_pi')->get(),
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

        DB::table('invoice_pi')->insert([
            'no_penjualan' => $r->no_penjualan,
            'no_nota' => 'PNJL-' . $no_nota,
            'tgl' => $r->tgl,
            'total_rp' => $r->total_rp,
            'status' => 'unpaid',
            'admin' => auth()->user()->name
        ]);
        return redirect()->route($this->route)->with('sukses', 'Data Berhasil Dibuat');
    }

    public function bayar(Request $r)
    {
        $no_pembayaran = DB::selectOne("SELECT MAX(CAST(SUBSTRING_INDEX(no_nota, '-', -1) AS UNSIGNED)) AS no_nota
        FROM bayar_pi;");
        $no_pembayaran = empty($no_pembayaran->no_nota) ? '1000' : $no_pembayaran->no_nota + 1;
        $data = [
            'title' => 'Pembayaran PI',
            'no_order' => $r->no_order,
            'akun' => DB::table('akun')->get(),
            'no_pembayaran' =>$no_pembayaran
        ];
        return view('jual.bayar', $data);
    }

    public function create(Request $r)
    {
        $no_pembayaran = $r->no_pembayaran;
        $tgl_bayar = $r->tgl_bayar;
        $ttlBayar = 0;
        for ($i = 0; $i < count($r->no_nota); $i++) {
            $no_nota = $r->no_nota[$i];
            $bayar = $r->bayar[$i];
            $total_rp = $r->total_rp[$i];
            $ttlBayar += $bayar;

            DB::table('bayar_pi')->insert([
                'tgl' => $tgl_bayar,
                'no_nota' => $no_nota,
                'debit' => 0,
                'kredit' => $bayar,
                'ket' => '',
                'admin' => auth()->user()->name,
                'nota_jurnal' => $no_nota,
            ]);
            // if ($bayar > $total_rp) {
            //     $status = 'paid';
            //     $dataD = [
            //         'tgl' => $tgl_bayar,
            //         'no_nota' => 'PBYR-' . $no_pembayaran,
            //         'id_akun' => 136,
            //         'id_buku' => '2',
            //         'ket' => 'Penjualan-' . $no_nota,
            //         'debit' => $bayar - $total_rp,
            //         'kredit' => 0,
            //         'admin' => auth()->user()->name,
            //     ];
            //     Jurnal::create($dataD);
            // }

            // if ($bayar < $total_rp) {
            //     $status = 'unpaid';
            //     $dataD = [
            //         'tgl' => $tgl_bayar,
            //         'no_nota' => 'PBYR-' . $no_pembayaran,
            //         'id_akun' => 136,
            //         'id_buku' => '2',
            //         'ket' => 'Penjualan-' . $no_nota,
            //         'kredit' => $total_rp - $bayar,
            //         'debit' => 0,
            //         'admin' => auth()->user()->name,
            //     ];
            //     Jurnal::create($dataD);
            // }
            // DB::table('bayar_pi')->where('no_nota', $no_nota)->update(['status' => $status ?? 'paid']);
            // DB::table('bayar_pi')->where('no_nota', $no_nota)->update([
            //     'no_pembayaran' => 'PBYR-' . $no_pembayaran,
            //     'tgl_bayar' => $tgl_bayar,
            // ]);

            
        }

        // for ($i=0; $i < count($r->id_akun); $i++) { 
        //     // masuk penjualan di debit
        //     $dataD = [
        //         'tgl' => $tgl_bayar,
        //         'no_nota' => 'PBYR-'.$no_pembayaran,
        //         'id_akun' => $r->id_akun[$i],
        //         'id_buku' => '2',
        //         'ket' => 'Pembayaran-' . $no_pembayaran,
        //         'kredit' => 0,
        //         'debit' => $r->setor[$i],
        //         'admin' => auth()->user()->name,
        //     ];
        //     Jurnal::create($dataD);

        //     // masuk penjualan di kredit
        //     $dataK = [
        //         'tgl' => $tgl_bayar,
        //         'no_nota' => 'PBYR-'.$no_pembayaran,
        //         'id_akun' => 134,
        //         'id_buku' => '2',
        //         'ket' => 'Pembayaran-' . $no_pembayaran,
        //         'debit' => 0,
        //         'kredit' => $ttlBayar,
        //         'admin' => auth()->user()->name,
        //     ];
        //     Jurnal::create($dataK);
        // }

        // if ($ttlBayar > $r->setor) {
        //     $dataD = [
        //         'tgl' => $tgl_bayar,
        //         'no_nota' => 'PBYR-' . $no_pembayaran,
        //         'id_akun' => 136,
        //         'id_buku' => '2',
        //         'ket' => 'Penjualan-' . $no_nota,
        //         'debit' => $r->setor - $ttlBayar,
        //         'kredit' => 0,
        //         'admin' => auth()->user()->name,
        //     ];
        //     Jurnal::create($dataD);
        // }

        // if ($ttlBayar < $r->setor) {
        //     $dataD = [
        //         'tgl' => $tgl_bayar,
        //         'no_nota' => 'PBYR-' . $no_pembayaran,
        //         'id_akun' => 136,
        //         'id_buku' => '2',
        //         'ket' => 'Penjualan-' . $no_nota,
        //         'kredit' => $ttlBayar - $r->setor,
        //         'debit' => 0,
        //         'admin' => auth()->user()->name,
        //     ];
        //     Jurnal::create($dataD);
        // }


        return redirect()->route($this->route)->with('sukses', 'Data Berhasil Dibuat');
    }

    public function tbh_baris(Request $r)
    {
        $data = [
            'akun' => DB::table('akun')->get(),
            'count' => $r->count,
        ];
        return view('jual.tbh_baris', $data);
    }

    public function delete(Request $r)
    {
        DB::table('invoice_pi')->where('no_nota', $r->no_nota)->delete();
        return redirect()->route($this->route)->with('sukses', 'Data Berhasil Dihapus');
    }
}
