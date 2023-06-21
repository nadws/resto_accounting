<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Stock_telurController extends Controller
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

        if (empty($r->id_gudang)) {
            $id_gudang = '1';
        } else {
            $id_gudang = $r->id_gudang;
        }

        $data =  [
            'title' => 'Stok Telur',
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'gudang' => DB::table('gudang_telur')->get(),
            'stok' => DB::select("SELECT a.id_stok_telur, a.tgl, a.ket, b.nm_kandang, c.nm_telur, a.pcs, a.kg, a.admin 
            FROM stok_telur as a 
            left join kandang as b on b.id_kandang = a.id_kandang
            left join telur_produk as c on c.id_produk_telur = a.id_telur
            where a.pcs != '0' and a.tgl between '$tgl1' and '$tgl2' and a.id_gudang = '$id_gudang'
            order by a.id_stok_telur DESC
            "),
            'id_gudang' => $id_gudang

        ];
        return view('stok_telur.index', $data);
    }

    public function tbh_stok_telur(Request $r)
    {
        $data =  [
            'title' => 'Tambah Stok Telur',
            'id_gudang' => $r->id_gudang

        ];
        return view('stok_telur.add', $data);
    }

    public function load_menu_telur(Request $r)
    {
        $data = [
            'title' => 'load menu telur',
            'kandang' => DB::table('kandang')->get(),
            'produk' => DB::table('telur_produk')->get(),
        ];
        return view('stok_telur.load', $data);
    }
    public function tbh_baris_telur(Request $r)
    {
        $data = [
            'title' => 'load menu telur',
            'kandang' => DB::table('kandang')->get(),
            'produk' => DB::table('telur_produk')->get(),
            'count' => $r->count
        ];
        return view('stok_telur.tambah', $data);
    }

    public function save_stok_telur(Request $r)
    {
        for ($x = 0; $x < count($r->id_kandang); $x++) {
            $data = [
                'id_kandang' => $r->id_kandang[$x],
                'id_telur' => $r->id_produk_telur[$x],
                'tgl' => $r->tgl,
                'pcs' => $r->pcs[$x],
                'kg' => $r->kg[$x],
                'admin' => Auth::user()->name,
                'id_gudang' => $r->id_gudang,
                'ket' => $r->ket
            ];
            DB::table('stok_telur')->insert($data);
        }
        return redirect()->route('stok_telur')->with('sukses', 'Data berhasil ditambahkan');
    }

    public function edit_telur(Request $r)
    {
        $data =  [
            'title' => 'Edit Stok Telur',
            'telur' => DB::table('stok_telur')->where('id_stok_telur', $r->id_stok_telur)->first(),
            'kandang' => DB::table('kandang')->get(),
            'produk' => DB::table('telur_produk')->get(),
        ];
        return view('stok_telur.edit', $data);
    }
    public function save_edit_stok_telur(Request $r)
    {

        $data = [
            'id_kandang' => $r->id_kandang,
            'id_telur' => $r->id_produk_telur,
            'tgl' => $r->tgl,
            'pcs' => $r->pcs,
            'kg' => $r->kg,
            'admin' => Auth::user()->name,
        ];
        DB::table('stok_telur')->where('id_stok_telur', $r->id_stok_telur)->update($data);
        return redirect()->route('stok_telur')->with('sukses', 'Data berhasil ditambahkan');
    }

    public function delete_telur(Request $r)
    {
        DB::table('stok_telur')->where('id_stok_telur', $r->no_nota)->delete();
        return redirect()->route('stok_telur')->with('sukses', 'Data berhasil dihapus');
    }



    // Transfer

    public function transfer_stok_telur(Request $r)
    {
        $data = [
            'title' => 'Transfer Stock',
            'id_gudang' => $r->id_gudang,
            'gudang' => DB::table('gudang_telur')->where('id_gudang_telur', $r->id_gudang)->first(),
            'gudang_telur' => DB::table('gudang_telur')->where('id_gudang_telur', '!=', $r->id_gudang)->get()
        ];
        return view('stok_telur.transfer', $data);
    }

    public function load_transfer_telur(Request $r)
    {
        $data = [
            'title' => 'Transfer Telur',
            'produk' => DB::table('telur_produk')->get(),
        ];
        return view('stok_telur.transfer_telur', $data);
    }
    public function tbh_baris_transfer(Request $r)
    {
        $data = [
            'title' => 'Transfer Telur',
            'produk' => DB::table('telur_produk')->get(),
            'count' => $r->count
        ];
        return view('stok_telur.tbh_transfer_telur', $data);
    }

    public function get_stok(Request $r)
    {
        $stok = DB::selectOne("SELECT sum(a.pcs) as pcs , sum(a.kg) as kg , sum(a.pcs_kredit) as pcs_kredit, sum(a.kg_kredit) as kg_kredit
        FROM stok_telur as a
        where a.id_telur = '$r->id_telur' and a.id_gudang = '$r->id_gudang_telur'
        ");

        $data = [
            'pcs' => $stok->pcs - $stok->pcs_kredit,
            'kg' => $stok->kg - $stok->kg_kredit
        ];
        echo json_encode($data);
    }

    public function save_transfer_stok_telur(Request $r)
    {
        $max = DB::table('stok_telur_alpa')->latest('urutan')->first();

        if (empty($max)) {
            $nota_t = '1000';
        } else {
            $nota_t = $max->urutan + 1;
        }
        for ($x = 0; $x < count($r->id_telur); $x++) {
            $data = [
                'tgl' => $r->tgl,
                'id_telur' => $r->id_telur[$x],
                'ket' => $r->ket[$x],
                'pcs' => $r->pcs[$x],
                'kg' => $r->kg[$x],
                'dari_gudang' => '1',
                'ke_gudang' => '2',
                'no_nota' => 'TF-' . $nota_t,
                'urutan' => $nota_t,
                'admin' => Auth::user()->name,
            ];
            DB::table('stok_telur_alpa')->insert($data);
        }
        for ($x = 0; $x < count($r->id_telur); $x++) {
            $data = [
                'tgl' => $r->tgl,
                'id_telur' => $r->id_telur[$x],
                'pcs_kredit' => $r->pcs[$x],
                'kg_kredit' => $r->kg[$x],
                'admin' => Auth::user()->name,
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
                'admin' => Auth::user()->name,
                'nota_transfer' => 'TF-' . $nota_t,
                'id_gudang' => $r->id_gudang
            ];
            DB::table('stok_telur')->insert($data);
        }
        return redirect()->route('stok_telur')->with('sukses', 'Data berhasil di transfer');
    }
}
