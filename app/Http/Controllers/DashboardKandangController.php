<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardKandangController extends Controller
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

    public function index()
    {
        $data = [
            'title' => 'Dashboard Kandang',
            'kandang' => DB::table('kandang')->get(),
            'telur' => DB::table('telur_produk')->get()
        ];
        return view('dashboard_kandang.index', $data);
    }

    public function tambah_telur(Request $r)
    {
        DB::table('stok_telur')->where([['id_kandang', $r->id_kandang], ['tgl', $r->tgl]])->delete();
        DB::table('stok_telur_new')->where([['id_kandang', $r->id_kandang], ['tgl', $r->tgl]])->delete();

        for ($i = 0; $i < count($r->id_telur); $i++) {
            $ikat = $r->ikat[$i];
            $ikat_kg = $r->ikat_kg[$i];

            $rak = $r->rak[$i];
            $rak_kg = $r->rak_kg[$i];

            $pcs = $r->pcs[$i];
            $ttl_kg_pcs = $r->ttl_kg_pcs[$i];

            $ttlPcs = ($ikat * 180) + ($rak * 30) + $pcs;
            $ttlKg = $ikat_kg + $rak_kg + $ttl_kg_pcs;

            $data = [
                'id_kandang' => $r->id_kandang,
                'id_telur' => $r->id_telur[$i],
                'tgl' => $r->tgl,
                'admin' => auth()->user()->name,
                'ikat' => $ikat,
                'ikat_kg' => $ikat_kg,
                'rak' => $rak,
                'rak_kg' => $rak_kg,
                'pcs' => $pcs,
                'pcs_kg' => $r->pcs_kg[$i],
                'potongan_pcs' => $r->potongan_pcs[$i],
                'ttl_kg_pcs' => $ttl_kg_pcs,
            ];
            DB::table('stok_telur_new')->insert($data);

            $dataStok = [
                'id_kandang' => $r->id_kandang,
                'id_telur' => $r->id_telur[$i],
                'tgl' => $r->tgl,
                'pcs' => $ttlPcs,
                'kg' => $ttlKg,
                'pcs_kredit' => 0,
                'kg_kredit' => 0,
                'admin' => auth()->user()->name,
                'id_gudang' => 1,
                'nota_transfer' => '',
                'ket' => '',
            ];
            DB::table('stok_telur')->insert($dataStok);
        }

        return redirect()->route('dashboard_kandang.index')->with('sukses', 'Data Berhasil Ditambahkan');
    }

    public function load_telur($id_kandang)
    {
        $data = [
            'telur' => DB::table('telur_produk')->get(),
            'kandang' => DB::table('kandang')->where('id_kandang', $id_kandang)->first()
        ];
        return view('dashboard_kandang.modal.load_telur', $data);
    }

    public function tambah_populasi(Request $r)
    {
        DB::table('populasi')->where([['id_kandang', $r->id_kandang], ['tgl', $r->tgl]])->delete();
        DB::table('populasi')->insert([
            'id_kandang' => $r->id_kandang,
            'mati' => $r->mati,
            'jual' => $r->jual,
            'tgl' => $r->tgl,
            'admin' => auth()->user()->name
        ]);
        $pesan = $r->mati > 3 ? 'error' : 'sukses';
        return redirect()->route('dashboard_kandang.index')->with($pesan, 'Data Berhasil Ditambahkan');
    }

    public function load_populasi($id_kandang)
    {
        $data = [
            'populasi' => DB::table('populasi')->where([['id_kandang', $id_kandang], ['tgl', date('Y-m-d')]])->first(),
            'kandang' => DB::table('kandang')->where('id_kandang', $id_kandang)->first()
        ];
        return view('dashboard_kandang.modal.load_populasi', $data);
    }

    public function transfer_stok()
    {
        $tgl1 =  $this->tgl1;
        $tgl2 =  $this->tgl2;
        $transfer = DB::table('stok_telur as a')
            ->join('telur_produk as c', 'a.id_telur', 'c.id_produk_telur')
            ->where('a.id_gudang', 2)
            ->get();
        $data = [
            'title' => 'Transfer Stok',
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'transfer' => $transfer
        ];
        return view('dashboard_kandang.history.transfer_stok', $data);
    }

    public function add_transfer_stok(Request $r)
    {
        $data = [
            'title' => 'Transfer Stock',
            'id_gudang' => $r->id_gudang,
            'gudang' => DB::table('gudang_telur')->where('id_gudang_telur', $r->id_gudang)->first(),
            'gudang_telur' => DB::table('gudang_telur')->where('id_gudang_telur', '!=', $r->id_gudang)->get()
        ];
        return view('stok_telur.transfer', $data);
    }

    public function save_transfer(Request $r)
    {
        $cek = DB::table('stok_telur')->where('nota_transfer', '!=', '')->first();
        $nota_t = empty($cek) ? 1000 + 1 : str()->remove('TF-', $cek->nota_transfer) + 1;

        for ($x = 0; $x < count($r->id_telur); $x++) {
            $data = [
                'tgl' => $r->tgl,
                'id_telur' => $r->id_telur[$x],
                'pcs_kredit' => $r->pcs[$x],
                'kg_kredit' => $r->kg[$x],
                'admin' => auth()->user()->name,
                'nota_transfer' => 'TF-' . $nota_t,
                'id_gudang' => $r->id_gudang_dari
            ];
            DB::table('stok_telur')->insert($data);
            $data = [
                'tgl' => $r->tgl,
                'id_telur' => $r->id_telur[$x],
                'pcs' => $r->pcs[$x],
                'kg' => $r->kg[$x],
                'ket' => $r->ket[$x],
                'admin' => auth()->user()->name,
                'nota_transfer' => 'TF-' . $nota_t,
                'id_gudang' => $r->id_gudang
            ];
            DB::table('stok_telur')->insert($data);
        }
        return redirect()->route('dashboard_kandang.index')->with('sukses', 'Data berhasil di transfer');
    }
}
