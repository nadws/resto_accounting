<?php

namespace App\Http\Controllers;

use App\Imports\BahanImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class BahanController extends Controller
{
    protected $bahan;
    
    public function __construct()
    {
        $this->bahan = DB::select("SELECT a.*, b.nm_satuan, c.nm_kategori,(d.debit - d.kredit) as stok
                        FROM tb_list_bahan a
                        JOIN tb_satuan b ON a.id_satuan = b.id_satuan
                        JOIN tb_kategori_bahan c ON a.id_kategori = c.id_kategori_bahan
                        LEFT join (
                            SELECT b.id_bahan, SUM(b.debit) as debit, sum(b.kredit) as kredit
                            FROM stok_bahan as b 
                            group by b.id_bahan
                        ) as d on d.id_bahan = a.id_list_bahan
                    ");
    }
    public function singkron()
    {
        $id_lokasi = app('id_lokasi');
        $tgl = date('Y-m-d', strtotime('- 1 days'));
        $response = Http::get("https://ptagafood.com/api/menu?id_lokasi=$id_lokasi&tgl1=$tgl&tgl2=$tgl");
            $invoice = $response['data']['menu'] ?? null;
            dd($invoice);
        //     $invo = json_decode(json_encode($invoice));
        //     foreach ($invo as $i) {
        // return app('id_lokasi');
    }
    public function index()
    {
        $data = [
            'title' => 'Bahan dan Barang Makanan',
            'satuan' => DB::table('tb_satuan')->get(),
            'kategori' => DB::table('tb_kategori_bahan')->get(),
            'bahan' => $this->bahan
        ];
        return view('persediaan.bahan_makanan.index', $data);
    }

    public function save(Request $r)
    {

        DB::table('tb_list_bahan')->insert([
            'nm_bahan' => $r->nm_bahan,
            'id_satuan' => $r->satuan_id,
            'id_kategori' => $r->kategori_id,
            'admin' => auth()->user()->name,
            'tgl' => date('Y-m-d')
        ]);
        return redirect()->route('bahan.index')->with('sukses', 'Data Berhasil ditambahkan');
    }

    public function opname(Request $r)
    {

        $data = [
            'title' => 'Opname Bahan dan Barang Makanan',
            'satuan' => DB::table('tb_satuan')->get(),
            'kategori' => DB::table('tb_kategori_bahan')->get(),
            'bahan' => $this->bahan
        ];
        return view('persediaan.bahan_makanan.opname', $data);
    }

    public function save_opname(Request $r)
    {
        $invo = DB::selectOne("SELECT max(a.urutan) as urutan
        FROM stok_bahan as a  WHERE a.invoice LIKE '%BHNOPN%'
        ");

        if (empty($invo->urutan)) {
            $invoice = '1001';
        } else {
            $invoice = $invo->urutan + 1;
        }
        for ($i = 0; $i < count($r->id_bahan); $i++) {
            $stok_program = (int) str()->remove(',', $r->stok_program[$i]);
            $stok_aktual = (int) str()->remove(',', $r->stok_aktual[$i]);
            $total = $stok_program - $stok_aktual;
            if ($total == 0) {
                continue;
            }
            if ($total < 0) {
                DB::table('stok_bahan')->insert([
                    'id_bahan' => $r->id_bahan[$i],
                    'invoice' => "BHNOPN-$invoice",
                    'urutan' => $invoice,
                    'tgl' => date('Y-m-d'),
                    'debit' => $total * -1,
                    'kredit' => 0,
                    'admin' => auth()->user()->name,
                ]);
            } else {
                DB::table('stok_bahan')->insert([
                    'id_bahan' => $r->id_bahan[$i],
                    'invoice' => "BHNOPN-$invoice",
                    'urutan' => $invoice,
                    'tgl' => date('Y-m-d'),
                    'kredit' => $total,
                    'debit' => 0,
                    'admin' => auth()->user()->name,
                ]);
            }
        }
        return redirect()->route('bahan.opname')->with('sukses', 'Data Berhasil diopname');
    }

    public function template()
    {
        return 'minta ke it';
    }
    public function import(Request $r)
    {
        Excel::import(new BahanImport, $r->file('file'));
        return redirect()->route('bahan.index')->with('sukses', 'Data berhasil import');
    }

    public function load_edit(Request $r)
    {
        $data = [
            'satuan' => DB::table('tb_satuan')->get(),
            'kategori' => DB::table('tb_kategori_bahan')->get(),
            'bahan' => DB::table('tb_list_bahan')->where('id_list_bahan', $r->id_bahan)->first()
        ];
        return view('persediaan.bahan_makanan.edit', $data);
    }

    public function update(Request $r)
    {
        DB::table('tb_list_bahan')->where('id_list_bahan', $r->id_bahan)->update([
            'nm_bahan' => $r->nm_bahan,
            'id_satuan' => $r->satuan_id,
            'id_kategori' => $r->kategori_id,
        ]);
        return redirect()->route('bahan.index')->with('sukses', 'Data Berhasil ditambahkan');
    }

    public function delete($id)
    {
        DB::table('tb_list_bahan')->where('id_list_bahan', $id)->delete();
        DB::table('stok_bahan')->where('id_bahan', $id)->delete();
        return redirect()->route('bahan.index')->with('sukses', 'Data Berhasil dihapus');
    }

    public function stok()
    {
        $data = [
            'title' => 'Stok Masuk',
            'invoice' => DB::select("SELECT a.tgl, a.invoice, sum(a.debit) as stok, a.admin
            FROM stok_bahan as a 
            where a.debit != 0 AND a.invoice LIKE '%BHNMSK%'
            group by a.invoice
            "),
        ];
        return view('persediaan.bahan_makanan.stok', $data);
    }

    public function stok_add()
    {
        $invo = DB::selectOne("SELECT max(a.urutan) as urutan
        FROM stok_bahan as a WHERE a.invoice LIKE '%BHNMSK%'
        ");

        if (empty($invo->urutan)) {
            $invoice = '1001';
        } else {
            $invoice = $invo->urutan + 1;
        }
        $data = [
            'title' => 'Stok Masuk',
            'invoice' => $invoice,
            'satuan' => DB::table('tb_satuan')->get(),
            'kategori' => DB::table('tb_kategori_bahan')->get(),
        ];
        return view('persediaan.bahan_makanan.stok_add', $data);
    }

    public function stok_tbh_baris(Request $r)
    {
        $data = [
            'count' => $r->count,
        ];
        return view('persediaan.bahan_makanan.stok_tbh_baris',$data);
    }

    public function load_produk_stok()
    {
        $atk = $this->bahan;

        $html = "<select name='id_bahan[]' class='select2_add pilihProduk'>";
        $html .= "<option value=''>Pilih Produk</option>";

        foreach ($atk as $a) {
            $html .= "<option value='{$a->id_list_bahan}'>{$a->nm_bahan} ({$a->nm_satuan})</option>";
        }

        $html .= "<option value='tambah'>+ tambah baru</option></select>";
        return $html;
    }

    public function save_stk_masuk(Request $r)
    {
        for ($i=0; $i < count($r->id_bahan); $i++) { 
            $debit = (int) str()->remove(',', $r->debit[$i]);
            $total_rp = (int) str()->remove(',', $r->total_rp[$i]);

            DB::table('stok_bahan')->insert([
                'id_bahan' => $r->id_bahan[$i],
                'invoice' => "BHNMSK-$r->invoice",
                'urutan' => $r->invoice,
                'tgl' => date('Y-m-d'),
                'kredit' => 0,
                'debit' => $debit,
                'rupiah' => $total_rp,
                'admin' => auth()->user()->name,
            ]);
        }

        return redirect()->route('bahan.stok')->with('sukses', 'Data Berhasil ditambahkan');
    }

    
}
