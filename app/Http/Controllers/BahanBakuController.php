<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\Stok;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use SettingHal;

class BahanBakuController extends Controller
{
    protected
        $id_departemen = 1,
        $gudang,
        $produk;

    public function __construct()
    {
        $this->produk = Produk::with('satuan')->where([['kontrol_stok', 'Y'], ['kategori_id', 2]])->get();
        $this->gudang = Gudang::where('kategori_id', 2)->get();
    }
    public function index($gudang_id = null)
    {
        $id_user = auth()->user()->id;
        $kd_produk = Produk::latest('kd_produk')->first();
        $data = [
            'title' => 'Bahan Baku',
            'produk' => Stok::getProduk(2, $gudang_id, 'Y'),
            'gudang' => $this->gudang,
            'satuan' => Satuan::all(),
            'gudang_id' => $gudang_id,
            'kd_produk' => empty($kd_produk) ? 1 : $kd_produk->kd_produk + 1,

            'user' => User::where('posisi_id', 1)->get(),
            'halaman' => 12,
            'create' => SettingHal::btnHal(49, $id_user),
            'edit' => SettingHal::btnHal(50, $id_user),
            'delete' => SettingHal::btnHal(51, $id_user),
            'detail' => SettingHal::btnHal(52, $id_user),
        ];
        return view('persediaan_barang.bahan_baku.index', $data);
    }

    public function create(Request $r)
    {
        $route = $r->url;
        $file = $r->file('img');
        if (!empty($file)) {
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
        } else {
            Produk::create([
                'kd_produk' => $r->kd_produk,
                'nm_produk' => $r->nm_produk,
                'kategori_id' => 2,
                'gudang_id' => $r->gudang_id,
                'satuan_id' => $r->satuan_id,
                'departemen_id' => $this->id_departemen,
                'kontrol_stok' => $r->kontrol_stok,
                'tgl' => date('Y-m-d'),
                'admin' => auth()->user()->name,
            ]);
            return redirect()->route($route, $r->segment ?? '')->with('sukses', 'Berhasil tambah data');
        }
    }

