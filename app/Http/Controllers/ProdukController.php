<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use App\Models\Produk;
use App\Models\Satuan;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    protected
        $id_departemen = 1;

    public function index($gudang_id = null)
    {
        $kd_produk = Produk::latest('kd_produk')->first();
 
        $data = [
            'title' => 'Data Produk',
            'gudang_id' => $gudang_id,
            'produk' => Produk::with('satuan')->when($gudang_id, function ($q, $gudang_id) {
                return $q->where('gudang_id', $gudang_id);
            })->get(),
            'gudang' => Gudang::all(),
            'satuan' => Satuan::all(),
            'kd_produk' => empty($kd_produk) ? 1 : $kd_produk->kd_produk + 1,
        ];
        return view('persediaan_barang.produk.produk', $data);
    }

    public function create(Request $r)
    {
        $route = $r->segment ? 'produk.detail' : 'detail';
        $file = $r->file('img');
        $fileDiterima = ['jpg', 'png', 'jpeg'];
        $cek = in_array($file->getClientOriginalExtension(), $fileDiterima);
        if ($cek) {
            $file->move('upload', $file->getClientOriginalName());

            Produk::create([
                'kd_produk' => $r->kd_produk,
                'nm_produk' => $r->nm_produk,
                'gudang_id' => $r->gudang_id,
                'satuan_id' => $r->satuan_id,
                'departemen_id' => $this->id_departemen,
                'kontrol_stok' => $r->kontrol_stok,
                'img' => $file->getClientOriginalName(),
                'tgl' => date('Y-m-d'),
                'admin' => auth()->user()->name,
            ]);
            return redirect()->route($route, $r->segment ?? '')->with('sukses', 'Berhasil tambah data');
        } else {
            return redirect()->route($route, $r->segment ?? '')->with('error', 'File tidak didukung');
        }
    }

    public function edit_load($id_produk)
    {
        $data = [
            'produk' => Produk::where('id_produk', $id_produk)->first(),
            'gudang' => Gudang::all(),
            'satuan' => Satuan::all(),
        ];
        return view('persediaan_barang.produk.edit', $data);
    }

    public function edit(Request $r)
    {
        Produk::where('id_produk', $r->id_produk)->update([
            'nm_produk' => $r->nm_produk,
            'kd_produk' => $r->kd_produk,
            'admin' => auth()->user()->name,
        ]);

        return redirect()->route('produk')->with('sukses', 'Berhasil update data');
    }
}
