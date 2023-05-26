<?php

namespace App\Http\Controllers;

use App\Exports\JualExport;
use App\Exports\KlasifikasiExport;
use App\Models\Jurnal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class JualController extends Controller
{
    public $route = 'jual.index';
    public $akunPiutangDagang = '12';
    public $akunPenjualan = '34';

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

        $this->id_proyek = $r->id_proyek ?? 0;
        $this->id_buku = $r->id_buku ?? 2;
    }

    public function index()
    {
        $tgl1 =  $this->tgl1;
        $tgl2 =  $this->tgl2;

        $jual = DB::select("SELECT a.admin,a.ket,a.no_nota, a.no_penjualan, a.status,a.total_rp,a.tgl, c.kredit, c.debit FROM `invoice_pi` as a
                    LEFT JOIN (
                        SELECT b.no_nota,b.nota_jurnal, SUM(debit) as debit, SUM(kredit) as kredit FROM bayar_pi as b
                        GROUP BY b.nota_jurnal
                    ) c ON c.nota_jurnal = a.no_nota
                    WHERE a.tgl BETWEEN '$tgl1' AND '$tgl2' ORDER BY a.id_invoice_bk ASC;");

        $semuaPiutang = DB::select("SELECT a.admin,a.ket,a.no_nota, a.no_penjualan, a.status,a.total_rp,a.tgl, c.kredit, c.debit FROM `invoice_pi` as a
                LEFT JOIN (
                    SELECT b.no_nota,b.nota_jurnal, SUM(debit) as debit, SUM(kredit) as kredit FROM bayar_pi as b
                    GROUP BY b.nota_jurnal
                ) c ON c.nota_jurnal = a.no_nota
                WHERE a.tgl BETWEEN '2022-01-01' AND '$tgl2' ORDER BY a.id_invoice_bk ASC;");
        $data = [
            'title' => 'Penjualan',
            'akun' => DB::table('akun')->get(),
            'jual' => $jual,
            'semuaPiutang' => $semuaPiutang,
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
        ];
        return view('jual.jual', $data);
    }

    public function add()
    {
        $data = [
            'title' => 'Tambah Nota'
        ];
        return view('jual.add', $data);
    }

    public function tbh_add(Request $r)
    {
        $data = [
            'count' => $r->count,
        ];
        return view('jual.tbh_add', $data);
    }

    public function piutang(Request $r)
    {
        for ($i = 0; $i < count($r->no_penjualan); $i++) {
            $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '2')->first();

            $no_nota = empty($max) ? '1000' : $max->nomor_nota + 1;
            DB::table('notas')->insert(['nomor_nota' => $no_nota, 'id_buku' => '2']);

            // masuk penjualan di debit
            $dataD = [
                'tgl' => $r->tgl[$i],
                'no_nota' => 'PNJL-' . $no_nota,
                'id_akun' => $this->akunPiutangDagang,
                'id_buku' => '2',
                'ket' => 'Penjualan-' . $r->no_penjualan[$i],
                'kredit' => 0,
                'debit' => $r->total_rp[$i],
                'admin' => auth()->user()->name,
            ];
            Jurnal::create($dataD);

            // masuk penjualan di kredit
            $dataK = [
                'tgl' => $r->tgl[$i],
                'no_nota' => 'PNJL-' . $no_nota,
                'id_akun' => $this->akunPenjualan,
                'id_buku' => '2',
                'ket' => 'Penjualan-' . $r->no_penjualan[$i],
                'debit' => 0,
                'kredit' => $r->total_rp[$i],
                'admin' => auth()->user()->name,
            ];
            Jurnal::create($dataK);

            DB::table('invoice_pi')->insert([
                'no_penjualan' => $r->no_penjualan[$i],
                'no_nota' => 'PNJL-' . $no_nota,
                'tgl' => $r->tgl[$i],
                'ket' => $r->ket[$i],
                'total_rp' => $r->total_rp[$i],
                'status' => 'unpaid',
                'admin' => auth()->user()->name
            ]);
        }

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
            'no_pembayaran' => $no_pembayaran
        ];
        return view('jual.bayar', $data);
    }

    public function get_kredit_pi(Request $r)
    {
        $bayar = DB::select("SELECT a.admin,a.nota_jurnal,a.no_nota,a.tgl,a.debit,a.kredit FROM bayar_pi as a
        LEFT JOIN invoice_pi as b ON a.nota_jurnal = b.no_nota
        WHERE a.nota_jurnal = '$r->no_nota'
        GROUP BY a.id_bayar_bk");

        $data = [
            'bayar' => $bayar
        ];
        return view('jual.get_kredit_pi', $data);
    }

    public function create(Request $r)
    {
        $no_pembayaran = $r->no_pembayaran;
        $tgl_bayar = $r->tgl_bayar;

        for ($i = 0; $i < count($r->id_akun); $i++) {
            // masuk penjualan di debit
            $dataD = [
                'tgl' => $tgl_bayar,
                'no_nota' => $no_pembayaran,
                'id_akun' => $r->id_akun[$i],
                'id_buku' => '2',
                'ket' => '',
                'kredit' => $r->kredit[$i] ?? 0,
                'debit' => $r->debit[$i] ?? 0,
                'admin' => auth()->user()->name,
            ];
            Jurnal::create($dataD);
        }

        for ($i = 0; $i < count($r->bayar); $i++) {

            // masuk penjualan di kredit
            $dataK = [
                'tgl' => $tgl_bayar,
                'no_nota' => $no_pembayaran,
                'id_akun' => $this->akunPiutangDagang,
                'id_buku' => '2',
                'ket' => 'Pembayaran-' . $r->no_penjualan[$i],
                'debit' => 0,
                'kredit' => $r->bayar[$i],
                'admin' => auth()->user()->name,
            ];
            Jurnal::create($dataK);

            $no_nota = $r->no_nota[$i];
            $bayar = $r->bayar[$i];
            $total_rp = $r->total_rp[$i];

            DB::table('bayar_pi')->insert([
                'tgl' => $tgl_bayar,
                'no_nota' => $no_pembayaran,
                'debit' => 0,
                'kredit' => $bayar,
                'ket' => '',
                'admin' => auth()->user()->name,
                'nota_jurnal' => $no_nota,
            ]);

            DB::table('invoice_pi')->where('no_nota', $no_nota)->update(['status' => $bayar < $total_rp ? 'unpaid' : 'paid']);
        }
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

    public function edit(Request $r)
    {
        $data = [
            'title' => 'Edit Penjualan',
            'edit' => DB::table('invoice_pi')->where('no_nota', $r->no_nota)->first(),
        ];

        return view('jual.edit', $data);
    }
    
    public function edit_save_penjualan(Request $r)
    {
        DB::table('jurnal')->where([['no_nota', $r->nota_jurnal], ['debit', 0]])->update([
            'kredit' => $r->total_rp,
        ]);
        DB::table('jurnal')->where([['no_nota', $r->nota_jurnal], ['kredit', 0]])->update([
            'debit' => $r->total_rp,
        ]);
        DB::table('invoice_pi')->where('id_invoice_bk', $r->id_invoice_bk)->update([
            'no_penjualan' => $r->no_penjualan,
            'ket' => $r->ket,
            'total_rp' => $r->total_rp,
            'tgl' => $r->tgl,
            'admin' => auth()->user()->name
        ]);

        return redirect()->route('jual.index')->with('sukses', 'Data Berhasil Diedit');
    }

    public function edit_pembayaran(Request $r)
    {
        $data = [
            'title' => 'Edit Pembayaran',
            'akun' => DB::table('akun')->get(),
            'edit' => DB::select("SELECT a.nota_jurnal,a.tgl, a.no_nota, b.no_penjualan,b.total_rp,a.kredit FROM `bayar_pi` as a
            LEFT JOIN invoice_pi b ON a.nota_jurnal = b.no_nota
            WHERE a.no_nota = '$r->no_nota';"),
        ];

        return view('jual.edit_pembayaran', $data);
    }

    public function edit_save_pembayaran(Request $r)
    {
        $no_pembayaran = $r->no_pembayaran;
        $tgl_bayar = $r->tgl_bayar;

        Jurnal::where('no_nota', $no_pembayaran)->delete();
        DB::table('bayar_pi')->where('no_nota', $no_pembayaran)->delete();

        for ($i = 0; $i < count($r->id_akun); $i++) {
            // masuk penjualan di debit
            $dataD = [
                'tgl' => $tgl_bayar,
                'no_nota' => $no_pembayaran,
                'id_akun' => $r->id_akun[$i],
                'id_buku' => '2',
                'ket' => '',
                'kredit' => $r->kredit[$i] ?? 0,
                'debit' => $r->debit[$i] ?? 0,
                'admin' => auth()->user()->name,
            ];
            Jurnal::create($dataD);
        }

        for ($i = 0; $i < count($r->bayar); $i++) {

            // masuk penjualan di kredit
            $dataK = [
                'tgl' => $tgl_bayar,
                'no_nota' => $no_pembayaran,
                'id_akun' => $this->akunPiutangDagang,
                'id_buku' => '2',
                'ket' => 'Pembayaran-' . $r->no_penjualan[$i],
                'debit' => 0,
                'kredit' => $r->bayar[$i],
                'admin' => auth()->user()->name,
            ];
            Jurnal::create($dataK);

            $no_nota = $r->no_nota[$i];
            $bayar = $r->bayar[$i];
            $total_rp = $r->total_rp[$i];

            DB::table('bayar_pi')->insert([
                'tgl' => $tgl_bayar,
                'no_nota' => $no_pembayaran,
                'debit' => 0,
                'kredit' => $bayar,
                'ket' => '',
                'admin' => auth()->user()->name,
                'nota_jurnal' => $no_nota,
            ]);

            DB::table('invoice_pi')->where('no_nota', $no_nota)->update(['status' => $bayar < $total_rp ? 'unpaid' : 'paid']);
        }
        return redirect()->route($this->route)->with('sukses', 'Data Berhasil Diedit');
    }

    public function export(Request $r)
    {
        $tgl1 =  $r->tgl1;
        $tgl2 =  $r->tgl2;

        $tbl = DB::select("SELECT a.ket,a.no_nota, a.no_penjualan, a.status,a.total_rp,a.tgl, c.kredit, c.debit FROM `invoice_pi` as a
        LEFT JOIN (
            SELECT b.no_nota,b.nota_jurnal, SUM(debit) as debit, SUM(kredit) as kredit FROM bayar_pi as b
            GROUP BY b.nota_jurnal
        ) c ON c.nota_jurnal = a.no_nota
        WHERE a.tgl BETWEEN '$tgl1' AND '$tgl2' ORDER BY a.id_invoice_bk ASC;");

        $totalrow = count($tbl) + 1;

        return Excel::download(new JualExport($tbl, $totalrow), 'Export Penjualan.xlsx');
    }
}
