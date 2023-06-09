<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class PenjualanController extends Controller
{
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


    public function index(Request $r)
    {
        $tgl1 =  $this->tgl1;
        $tgl2 =  $this->tgl2;

        $data =  [
            'title' => 'Penjualan Agrilaras',
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'invoice' => DB::select("SELECT a.no_nota, a.tgl, a.tipe, a.admin, b.nm_customer, sum(a.total_rp) as ttl_rp, a.status
            FROM invoice_telur as a 
            left join customer as b on b.id_customer = a.id_customer
            where a.tgl between '$tgl1' and '$tgl2'
            group by a.no_nota
            order by a.urutan DESC
            ")

        ];
        return view('penjualan_agl.index', $data);
    }

    public function tbh_invoice_telur(Request $r)
    {
        $max = DB::table('invoice_telur')->latest('urutan')->first();

        if (empty($max)) {
            $nota_t = '1000';
        } else {
            $nota_t = $max->urutan + 1;
        }
        $data = [
            'title' => 'Buat Invoice',
            'produk' => DB::table('telur_produk')->get(),
            'customer' => DB::table('customer')->get(),
            'nota' => $nota_t,
            'akun' => DB::table('akun')->whereIn('id_klasifikasi', ['1', '7'])->get()
        ];
        return view('penjualan_agl.invoice', $data);
    }

    public function loadkginvoice(Request $r)
    {
        $data = [
            'title' => 'Buat Invoice',
            'produk' => DB::table('telur_produk')->get(),
        ];
        return view('penjualan_agl.load_penjualankg', $data);
    }

    public function tambah_baris_kg(Request $r)
    {
        $data = [
            'title' => 'Buat Invoice',
            'produk' => DB::table('telur_produk')->get(),
            'count' => $r->count
        ];
        return view('penjualan_agl.tbh_bariskg', $data);
    }

    public function tbh_pembayaran(Request $r)
    {
        $data = [
            'count' => $r->count,
            'akun' => DB::table('akun')->whereIn('id_klasifikasi', ['1', '7'])->get()
        ];
        return view('penjualan_agl.tbh_pembayaran', $data);
    }

    public function save_penjualan_telur(Request $r)
    {
        $max = DB::table('invoice_telur')->latest('urutan')->first();

        if (empty($max)) {
            $nota_t = '1000';
        } else {
            $nota_t = $max->urutan + 1;
        }
        for ($x = 0; $x < count($r->id_produk); $x++) {
            $data = [
                'tgl' => $r->tgl,
                'id_customer' => $r->customer,
                'tipe' => $r->tipe,
                'no_nota' => 'T' . $nota_t,
                'id_produk' => $r->id_produk[$x],
                'pcs' => $r->pcs[$x],
                'kg' => $r->kg[$x],
                'kg_jual' => $r->kg_jual[$x],
                'rp_satuan' => $r->rp_satuan[$x],
                'total_rp' => $r->total_rp[$x],
                'admin' => Auth::user()->name,
                'urutan' => $nota_t
            ];
            DB::table('invoice_telur')->insert($data);
        }
        $max_akun = DB::table('jurnal')->latest('urutan')->where('id_akun', '517')->first();
        $akun = DB::table('akun')->where('id_akun', '517')->first();

        $urutan = empty($max_akun) ? '1001' : ($max_akun->urutan == 0 ? '1001' : $max_akun->urutan + 1);
        $data = [
            'tgl' => $r->tgl,
            'no_nota' => 'T' . $nota_t,
            'id_akun' => '517',
            'id_buku' => '6',
            'ket' => 'Penjualan Telur',
            'debit' => 0,
            'kredit' => $r->total_penjualan,
            'admin' => Auth::user()->name,
            'no_urut' => $akun->inisial . '-' . $urutan,
            'urutan' => $urutan,
        ];
        DB::table('jurnal')->insert($data);

        $data = [
            'tgl' => $r->tgl,
            'no_nota' => 'T' . $nota_t,
            'debit' => 0,
            'kredit' => $r->total_penjualan,
        ];
        DB::table('bayar_telur')->insert($data);



        for ($x = 0; $x < count($r->id_akun); $x++) {
            $max_akun2 = DB::table('jurnal')->latest('urutan')->where('id_akun', $r->id_akun[$x])->first();
            $akun2 = DB::table('akun')->where('id_akun', $r->id_akun[$x])->first();
            $urutan2 = empty($max_akun2) ? '1001' : ($max_akun2->urutan == 0 ? '1001' : $max_akun2->urutan + 1);
            $data = [
                'tgl' => $r->tgl,
                'no_nota' => 'T' . $nota_t,
                'id_akun' => $r->id_akun[$x],
                'id_buku' => '6',
                'ket' => 'Penjualan Telur',
                'debit' => $r->debit[$x],
                'kredit' => $r->kredit[$x],
                'admin' => Auth::user()->name,
                'no_urut' => $akun2->inisial . '-' . $urutan2,
                'urutan' => $urutan2,
            ];
            DB::table('jurnal')->insert($data);
            $data = [
                'tgl' => $r->tgl,
                'no_nota' => 'T' . $nota_t,
                'debit' => $r->debit[$x],
                'kredit' => $r->kredit[$x],
            ];
            DB::table('bayar_telur')->insert($data);
        }

        return redirect()->route('penjualan_agrilaras')->with('sukses', 'Data berhasil ditambahkan');
    }
}
