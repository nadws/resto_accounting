<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        FROM stok_bahan as a 
        ");

        if (empty($invo->urutan)) {
            $invoice = '1001';
        } else {
            $invoice = $invo->urutan + 1;
        }

        for ($i = 0; $i < count($r->id_bahan); $i++) {
            $stok_program = str()->remove(',', $r->stok_program[$i]);
            $stok_aktual = str()->remove(',', $r->stok_aktual[$i]);
            $total = $stok_program - $stok_aktual;
            if($total == 0) {
                continue;
            }
            if($total < 0) {
                DB::table('stok_bahan')->insert([
                    'id_bahan' => $r->id_bahan[$i],
                    'invoice' => "BHN-$invoice",
                    'urutan' => $invoice,
                    'tgl' => date('Y-m-d'),
                    'debit' => $total * -1,
                    'kredit' => 0,
                    'admin' => auth()->user()->name,
                ]);
            } else {
                DB::table('stok_bahan')->insert([
                    'id_bahan' => $r->id_bahan[$i],
                    'invoice' => "BHN-$invoice",
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
}
