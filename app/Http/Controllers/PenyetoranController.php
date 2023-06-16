<?php

namespace App\Http\Controllers;

use App\Exports\PenyetoranExport;
use App\Models\Jurnal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use SettingHal;

class PenyetoranController extends Controller
{
    protected $tgl1, $tgl2, $id_proyek, $period, $id_buku;
    public $akunPenjualan = '34';
    public $akunPiutangDagang = '12';

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
        $tgl1 = $this->tgl1;
        $tgl2 = $this->tgl2;
        $id_user = auth()->user()->id;

        $setor = DB::select("SELECT a.debit, a.id_jurnal,a.no_nota,a.id_akun, a.tgl,b.nm_akun,a.admin,a.ket FROM jurnal as a
        LEFT JOIN akun as b ON a.id_akun = b.id_akun
        WHERE a.id_buku = 10 AND a.kredit = 0 AND a.id_akun != 12 AND a.setor = 'T'
        AND a.tgl BETWEEN '$tgl1' AND '$tgl2'");

        $data = [
            'title' => 'Penyetoran',
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'setor' => $setor,

            'user' => User::where('posisi_id', 1)->get(),
            'halaman' => 16,
            'create' => SettingHal::btnHal(67, $id_user),
            'export' => SettingHal::btnHal(68, $id_user),
            'bayar' => SettingHal::btnHal(69, $id_user),
            'edit' => SettingHal::btnHal(70, $id_user),
        ];
        return view('penyetoran2.penyetoran', $data);
    }

