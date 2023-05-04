<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use App\Models\Produk;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokMasukController extends Controller
{
    protected $produk;

    public function __construct()
    {
        $this->produk = Produk::with('satuan')->where([['kontrol_stok', 'Y'],['kategori_id', 1]])->get();
    }

    public function index($gudang_id = null)
    {
        $data = [
            'title' => 'Stok Masuk',
            'produk' => $this->produk,
            'gudang' => Gudang::where('kategori_id', 1)->get(),
            'stok' => Stok::select('no_nota', 'tgl', 'jenis', DB::raw('SUM(debit) as debit'))
                ->when($gudang_id, function ($q, $gudang_id) {
                    return $q->where('gudang_id', $gudang_id);
                })
                ->where('status', '!=', 'opname')
                ->groupBy('no_nota')
                ->orderBy('id_stok_produk', 'DESC')
                ->get()
        ];
        return view('persediaan_barang.stok_masuk.stok_masuk', $data);
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
        $no_nota = "INV" . strtoupper(str()->random(4));

        return redirect()->route('stok_masuk.add', ['no_nota' => $no_nota])->with('sukses', 'Data Berhasil Add');
    }

    public function load_menu(Request $r)
    {
        $no_nota = buatNota('tb_stok_produk', 'urutan');
        $data = [
            'no_nota' => $no_nota,
            'detail' => Stok::getStatus($r->no_nota),
            'stok' => Stok::getStokMasuk($r->no_nota),
            'produk' => $this->produk,
            'gudang' => Gudang::where('kategori_id', 1)->get(),
        ];
        return view('persediaan_barang.stok_masuk.load_menu', $data);
    }

    public function get_stok_sebelumnya(Request $r)
    {
        return Stok::getStokMasuk($r->id_produk);
    }

    public function tbh_baris(Request $r)
    {
        $data = [
            'title' => 'Tambah Barang',
            'count' => $r->count,
            'produk' => $this->produk
        ];
        return view('persediaan_barang.stok_masuk.tbh_baris', $data);
    }

    public function store(Request $r)
    {
        if(empty($r->id_produk)) {
            return redirect()->route('stok_masuk.index')->with('error', 'Data Tidak ada');
        }
        for ($i = 0; $i < count($r->id_produk); $i++) {
            $jml_sebelumnya = $r->jml_sebelumnya[$i];
            $debit = $r->debit[$i];

            $data = [
                'id_produk' => $r->id_produk[$i],
                'tgl' => $r->tgl,
                'urutan' => $r->urutan,
                'no_nota' => $r->no_nota,
                'departemen_id' => '1',
                'kategori_id' => '1',
                'status' => 'masuk',
                'jenis' => $r->simpan == 'simpan' ? 'selesai' : 'draft',
                'gudang_id' => $r->gudang_id,
                'jml_sebelumnya' => $jml_sebelumnya,
                'jml_sesudahnya' => $jml_sebelumnya + $debit,
                'debit' => $debit,
                'ket' => $r->ket,
                'rp_satuan' => $r->rp_satuan[$i],
                'admin' => auth()->user()->name,
            ];

            if (!empty($r->jenis)) {
                Stok::where([['urutan', $r->urutan], ['id_produk', $r->id_produk[$i]]])->update($data);
            } else {
                Stok::create($data);
            }
        }

        return redirect()->route('stok_masuk.index')->with('sukses', 'Data Berhasil Ditambahkan');
    }

    public function edit($no_nota)
    {
        $data = [
            'title' => 'Stok Masuk Edit',
            'stok' => Stok::where('no_nota', $no_nota)->get(),
            'detail' => Stok::getStatus($no_nota),
        ];
        return view('persediaan_barang.stok_masuk.detail', $data);
    }

    public function delete($no_nota)
    {
        Stok::where('no_nota', $no_nota)->delete();
        return redirect()->route('stok_masuk.index')->with('sukses', 'Data Berhasil Dihapus');
    }

    public function cetak(Request $r)
    {
        if(strlen($r->no_nota) > 200 || strlen($r->no_nota) < 200){
            return redirect()->back()->with('error', 'No nota tidak terdaftar !');
        }
        $no_nota = decrypt($r->no_nota);
        
        $data = [
            'title' => 'Stok Masuk Cetak',
            'stok' => Stok::getCetak($no_nota),
            'detail' => Stok::getStatus($no_nota),
        ];
        return view('persediaan_barang.stok_masuk.cetak', $data);
    }
}
