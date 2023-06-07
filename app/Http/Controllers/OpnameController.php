<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use App\Models\Stok;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SettingHal;

class OpnameController extends Controller
{
    protected $gudang;

    public function __construct()
    {
        $this->gudang = Gudang::where('kategori_id', 1)->get();
    }

    public function index($gudang_id = null)
    {
        $produk = Stok::where([['status', 'opname'],['gudang_id', $gudang_id ?? 1], ['kategori_id', 1]])
                    ->whereBetween('tgl', [$tgl1 ?? date('Y-m-1'), $tgl2 ?? date('Y-m-d')])
                    ->orderBy('no_nota', 'desc')
                    ->groupBy('no_nota')
                    ->get();
        $id_user = auth()->user()->id;
        $data = [
            'title' => 'Opname',
            'gudang' => $this->gudang,
            'stok' => $produk,

            'user' => User::where('posisi_id', 1)->get(),
            'halaman' => 8,
            'create' => SettingHal::btnHal(34, $id_user),
            'print' => SettingHal::btnHal(35, $id_user),
            'detail' => SettingHal::btnHal(36, $id_user),
        ];
        return view('persediaan_barang.opname.index',$data);
    }

    public function add($gudang_id = null)
    {
        $produk = Stok::getProduk(1, $gudang_id, 'Y');
    
        $data = [
            'title' => 'Opname',
            'gudang' => $this->gudang,
            'produk' => $produk,
        ];
        return view('persediaan_barang.opname.opname',$data);
    }

    public function edit(Request $r)
    {
        $no_nota = decrypt($r->no_nota);
        $data = [
            'title' => 'Opname',
            'gudang' => $this->gudang,
            'produk' => Stok::where('no_nota', $no_nota)->get(),
            'no_nota' => $no_nota,
        ];
        return view('persediaan_barang.opname.edit',$data);
    }

    public function update(Request $r)
    {
        for ($i=0; $i < count($r->id_produk); $i++) { 
            $total = $r->buku[$i] - $r->fisik[$i];
            
            $debit = $total < 0 ? $total * -1 : 0;
            $kredit = $total < 0 ? 0 : $total;

            $data = [
                'tgl' => date('Y-m-d'),
                'departemen_id' => '1',
                'status' => 'opname',
                'jenis' => $r->simpan == 'simpan' ? 'selesai' : 'draft',
                'gudang_id' => $r->gudang_id[$i],
                'kategori_id' => 1,
                'jml_sesudahnya' => $r->fisik[$i],
                'selisih' => $r->selisih[$i],
                'debit' => $debit,
                'kredit' => $kredit,
                'ket' => 'Opname',
                'rp_satuan' => '0',
                'admin' => auth()->user()->name,
            ];

            Stok::where([['no_nota', $r->no_nota], ['id_produk', $r->id_produk[$i]]])->update($data);
        }

        return redirect()->route('opname.index')->with('sukses', 'Berhasil opname');
    }

    public function save(Request $r)
    {
        $no_nota = buatNota('tb_stok_produk', 'urutan');
        
        for ($i=0; $i < count($r->id_produk); $i++) { 
            $total = $r->buku[$i] - $r->fisik[$i];
            
            $debit = $total < 0 ? $total * -1 : 0;
            $kredit = $total < 0 ? 0 : $total;

            $data = [
                'id_produk' => $r->id_produk[$i],
                'tgl' => date('Y-m-d'),
                'urutan' => $no_nota,
                'no_nota' => 'OPN-'.$no_nota,
                'departemen_id' => '1',
                'kategori_id' => 1,
                'status' => 'opname',
                'jenis' => $r->simpan == 'simpan' ? 'selesai' : 'draft',
                'gudang_id' => $r->gudang_id[$i],
                'jml_sebelumnya' => $r->buku[$i],
                'jml_sesudahnya' => $r->fisik[$i],
                'selisih' => $r->selisih[$i],
                'debit' => $debit,
                'kredit' => $kredit,
                'ket' => 'Opname',
                'rp_satuan' => '0',
                'admin' => auth()->user()->name,
            ];

            Stok::create($data);
        }

        return redirect()->route('opname.index')->with('sukses', 'Berhasil opname');
    }

    public function delete($no_nota)
    {
        Stok::where('no_nota', $no_nota)->delete();
        return redirect()->route('opname.index')->with('sukses', 'Data Berhasil Dihapus');
    }

    public function detail($no_nota)
    {
        $data = [
            'title' => 'Opname Detail',
            'stok' => Stok::where('no_nota', $no_nota)->get(),
            'detail' => Stok::getStatus($no_nota),
        ];
        return view('persediaan_barang.opname.detail', $data);
    }

    public function cetak(Request $r)
    {
        if(strlen($r->no_nota) > 200 || strlen($r->no_nota) < 200){
            return redirect()->back()->with('error', 'No nota tidak terdaftar !');
        }
        $no_nota = decrypt($r->no_nota);
        
        $data = [
            'title' => 'Opname Cetak',
            'stok' => Stok::getCetak($no_nota),
            'detail' => Stok::getStatus($no_nota),
        ];
        return view('persediaan_barang.opname.cetak', $data);
    }
}