    public function perencanaan(Request $r)
    {
        $no_nota = buatNota('setor_penjualan_umum', 'urutan');
        $getNota = $r->no_order;
        $string = implode(", ", $getNota);
        $setor = DB::select("SELECT a.debit, a.id_jurnal, a.no_nota,a.id_akun, a.tgl,b.nm_akun,a.admin FROM `jurnal` as a
        LEFT JOIN akun as b ON a.id_akun = b.id_akun
        WHERE 
        a.id_jurnal IN ($string)");
        $data = [
            'title' => 'Perencanaan Nota',
            'no_nota' => $no_nota,
            'setor' => $setor,
            'akun' => DB::table('akun')->select('id_akun', 'nm_akun')->get()
        ];
        return view('penyetoran2.perencanaan', $data);
    }

    public function bayar(Request $r)
    {
        $no_nota = buatNota('setor_penjualan_umum', 'no_nota');
        $getNota = $r->no_order;
        $string = implode(", ", $getNota);
        $setor = DB::select("SELECT a.debit, a.id_jurnal, a.no_nota,a.id_akun, a.tgl,b.nm_akun,a.admin FROM `jurnal` as a
        LEFT JOIN akun as b ON a.id_akun = b.id_akun
        WHERE 
        a.id_jurnal IN ($string)");
        $data = [
            'title' => 'Setor Nota',
            'no_nota' => $no_nota,
            'setor' => $setor,
            'akun' => DB::table('akun')->select('id_akun', 'nm_akun')->get()
        ];
        return view('penyetoran2.bayar', $data);
    }

    public function save_perencanaan(Request $r)
    {
        for ($i = 0; $i < count($r->id_jurnal); $i++) {
            DB::table('setor_penjualan_umum')->insert([
                'nota_setor' => $r->nota_setor,
                'urutan' => $r->urutan,
                'ket' => $r->ket,
                'tgl' => $r->tgl,
                'admin' => auth()->user()->name,
                'nominal' => $r->debit[$i],
                'id_jurnal' => $r->id_jurnal[$i],
                'id_akun' => $r->id_akun[$i],
                'no_nota' => $r->no_nota[$i],
            ]);

            DB::table('jurnal')->where('id_jurnal', $r->id_jurnal[$i])->update([
                'setor' => 'Y'
            ]);
        }
        return redirect()->route('penyetoran2.index')->with('sukses', 'Data Berhasil Direncanakan');
    }

    public function load_perencanaan()
    {
        $perencanaan = DB::select("SELECT a.no_nota,a.nota_setor,sum(nominal) as nominal,b.nm_akun,a.tgl FROM setor_penjualan_umum as a
        LEFT JOIN akun as b ON a.id_akun = b.id_akun 
        WHERE a.selesai = 'T'
        GROUP BY a.nota_setor");
        $data = [
            'perencanaan' => $perencanaan
        ];
        return view('penyetoran2.load_perencanaan', $data);
    }

    public function delete(Request $r)
    {
        $getNoNota = DB::table('setor_penjualan_umum')->where('nota_setor', $r->nota_setor)->first()->no_nota;
        DB::table('jurnal')->where('no_nota', $getNoNota)->update(['setor' => 'T']);
        DB::table('setor_penjualan_umum')->where('nota_setor', $r->nota_setor)->delete();

        return redirect()->route('penyetoran2.index')->with('sukses', 'Data Berhasil dihapus');
    }

    public function edit($nota)
    {
        $getNoNota = DB::table('setor_penjualan_umum as a')
            ->join('akun as b', 'a.id_akun', 'b.id_akun')
            ->where('a.nota_setor', $nota)->get();
        $data = [
            'datas' => $getNoNota,
            'nota' => $nota,
            'akun' => DB::table('akun')->select('id_akun', 'nm_akun')->get()
        ];
        return view('penyetoran2.save_setor', $data);
    }

    public function save_setor(Request $r)
    {
        DB::table('setor_penjualan_umum')->where('nota_setor', $r->nota_setor)->update(['selesai' => 'Y']);

        $ttlNominal = 0;
        for ($i = 0; $i < count($r->id_akun); $i++) {
            $id_akun = $r->id_akun[$i];
            $max_akun = DB::table('jurnal')->latest('urutan')->where('id_akun', $id_akun)->first();
            $akun = DB::table('akun')->where('id_akun', $id_akun)->first();
            $urutan = empty($max_akun) ? '1001' : ($max_akun->urutan == 0 ? '1001' : $max_akun->urutan + 1);

            $ttlNominal += $r->nominal[$i];
            Jurnal::create([
                'tgl' => $r->tgl_setor,
                'id_akun' => $r->id_akun[$i],
                'id_buku' => 11,
                'no_nota' => $r->nota_setor,
                'ket' => $r->ket,
                'debit' => 0,
                'kredit' => $r->nominal[$i],
                'no_urut' => $akun->inisial . '-' . $urutan,
                'urutan' => $urutan,
                'admin' => auth()->user()->name,
            ]);
        }
        $id_akun2 = $r->akun_setor;
        $max_akun = DB::table('jurnal')->latest('urutan')->where('id_akun', $id_akun2)->first();
        $akun = DB::table('akun')->where('id_akun', $id_akun2)->first();
        $urutan = empty($max_akun) ? '1001' : ($max_akun->urutan == 0 ? '1001' : $max_akun->urutan + 1);

        Jurnal::create([
            'tgl' => $r->tgl_setor,
            'id_akun' => $r->akun_setor,
            'id_buku' => 11,
            'no_nota' => $r->nota_setor,
            'ket' => $r->ket,
            'kredit' => 0,
            'debit' => $ttlNominal,
            'no_urut' => $akun->inisial . '-' . $urutan,
            'urutan' => $urutan,
            'admin' => auth()->user()->name,
        ]);

        return redirect()->route('penyetoran2.index')->with('sukses', 'Data Berhasil Di setor');
    }

    public function load_history()
    {
        $perencanaan = DB::select("SELECT a.tgl, b.nm_akun, a.id_akun, a.debit, a.no_nota FROM jurnal as a
        LEFT JOIN akun as b ON a.id_akun = b.id_akun 
        WHERE a.id_buku = '11' AND a.kredit = 0
        ");
        $data = [
            'setor' => $perencanaan
        ];
        return view('penyetoran2.history', $data);
    }

    public function kembali($nota)
    {
        $getNoNota = DB::table('setor_penjualan_umum as a')
            ->join('akun as b', 'a.id_akun', 'b.id_akun')
            ->where('a.nota_setor', $nota)->get();

        $data = [
            'datas' => $getNoNota,
            'nota' => $nota,
            'akun' => DB::table('akun')->select('id_akun', 'nm_akun')->get()
        ];

        return view('penyetoran2.kembali', $data);
    }

    public function hapus_setor(Request $r)
    {
        DB::table('setor_penjualan_umum')->where('nota_setor', $r->nota_setor)->update(['selesai' => 'T']);
        DB::table('jurnal')->where('no_nota', $r->nota_setor)->delete();

        return redirect()->route('penyetoran2.index')->with('sukses', 'Data Berhasil Di setor');
    }

    public function print(Request $r)
    {
        $getNoNota = DB::table('setor_penjualan_umum as a')
            ->join('akun as b', 'a.id_akun', 'b.id_akun')
            ->where('a.nota_setor', $r->nota_setor)->get();

        $data = [
            'title' => 'Cetak Perencanaan',
            'detail' => DB::table('setor_penjualan_umum')->where('nota_setor', $r->nota_setor)->first(),
            'datas' => $getNoNota,
            'nota' => $r->nota_setor,
        ];

        return view('penyetoran2.print', $data);
    }

    public function print_setor(Request $r)
    {
        $getNoNota = DB::table('setor_penjualan_umum as a')
            ->join('akun as b', 'a.id_akun', 'b.id_akun')
            ->where('a.nota_setor', $r->nota_setor)->get();

        $data = [
            'title' => 'Cetak Setoran',
            'detail' => DB::table('jurnal as a')
                ->join('akun as b', 'a.id_akun', 'b.id_akun')
                ->where([['a.no_nota', $r->nota_setor], ['a.kredit', 0]])
                ->first(),
            'datas' => $getNoNota,
            'nota' => $r->nota_setor,
        ];

        return view('penyetoran2.print_setor', $data);
    }

    public function export(Request $r)
    {
        $tgl1 =  $r->tgl1;
        $tgl2 =  $r->tgl2;

        $tbl = DB::select("SELECT a.debit, a.id_jurnal,a.no_nota,a.id_akun, a.tgl,b.nm_akun,a.admin,a.ket FROM jurnal as a
        LEFT JOIN akun as b ON a.id_akun = b.id_akun
        WHERE a.id_buku = 10 AND a.kredit = 0 AND a.id_akun != 12 AND a.setor = 'T'
        AND a.tgl BETWEEN '$tgl1' AND '$tgl2'");

        $totalrow = count($tbl) + 1;

        return Excel::download(new PenyetoranExport($tbl, $totalrow), 'Export Penyetoran2.xlsx');
    }
}
