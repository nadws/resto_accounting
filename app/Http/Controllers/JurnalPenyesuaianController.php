<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JurnalPenyesuaianController extends Controller
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
    function index(Request $r)
    {
        $tgl1 =  $this->tgl1;
        $tgl2 =  $this->tgl2;
        $jurnal =  DB::select("SELECT a.penutup, a.id_jurnal,a.no_urut,a.admin, a.id_akun, a.tgl, a.debit, a.kredit, a.ket,a.no_nota, b.nm_akun, c.nm_post, d.nm_proyek FROM jurnal as a 
            left join akun as b on b.id_akun = a.id_akun
            left join tb_post_center as c on c.id_post_center = a.id_post_center
            left join proyek as d on d.id_proyek = a.id_proyek
            where a.id_buku = '7' and a.tgl between '$tgl1' and '$tgl2' order by a.id_jurnal DESC");

        $id_user = auth()->user()->id;
        $data =  [
            'title' => 'Jurnal Penyesuaian',
            'jurnal' => $jurnal,
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'user' => User::where('posisi_id', 1)->get(),
        ];
        return view('persediaan.penyesuaian.index', $data);
    }

    function aktiva(Request $r)
    {
        $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '7')->first();
        $max_tgl = DB::selectOne("SELECT max(a.tgl) as tgl FROM depresiasi_aktiva as a");
        $max_tgl_akt = DB::selectOne("SELECT min(a.tgl) as tgl FROM aktiva as a");

        if (empty($max_tgl->tgl)) {
            $tgl = date('Y-m-t', strtotime($max_tgl_akt->tgl));
            $tgl1 = date('Y-m-01', strtotime($tgl));
        } else {
            $tgl_asli = date('Y-m-01', strtotime($max_tgl->tgl));
            $tgl = date('Y-m-t', strtotime("next month", strtotime($tgl_asli)));
            $tgl1 = date('Y-m-01', strtotime($tgl));
        }
        if (empty($max)) {
            $nota_t = '1000';
        } else {
            $nota_t = $max->nomor_nota + 1;
        }

        $id_user = auth()->user()->id;
        $data =  [
            'title' => 'Jurnal Penyesuaian',
            'nota' => $nota_t,
            'akun' => DB::table('akun')->get(),
            'akunDebit' => DB::table('akun')->where('id_akun', 27)->first(),
            'akunKredit' => DB::table('akun')->where('id_akun', 26)->first(),
            'aktiva' => DB::select("SELECT a.*, c.beban FROM aktiva as a 
            left join kelompok_aktiva as b on b.id_kelompok = a.id_kelompok
            left join(
            SELECT sum(c.b_penyusutan) as beban , c.id_aktiva
                FROM depresiasi_aktiva as c
                group by c.id_aktiva
            ) as c on c.id_aktiva = a.id_aktiva
            where a.tgl between '2017-01-01' and '$tgl' 
            order by a.tgl ASC
            "),
            'tgl' => $tgl,
            'user' => User::where('posisi_id', 1)->get(),
        ];
        return view('persediaan.penyesuaian.aktiva', $data);
    }

    public function save_penyesuaian_aktiva(Request $r)
    {
        $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '4')->first();

        if (empty($max)) {
            $nota_t = '1000';
        } else {
            $nota_t = $max->nomor_nota + 1;
        }
        DB::table('notas')->insert(['nomor_nota' => $nota_t, 'id_buku' => '7']);
        $max_akun = DB::table('jurnal')->latest('urutan')->where('id_akun', $r->id_akun_debit)->first();
        $akun = DB::table('akun')->where('id_akun', $r->id_akun_debit)->first();

        $urutan = empty($max_akun) ? '1001' : ($max_akun->urutan == 0 ? '1001' : $max_akun->urutan + 1);
        $data_kredit = [
            'tgl' => $r->tgl,
            'no_nota' => 'JPA-' . $nota_t,
            'id_akun' => $r->id_akun_debit,
            'id_buku' => '7',
            'ket' => 'Penyesuaian Aktiva',
            'debit' => $r->debit_kredit,
            'kredit' => '0',
            'admin' => Auth::user()->name,
            'kode_penyesuaian' => 'JPA', 'no_urut' => $akun->inisial . '-' . $urutan,
            'urutan' => $urutan,
        ];
        DB::table('jurnal')->insert($data_kredit);

        $max_akun2 = DB::table('jurnal')->latest('urutan')->where('id_akun', $r->id_akun_kredit)->first();
        $akun2 = DB::table('akun')->where('id_akun', $r->id_akun_kredit)->first();

        $urutan = empty($max_akun2) ? '1001' : ($max_akun2->urutan == 0 ? '1001' : $max_akun2->urutan + 1);
        $data_debit = [
            'tgl' => $r->tgl,
            'no_nota' => 'JPA-' . $nota_t,
            'id_akun' => $r->id_akun_kredit,
            'id_buku' => '7',
            'ket' => 'Penyesuaian Aktiva',
            'kredit' => $r->debit_kredit,
            'debit' => '0',
            'admin' => Auth::user()->name,
            'kode_penyesuaian' => 'JPA',
            'no_urut' => $akun->inisial . '-' . $urutan,
            'urutan' => $urutan,
        ];
        DB::table('jurnal')->insert($data_debit);

        for ($x = 0; $x < count($r->id_aktiva); $x++) {
            $data = [
                'id_aktiva' => $r->id_aktiva[$x],
                'tgl' => $r->tgl,
                'b_penyusutan' => $r->b_penyusutan[$x],
                'admin' => auth()->user()->name
            ];
            DB::table('depresiasi_aktiva')->insert($data);
        }

        return redirect()->route('jurnalpenyesuaian.index')->with('sukses', 'Data berhasil ditambahkan');
    }

    function peralatan(Request $r)
    {
        $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '4')->first();
        $max_tgl = DB::selectOne("SELECT max(a.tgl) as tgl FROM depresiasi_peralatan as a");
        $max_tgl_akt = DB::selectOne("SELECT min(a.tgl) as tgl FROM peralatan as a");
        if (empty($max_tgl->tgl)) {
            $tgl = date('Y-m-t', strtotime($max_tgl_akt->tgl));
            $tgl1 = date('Y-m-01', strtotime($tgl));
        } else {
            $tgl_asli = date('Y-m-01', strtotime($max_tgl->tgl));
            $tgl = date('Y-m-t', strtotime("next month", strtotime($tgl_asli)));
            $tgl1 = date('Y-m-01', strtotime($tgl));
        }

        if (empty($max)) {
            $nota_t = '1000';
        } else {
            $nota_t = $max->nomor_nota + 1;
        }
        $data =  [
            'title' => 'Jurnal Penyesuaian',
            'akunAtk' => DB::table('akun')->where('id_akun', 28)->first(),
            'akunBiaya' => DB::table('akun')->where('id_akun', 29)->first(),
            'nota' => $nota_t,
            'akun' => DB::table('akun')->get(),
            'aktiva' => DB::select("SELECT a.*, c.beban FROM peralatan as a 
            left join kelompok_peralatan as b on b.id_kelompok = a.id_kelompok
            left join(
            SELECT sum(c.b_penyusutan) as beban , c.id_aktiva
                FROM depresiasi_peralatan as c
                group by c.id_aktiva
            ) as c on c.id_aktiva = a.id_aktiva
            where a.tgl between '2017-01-01' and '$tgl' 
            order by a.tgl ASC
            "),
            'tgl' => $tgl
        ];
        return view('persediaan.penyesuaian.peralatan', $data);
    }

    public function save_peralatan(Request $r)
    {
        $admin = auth()->user()->name;
        $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '7')->first();

        if (empty($max)) {
            $nota_t = '1000';
        } else {
            $nota_t = $max->nomor_nota + 1;
        }
        DB::table('notas')->insert(['nomor_nota' => $nota_t, 'id_buku' => '7']);

        $data_kredit = [
            'tgl' => $r->tgl,
            'no_nota' => 'JPP-' . $nota_t,
            'id_akun' => $r->id_akun_kredit,
            'id_buku' => '7',
            'ket' => 'Penyesuaian Peralatan',
            'kredit' => $r->debit_kredit,
            'debit' => '0',
            'admin' => $admin,
            'kode_penyesuaian' => 'JPP'
        ];
        DB::table('jurnal')->insert($data_kredit);

        $data_debit = [
            'tgl' => $r->tgl,
            'no_nota' => 'JPP-' . $nota_t,
            'id_akun' => $r->id_akun_debit,
            'id_buku' => '7',
            'ket' => 'Penyesuaian Peralatan',
            'debit' => $r->debit_kredit,
            'kredit' => '0',
            'admin' => $admin,
        ];
        DB::table('jurnal')->insert($data_debit);


        for ($x = 0; $x < count($r->id_aktiva); $x++) {
            $data = [
                'id_aktiva' => $r->id_aktiva[$x],
                'tgl' => $r->tgl,
                'b_penyusutan' => $r->b_penyusutan[$x],
                'admin' => $admin
            ];
            DB::table('depresiasi_peralatan')->insert($data);
        }

        return redirect()->route('jurnalpenyesuaian.index')->with('sukses', 'Data berhasil ditambahkan');
    }

    function atk(Request $r)
    {
        $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '7')->first();
        $max_tgl = DB::selectOne("SELECT max(a.tgl) as tgl FROM jurnal as a where a.id_buku = '7' and a.id_akun = '30'")->tgl;
        if (empty($max_tgl)) {
            $tgl = date('Y-m-t', strtotime(date('Y-m-d')));
        } else {
            $tgl1 = date('Y-m-15', strtotime($max_tgl));
            $tgl = Carbon::parse($tgl1)->addMonth()->toDateString();
        }
        $tgl2 = date('Y-m-t', strtotime($tgl));
        if (empty($max)) {
            $nota_t = '1000';
        } else {
            $nota_t = $max->nomor_nota + 1;
        }
        $tglHariIni = date('Y-m-d');



        $data = [
            'title' => 'Jurnal Penyesuaian ATK',
            'nota' => $nota_t,
            'akunAtk' => DB::table('akun')->where('id_akun', 31)->first(),
            'akunBiaya' => DB::table('akun')->where('id_akun', 30)->first(),
            'atk' => DB::select("SELECT a.id_atk, a.cfm, a.nm_atk, (b.debit -  b.kredit) as stok_sisa, (b.rupiah / b.debit) as rp_satuan
            FROM atk as a 
            left join (
                SELECT b.id_atk, sum(b.debit) as debit , sum(b.kredit) as kredit, sum(b.rupiah) as rupiah
                FROM stok_atk as b 
                where b.tgl BETWEEN '2023-01-01' and '$tgl2' 
                GROUP by b.id_atk
            ) as b on b.id_atk = a.id_atk;"),
            'tgl' => $tgl2
        ];
        return view('persediaan.penyesuaian.atk', $data);
    }

    public function save_atk(Request $r)
    {
        $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '4')->first();

        if (empty($max)) {
            $nota_t = '1000';
        } else {
            $nota_t = $max->nomor_nota + 1;
        }
        DB::table('notas')->insert(['nomor_nota' => $nota_t, 'id_buku' => '7']);
        $max_akun = DB::table('jurnal')->latest('urutan')->where('id_akun', $r->id_akun_debit)->first();
        $akun = DB::table('akun')->where('id_akun', $r->id_akun_debit)->first();

        $urutan = empty($max_akun) ? '1001' : ($max_akun->urutan == 0 ? '1001' : $max_akun->urutan + 1);

        $dataK = [
            'tgl' => $r->tgl,
            'no_nota' => $r->no_nota,
            'id_akun' => $r->id_akun_debit,
            'id_buku' => '7',
            'ket' => 'Penyesuaian Atk',
            'debit' => $r->debit_kredit,
            'no_urut' => $akun->inisial . '-' . $urutan,
            'urutan' => $urutan,
            'kredit' => 0,
            'admin' => auth()->user()->name,
            'kode_penyesuaian' => 'JPATK'
        ];
        DB::table('jurnal')->insert($dataK);

        $max_akun2 = DB::table('jurnal')->latest('urutan')->where('id_akun', $r->id_akun_kredit)->first();
        $akun2 = DB::table('akun')->where('id_akun', $r->id_akun_kredit)->first();

        $urutan = empty($max_akun2) ? '1001' : ($max_akun2->urutan == 0 ? '1001' : $max_akun2->urutan + 1);
        $dataK = [
            'tgl' => $r->tgl,
            'no_nota' => $r->no_nota,
            'id_akun' => $r->id_akun_kredit,
            'id_buku' => '7',
            'ket' => 'Penyesuaian Atk',
            'kredit' => $r->debit_kredit,
            'debit' => 0,
            'no_urut' => $akun2->inisial . '-' . $urutan,
            'urutan' => $urutan,
            'admin' => auth()->user()->name,
            'kode_penyesuaian' => 'JPATK'
        ];
        DB::table('jurnal')->insert($dataK);


        $invo = DB::selectOne("SELECT max(a.urutan) as urutan
        FROM stok_atk as a 
        ");

        if (empty($invo->urutan)) {
            $invoice = '1001';
        } else {
            $invoice = $invo->urutan + 1;
        }
        for ($i = 0; $i < count($r->id_atk); $i++) {
            $total = $r->sisa[$i] - $r->fisik[$i];
            $data = [
                'invoice' => 'STKM-' . $invoice,
                'tgl' => $r->tgl,
                'id_atk' => $r->id_atk[$i],
                'kredit' => $total,
                'rupiah_kredit' => $r->ttl_opname[$i],
                'urutan' => $invoice
            ];
            DB::table('stok_atk')->insert($data);
        }
        return redirect()->route('jurnalpenyesuaian.index')->with('sukses', 'Berhasil Penyesuaian Opname');
    }
}
