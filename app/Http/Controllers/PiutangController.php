<?php

namespace App\Http\Controllers;

use App\Exports\PiutangExport;
use App\Models\Jurnal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use SettingHal;

class PiutangController extends Controller
{
    public $akunPiutangDagang = '12';
    public $akunPenjualan = '34';
    protected $tgl1, $tgl2, $period;
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
            $tgl = "$tahun" . "-" . "$bulan" . "-" . "01";

            $this->tgl1 = date('Y-m-01', strtotime($tgl));
            $this->tgl2 = date('Y-m-t', strtotime($tgl));
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

    public function index()
    {
        $tgl1 =  $this->tgl1;
        $tgl2 =  $this->tgl2;
        $id_user = auth()->user()->id;

        $jual = DB::select("SELECT a.admin,a.ket,a.no_nota, a.no_penjualan, a.status,a.total_rp,a.tgl, c.kredit, c.debit FROM `invoice_agl` as a
                    LEFT JOIN (
                        SELECT b.no_nota,b.nota_jurnal, SUM(debit) as debit, SUM(kredit) as kredit FROM bayar_agl as b
                        GROUP BY b.nota_jurnal
                    ) c ON c.nota_jurnal = a.no_nota
                    WHERE a.tgl BETWEEN '$tgl1' AND '$tgl2' ORDER BY a.id_invoice_bk ASC;");

        $semuaPiutang = DB::select("SELECT a.admin,a.ket,a.no_nota, a.no_penjualan, a.status,a.total_rp,a.tgl, c.kredit, c.debit FROM `invoice_agl` as a
                LEFT JOIN (
                    SELECT b.no_nota,b.nota_jurnal, SUM(debit) as debit, SUM(kredit) as kredit FROM bayar_agl as b
                    GROUP BY b.nota_jurnal
                ) c ON c.nota_jurnal = a.no_nota
                WHERE a.tgl BETWEEN '2022-01-01' AND '$tgl2' ORDER BY a.id_invoice_bk ASC;");

        $data = [
            'title' => 'Pembayaran AGL',
            'akun' => DB::table('akun')->get(),
            'jual' => $jual,
            'semuaPiutang' => $semuaPiutang,
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,

            'user' => User::where('posisi_id', 1)->get(),
            'halaman' => 16,
            'create' => SettingHal::btnHal(67, $id_user),
            'export' => SettingHal::btnHal(68, $id_user),
            'bayar' => SettingHal::btnHal(69, $id_user),
            'edit' => SettingHal::btnHal(70, $id_user),
        ];
        return view('piutang.piutang', $data);
    }

    public function bayar(Request $r)
    {
        $no_pembayaran = DB::selectOne("SELECT MAX(CAST(SUBSTRING_INDEX(no_nota, '-', -1) AS UNSIGNED)) AS no_nota
        FROM bayar_agl;");
        $no_pembayaran = empty($no_pembayaran->no_nota) ? '1000' : $no_pembayaran->no_nota + 1;
        $data = [
            'title' => 'Pembayaran AGL',
            'no_order' => $r->no_order,
            'akun' => DB::table('akun')->get(),
            'no_pembayaran' => $no_pembayaran
        ];
        return view('piutang.bayar', $data);
    }

    public function tbh_baris(Request $r)
    {
        $data = [
            'akun' => DB::table('akun')->get(),
            'count' => $r->count,
        ];
        return view('jual.tbh_baris', $data);
    }

    public function create(Request $r)
    {
        $no_pembayaran = $r->no_pembayaran;
        $tgl_bayar = $r->tgl_bayar;
        for ($i = 0; $i < count($r->id_akun); $i++) {
            $notaGabungan = implode(", ", $r->no_nota);
            $max_akun2 = DB::table('jurnal')->latest('urutan')->where('id_akun', $r->id_akun[$i])->first();
            $akun2 = DB::table('akun')->where('id_akun', $r->id_akun[$i])->first();

            $urutan2 = empty($max_akun2) ? '1001' : ($max_akun2->urutan == 0 ? '1001' : $max_akun2->urutan + 1);
            // masuk penjualan di debit
            $dataD = [
                'tgl' => $tgl_bayar,
                'no_nota' => $no_pembayaran,
                'id_akun' => $r->id_akun[$i],
                'id_buku' => '10',
                'ket' => "Pembayaran $notaGabungan",
                'no_urut' => $akun2->inisial . '-' . $urutan2,
                'urutan' => $urutan2,
                'kredit' => $r->kredit[$i] ?? 0,
                'debit' => $r->debit[$i] ?? 0,
                'admin' => auth()->user()->name,
            ];
            Jurnal::create($dataD);
        }

        for ($i = 0; $i < count($r->bayar); $i++) {
            $max_akun = DB::table('jurnal')->latest('urutan')->where('id_akun', $this->akunPiutangDagang)->first();
            $akun = DB::table('akun')->where('id_akun', $this->akunPiutangDagang)->first();

            $urutan = empty($max_akun) ? '1001' : ($max_akun->urutan == 0 ? '1001' : $max_akun->urutan + 1);
            // masuk penjualan di kredit
            $dataK = [
                'tgl' => $tgl_bayar,
                'no_nota' => $no_pembayaran,
                'id_akun' => $this->akunPiutangDagang,
                'id_buku' => '10',
                'ket' => 'Pembayaran-' . $r->no_penjualan[$i],
                'debit' => 0,
                'no_urut' => $akun->inisial . '-' . $urutan,
                'urutan' => $urutan,
                'kredit' => $r->bayar[$i],
                'admin' => auth()->user()->name,
            ];
            Jurnal::create($dataK);

            $no_nota = $r->no_nota[$i];
            $bayar = $r->bayar[$i];
            $total_rp = $r->total_rp[$i];

            DB::table('bayar_agl')->insert([
                'tgl' => $tgl_bayar,
                'no_nota' => $no_pembayaran,
                'debit' => 0,
                'kredit' => $bayar,
                'ket' => '',
                'admin' => auth()->user()->name,
                'nota_jurnal' => $no_nota,
            ]);

            DB::table('invoice_agl')->where('no_nota', $no_nota)->update(['status' => $bayar < $total_rp ? 'unpaid' : 'paid']);
        }
        return redirect()->route('piutang.index')->with('sukses', 'Data Berhasil Dibuat');
    }

    public function get_kredit_pi(Request $r)
    {
        $bayar = DB::select("SELECT a.admin,a.nota_jurnal,a.no_nota,a.tgl,a.debit,a.kredit FROM bayar_agl as a
        LEFT JOIN invoice_agl as b ON a.nota_jurnal = b.no_nota
        WHERE a.nota_jurnal = '$r->no_nota'
        GROUP BY a.id_bayar_bk");

        $data = [
            'bayar' => $bayar
        ];
        return view('piutang.get_kredit_agl', $data);
    }

    public function edit_pembayaran(Request $r)
    {
        $data = [
            'title' => 'Edit Pembayaran',
            'akun' => DB::table('akun')->get(),
            'edit' => DB::select("SELECT a.nota_jurnal,a.tgl, a.no_nota, b.no_penjualan,b.total_rp,a.kredit FROM `bayar_agl` as a
            LEFT JOIN invoice_agl b ON a.nota_jurnal = b.no_nota
            WHERE a.no_nota = '$r->no_nota';"),
        ];

        return view('piutang.edit_pembayaran', $data);
    }

    public function edit_save_pembayaran(Request $r)
    {
        $no_pembayaran = $r->no_pembayaran;
        $tgl_bayar = $r->tgl_bayar;

        Jurnal::where('no_nota', $no_pembayaran)->delete();
        DB::table('bayar_agl')->where('no_nota', $no_pembayaran)->delete();

        for ($i = 0; $i < count($r->id_akun); $i++) {
            // masuk penjualan di debit
            $dataD = [
                'tgl' => $tgl_bayar,
                'no_nota' => $no_pembayaran,
                'id_akun' => $r->id_akun[$i],
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
                'ket' => 'Pembayaran-' . $r->no_penjualan[$i],
                'debit' => 0,
                'kredit' => $r->bayar[$i],
                'admin' => auth()->user()->name,
            ];
            Jurnal::create($dataK);

            $no_nota = $r->no_nota[$i];
            $bayar = $r->bayar[$i];
            $total_rp = $r->total_rp[$i];

            DB::table('bayar_agl')->insert([
                'tgl' => $tgl_bayar,
                'no_nota' => $no_pembayaran,
                'debit' => 0,
                'kredit' => $bayar,
                'ket' => '',
                'admin' => auth()->user()->name,
                'nota_jurnal' => $no_nota,
            ]);

            DB::table('invoice_agl')->where('no_nota', $no_nota)->update(['status' => $bayar < $total_rp ? 'unpaid' : 'paid']);
        }
        return redirect()->route('piutang.index')->with('sukses', 'Data Berhasil Diedit');
    }

    public function export(Request $r)
    {
        $tgl1 =  $r->tgl1;
        $tgl2 =  $r->tgl2;

        $tbl = DB::select("SELECT a.ket,a.no_nota, a.no_penjualan, a.status,a.total_rp,a.tgl, c.kredit, c.debit FROM `invoice_agl` as a
        LEFT JOIN (
            SELECT b.no_nota,b.nota_jurnal, SUM(debit) as debit, SUM(kredit) as kredit FROM bayar_agl as b
            GROUP BY b.nota_jurnal
        ) c ON c.nota_jurnal = a.no_nota
        WHERE a.tgl BETWEEN '$tgl1' AND '$tgl2' ORDER BY a.id_invoice_bk ASC;");

        $totalrow = count($tbl) + 1;

        return Excel::download(new PiutangExport($tbl, $totalrow), 'Export Piutang.xlsx');
    }
}
