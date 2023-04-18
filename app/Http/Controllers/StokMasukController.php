<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokMasukController extends Controller
{
    protected $produk;

    public function __construct()
    {
        $this->produk = Produk::with('satuan')->where('kontrol_stok', 'Y')->get();
    }

    public function index()
    {
        $data = [
            'title' => 'Stok Masuk',
            'produk' => $this->produk,
            'stok' => Stok::select('no_nota', 'tgl', 'jenis', DB::raw('SUM(debit) as debit'))->groupBy('no_nota')->orderBy('id_stok_produk', 'DESC')->get()
        ];
        return view('persediaan_barang.stok_masuk.stok_masuk',$data);
    }

    public function add(Request $r)
    {
        
        $data = [
            'title' => 'Add Stok Produk',
            'allProduk' => $this->produk,
        ];
        return view('persediaan_barang.stok_masuk.add', $data);
    }

    public function create(Request $r)
    {
        
        $no_nota = "INV". strtoupper(str()->random(4));
        $data = [];

        for ($i=0; $i < count($r->id_produk); $i++) { 
            $data[] = [
                'id_produk' => $r->id_produk[$i],
                'tgl' => date('Y-m-d'),
                'no_nota' => $no_nota,
                'departemen_id' => '1',
                'status' => 'masuk',
                'jenis' => 'draft',
                'gudang_id' => '1',
                'admin' => auth()->user()->name,
            ];
        }

        Stok::insert($data);

        return redirect()->route('stok_masuk.add', ['no_nota' => $no_nota])->with('sukses', 'Data Berhasil Add');
    }

    public function load_menu(Request $r)
    {   
        
        $data = [
            'no_nota' => $r->no_nota,
            'produk' => DB::select("SELECT a.id_produk,a.jml_sebelumnya,a.jml_sesudahnya,b.nm_produk,d.nm_satuan, c.debit, c.kredit, a.id_stok_produk FROM `tb_stok_produk` as a
            LEFT JOIN tb_produk as b ON a.id_produk = b.id_produk
            LEFT JOIN tb_satuan as d ON b.satuan_id = d.id_satuan
            LEFT JOIN (
                SELECT b.id_produk, sum(b.debit) as debit, sum(b.kredit) as kredit
            FROM tb_stok_produk as b WHERE b.jenis = 'selesai' GROUP BY b.id_produk
            ) as c ON c.id_produk = a.id_produk
            WHERE a.no_nota = '$r->no_nota'"),
            'allProduk' => $this->produk
        ];
        return view('persediaan_barang.stok_masuk.load_menu',$data);
    }

    public function create_add(Request $r)
    {
        DB::transaction(function () use($r) {
            
            Stok::where('no_nota', $r->no_nota_add)->delete();

            for ($i=0; $i < count($r->id_produk); $i++) { 
                Stok::create([
                    'id_produk' => $r->id_produk[$i],
                    'tgl' => date('Y-m-d'),
                    'no_nota' => $r->no_nota_add,
                    'departemen_id' => '1',
                    'status' => 'masuk',
                    'jenis' => 'draft',
                    'gudang_id' => '1',
                    'admin' => auth()->user()->name,
                ]);
            }
            
        });
    }

    public function store(Request $r)
    {
        for ($i=0; $i < count($r->id_produk); $i++) { 
            $id_produk = $r->id_produk[$i];
            $jml_sebelumnya = $r->jml_sebelumnya[$i];
            $debit = $r->debit[$i];

            Stok::where([['no_nota', $r->no_nota], ['id_produk', $id_produk]])->update([
                'tgl' => $r->tgl,
                'departemen_id' => '1',
                'status' => 'masuk',
                'jenis' => $r->simpan == 'simpan' ? 'selesai' : 'draft',
                'gudang_id' => '1',
                'jml_sebelumnya' => $jml_sebelumnya,
                'jml_sesudahnya' => $jml_sebelumnya + $debit,
                'debit' => $debit,
                'admin' => auth()->user()->name,
            ]);
        }

        return redirect()->route('stok_masuk.index')->with('sukses', 'Data Berhasil Ditambahkan');
    }
    
}
