<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ProdukController extends Controller
{
    protected
        $id_departemen = 1;

    public function index($gudang_id = null)
    {
        $kd_produk = Produk::latest('kd_produk')->first();

        $data = [
            'title' => 'Data Atk dan Peralatan',
            'produk' => Stok::getProduk(1,$gudang_id, 'Y'),
            'gudang' => Gudang::where('kategori_id',1)->get(),
            'satuan' => Satuan::all(),
            'gudang_id' => $gudang_id,
            'kd_produk' => empty($kd_produk) ? 1 : $kd_produk->kd_produk + 1,
        ];
        return view('persediaan_barang.produk.produk', $data);
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
                'gudang_id' => $r->gudang_id,
                'kategori_id' => 1,
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
        $file = $r->file('img');
        $fileDiterima = ['jpg', 'png', 'jpeg'];
        if ($file !== null) {
            $cek = in_array($file->getClientOriginalExtension(), $fileDiterima);
            if ($cek) {
                $path = public_path('upload/' . $r->imgLama);
                if (file_exists($path)) {
                    unlink($path);
                }
                $fileName = "P-$r->kd_produk" . $file->getClientOriginalName();
                $file->move('upload', $fileName);
            } else {
                return redirect()->route('produk.index')->with('error', 'File tidak didukung');
            }
        }

        Produk::where('id_produk', $r->id_produk)->update([
            'nm_produk' => $r->nm_produk,
            'gudang_id' => $r->gudang_id,
            'satuan_id' => $r->satuan_id,
            'kontrol_stok' => $r->kontrol_stok,
            'img' => $fileName ?? $r->imgLama,
            'tgl' => date('Y-m-d'),
            'admin' => auth()->user()->name,
        ]);

        return redirect()->route('produk.index')->with('sukses', 'Berhasil update data');
    }

    public function delete($id_produk)
    {
        $produk = Produk::findOrFail($id_produk);

        $path = public_path('upload/' . $produk->img);
        if (file_exists($path)) {
            unlink($path);
        }

        $produk->delete();

        return redirect()->route('produk.index')->with('sukses', 'Berhasil hapus data');
    }
}
