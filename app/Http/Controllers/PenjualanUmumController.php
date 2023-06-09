<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Jurnal;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanUmumController extends Controller
{
    protected $produk;
    public $akunPenjualan = '34';

    public function __construct()
    {
        $this->produk = Produk::with('satuan')->where([['kontrol_stok', 'Y'], ['kategori_id', 3]])->get();
    }

    public function index()
    {
        $data = [
            'title' => 'Penjualan Umum'
        ];
        return view('penjualan2.penjualan', $data);
    }

    public function add()
    {
        $nota = buatNota('penjualan_agl', 'urutan');
        $data = [
            'title' => 'Tambah Penjualan Umum',
            'customer' => DB::table('customer')->get(),
            'produk' => $this->produk,
            'akun' => Akun::all(),
            'no_nota' => $nota + 1
        ];
        return view('penjualan2.add', $data);
    }

    public function tbh_add(Request $r)
    {
        $data = [
            'count' => $r->count,
            'produk' => $this->produk,
        ];
        return view('penjualan2.tbh_add', $data);
    }

    public function tbh_pembayaran(Request $r)
    {
        $data = [
            'count' => $r->count,
            'akun' => Akun::all()
        ];
        return view('penjualan2.tbh_pembayaran', $data);
    }

    public function store(Request $r)
    {
        $ttlDebit = 0;
        for ($i = 0; $i < count($r->akun_pembayaran); $i++) {
            $ttlDebit += $r->debit[$i] ?? 0 - $r->kredit[$i] ?? 0;

            $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '6')->first();

            $no_nota = empty($max) ? '1000' : $max->nomor_nota + 1;
            DB::table('notas')->insert(['nomor_nota' => $no_nota, 'id_buku' => '6']);

            $id_akun = $r->akun_pembayaran[$i];

            $max_akun = DB::table('jurnal')->latest('urutan')->where('id_akun', $id_akun)->first();
            $akun = DB::table('akun')->where('id_akun', $id_akun)->first();
            $urutan = empty($max_akun) ? '1001' : ($max_akun->urutan == 0 ? '1001' : $max_akun->urutan + 1);

            Jurnal::create([
                'tgl' => $r->tgl,
                'id_akun' => $id_akun,
                'id_buku' => 6,
                'no_nota' => 'PNJL-' . $no_nota,
                'ket' => 'PAGL-' . $r->no_nota . '-' . $r->nota_manual,
                'debit' => $r->debit[$i] ?? 0,
                'kredit' => $r->kredit[$i] ?? 0,
                'no_urut' => $akun->inisial . '-' . $urutan,
                'urutan' => $urutan,
                'admin' => auth()->user()->name,
            ]);
        }

        $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '6')->first();

        $no_nota = empty($max) ? '1000' : $max->nomor_nota + 1;
        DB::table('notas')->insert(['nomor_nota' => $no_nota, 'id_buku' => '6']);

        $max_akun2 = DB::table('jurnal')->latest('urutan')->where('id_akun', $this->akunPenjualan)->first();
        $akun2 = DB::table('akun')->where('id_akun', $this->akunPenjualan)->first();
        $urutan2 = empty($max_akun2) ? '1001' : ($max_akun2->urutan == 0 ? '1001' : $max_akun2->urutan + 1);

        $dataK = [
            'tgl' => $r->tgl,
            'no_nota' => 'PNJL-' . $no_nota,
            'id_akun' => $this->akunPenjualan,
            'id_buku' => '6',
            'ket' => 'Penjualan-' . $r->no_nota,
            'no_urut' => $akun2->inisial . '-' . $urutan2,
            'urutan' => $urutan2,
            'kredit' => $ttlDebit,
            'debit' => 0,
            'admin' => auth()->user()->name,
        ];
        $penjualan = Jurnal::create($dataK);

        for ($i=0; $i < count($r->id_produk); $i++) { 
            DB::table('penjualan_agl')->insert([
                'urutan' => $r->no_nota,
                'nota_manual' => $r->nota_manual,
                'tgl' => $r->tgl,
                'kode' => 'PAGL',
                'id_customer' => $r->id_customer,
                'driver' => $r->driver,
                'id_produk' => $r->id_produk[$i],
                'qty' => $r->qty[$i],
                'rp_satuan' => $r->rp_satuan[$i],
                'total_rp' => $r->total_rp[$i],
                'id_jurnal' => $penjualan->id,
                'admin' => auth()->user()->name
            ]); 
        }

        return redirect()->route('penjualan2.index')->with('sukses', 'Data Berhasil Ditambahkan');
    }
}