    public function delete(Request $r)
    {
        $produk = Produk::findOrFail($r->id_produk);
        $produk->delete();

        if (!empty($produk->img)) {
            $path = public_path('upload/' . $produk->img);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        return redirect()->route('bahan_baku.index')->with('sukses', 'Berhasil hapus data');
    }

    public function stokMasuk($gudang_id = null)
    {
        $stok = Stok::select('no_nota', 'tgl', 'jenis', DB::raw('SUM(debit) as debit'))
            ->when($gudang_id, function ($q, $gudang_id) {
                return $q->where('gudang_id', $gudang_id);
            })
            ->where([['status', '!=', 'opname'], ['kategori_id', '2']])
            ->groupBy('no_nota')
            ->orderBy('id_stok_produk', 'DESC')
            ->get();
        $id_user = auth()->user()->id;
        $data = [
            'title' => 'Bahan Baku Stok Masuk',
            'gudang' => $this->gudang,
            'stok' => $stok,

            'user' => User::where('posisi_id', 1)->get(),
            'halaman' => 13,
            'create' => SettingHal::btnHal(53, $id_user),
            'edit' => SettingHal::btnHal(54, $id_user),
            'delete' => SettingHal::btnHal(55, $id_user),
            'detail' => SettingHal::btnHal(56, $id_user),
            'print' => SettingHal::btnHal(57, $id_user),
        ];
        return view('persediaan_barang.bahan_baku.stok_masuk.stok_masuk', $data);
    }

    public function add(Request $r)
    {
        $data = [
            'title' => 'Add Stok Bahan Baku',
            'allProduk' => $this->produk,
        ];
        return view('persediaan_barang.bahan_baku.stok_masuk.add', $data);
    }

    public function edit_load($id_produk)
    {
        $data = [
            'produk' => Produk::where('id_produk', $id_produk)->first(),
            'gudang' => $this->gudang,
            'satuan' => Satuan::all(),
        ];
        return view('persediaan_barang.bahan_baku.edit', $data);
    }

    public function load_menu(Request $r)
    {
        $no_nota = buatNota('tb_stok_produk', 'urutan');
        $data = [
            'no_nota' => $no_nota,
            'detail' => Stok::getStatus($r->no_nota),
            'stok' => Stok::getStokMasuk($r->no_nota),
            'produk' => $this->produk,
            'gudang' => $this->gudang,
        ];
        return view('persediaan_barang.bahan_baku.stok_masuk.load_menu', $data);
    }

    public function tbh_baris(Request $r)
    {
        $data = [
            'title' => 'Tambah Barang',
            'count' => $r->count,
            'produk' => $this->produk
        ];
        return view('persediaan_barang.bahan_baku.stok_masuk.tbh_baris', $data);
    }

    public function store(Request $r)
    {
        if (empty($r->id_produk)) {
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
                'kategori_id' => '2',
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

        return redirect()->route('bahan_baku.stok_masuk')->with('sukses', 'Data Berhasil Ditambahkan');
    }

    public function opname($gudang_id = null)
    {
        $produk = Stok::where([['status', 'opname'], ['gudang_id', $gudang_id ?? 2], ['kategori_id', 2]])
            ->whereBetween('tgl', [$tgl1 ?? date('Y-m-1'), $tgl2 ?? date('Y-m-d')])
            ->orderBy('no_nota', 'desc')
            ->groupBy('no_nota')
            ->get();

        $data = [
            'title' => 'Opname Bahan Baku',
            'gudang' => $this->gudang,
            'stok' => $produk,
        ];
        return view('persediaan_barang.bahan_baku.opname.opname', $data);
    }

    public function opname_add($gudang_id = null)
    {
        $produk = Stok::getProduk(2, $gudang_id, 'Y');

        $data = [
            'title' => 'Opname',
            'gudang' => $this->gudang,
            'produk' => $produk,
        ];
        return view('persediaan_barang.bahan_baku.opname.opname_add', $data);
    }

    public function opname_store(Request $r)
    {
        $no_nota = buatNota('tb_stok_produk', 'urutan');

        for ($i = 0; $i < count($r->id_produk); $i++) {
            $total = $r->buku[$i] - $r->fisik[$i];

            $debit = $total < 0 ? $total * -1 : 0;
            $kredit = $total < 0 ? 0 : $total;

            $data = [
                'id_produk' => $r->id_produk[$i],
                'tgl' => date('Y-m-d'),
                'urutan' => $no_nota,
                'no_nota' => 'OPNBAKU-' . $no_nota,
                'departemen_id' => '1',
                'kategori_id' => 2,
                'status' => 'opname',
                'jenis' => $r->simpan == 'simpan' ? 'selesai' : 'draft',
                'gudang_id' => $r->gudang_id[$i],
                'jml_sebelumnya' => $r->buku[$i],
                'jml_sesudahnya' => $r->fisik[$i],
                'selisih' => $r->selisih[$i],
                'debit' => $debit,
                'kredit' => $kredit,
                'ket' => 'Opname Bahan Baku',
                'rp_satuan' => '0',
                'admin' => auth()->user()->name,
            ];

            Stok::create($data);
        }

        return redirect()->route('bahan_baku.opname')->with('sukses', 'Berhasil opname');
    }

    public function opname_detail($no_nota)
    {
        $data = [
            'title' => 'Opname Detail',
            'stok' => Stok::where('no_nota', $no_nota)->get(),
            'detail' => Stok::getStatus($no_nota),
        ];
        return view('persediaan_barang.bahan_baku.opname.opname_detail', $data);
    }

    public function opname_cetak(Request $r)
    {
        if (strlen($r->no_nota) > 228 || strlen($r->no_nota) < 228) {
            return redirect()->back()->with('error', 'No nota tidak terdaftar !');
        }
        $no_nota = decrypt($r->no_nota);

        $data = [
            'title' => 'Opname Cetak',
            'stok' => Stok::getCetak($no_nota),
            'detail' => Stok::getStatus($no_nota),
        ];
        return view('persediaan_barang.bahan_baku.opname.cetak', $data);
    }
}
