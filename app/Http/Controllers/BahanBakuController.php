<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class BahanBakuController extends Controller
{
    protected
        $id_departemen = 1,
        $gudang,
        $produk;

    public function __construct()
    {
        $this->produk = Produk::with('satuan')->where([['kontrol_stok', 'Y'],['kategori_id', 2]])->get();
        $this->gudang = Gudang::where('kategori_id', 2)->get();
    }
    public function index($gudang_id = null)
    {
        $kd_produk = Produk::latest('kd_produk')->first();
        $data = [
            'title' => 'Bahan Baku',
            'produk' => Stok::getProduk(2, $gudang_id, 'Y'),
            'gudang' => $this->gudang,
            'satuan' => Satuan::all(),
            'gudang_id' => $gudang_id,
            'kd_produk' => empty($kd_produk) ? 1 : $kd_produk->kd_produk + 1,
        ];
        return view('persediaan_barang.bahan_baku.index', $data);
    }

    public function create(Request $r)
    {
        $route = $r->url;
        $file = $r->file('img');
        $fileDiterima = ['jpg', 'png', 'jpeg'];
        $cek = in_array($file->getClientOriginalExtension(), $fileDiterima);
        if ($cek) {
            $maxFileSize = 1024 * 1024; // 1MB
            if ($file instanceof UploadedFile && $file->getSize() > $maxFileSize) {
                return redirect()->route($route, $r->segment ?? '')->with('error', 'File lebih dari 1MB');
            }
            $fileName = "P-$r->kd_produk" . $file->getClientOriginalName();
            $path = $file->move('upload', $fileName);

            Produk::create([
                'kd_produk' => $r->kd_produk,
                'nm_produk' => $r->nm_produk,
                'kategori_id' => 2,
                'gudang_id' => $r->gudang_id,
                'satuan_id' => $r->satuan_id,
                'departemen_id' => $this->id_departemen,
                'kontrol_stok' => $r->kontrol_stok,
                'img' => $fileName,
                'tgl' => date('Y-m-d'),
                'admin' => auth()->user()->name,
            ]);
            return redirect()->route($route, $r->segment ?? '')->with('sukses', 'Berhasil tambah data');
        } else {
            return redirect()->route($route, $r->segment ?? '')->with('error', 'File tidak didukung');
        }
    }

    public function stokMasuk($gudang_id = null)
    {
        $data = [
            'title' => 'Bahan Baku Stok Masuk',
            'gudang' => $this->gudang
        ];
        return view('persediaan_barang.bahan_baku.stok_masuk', $data);
    }

    public function add(Request $r)
    {
        $data = [
            'title' => 'Add Stok Bahan Baku',
            'allProduk' => $this->produk,
        ];
        return view('persediaan_barang.stok_masuk.add', $data);
    }
}
