<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembelianBahanBakuController extends Controller
{
    public function index(Request $r)
    {
        $pembelian = DB::select("SELECT a.tgl, a.no_nota,b.nm_suplier, a.suplier_akhir, a.total_harga, a.lunas, c.kredit, c.debit
        FROM invoice_bk as a 
        left join tb_suplier as b on b.id_suplier = a.id_suplier
        left join (
        SELECT c.no_nota , sum(c.debit) as debit, sum(c.kredit) as kredit  FROM bayar_bk as c
        group by c.no_nota
        ) as c on c.no_nota = a.no_nota
        order by a.no_nota DESC");
        $data =  [
            'title' => 'Pembelian Bahan Baku',
            'pembelian' => $pembelian

        ];
        return view('pembelian_bk.index', $data);
    }

    public function add(Request $r)
    {
        $max = DB::table('pembelian')->latest('urutan_nota')->first();

        if (empty($max)) {
            $nota_t = '1000';
        } else {
            $nota_t = $max->urutan_nota + 1;
        }
        $data =  [
            'title' => 'Tambah Bahan Baku',
            'suplier' => DB::table('tb_suplier')->get(),
            'nota' => $nota_t,
            'produk' => DB::table('tb_produk')->get()

        ];
        return view('pembelian_bk.add', $data);
    }

    public function get_satuan_produk(Request $r)
    {
        $id_produk = $r->id_produk;
        $produk = DB::table('tb_produk')->where('id_produk', $id_produk)->first();
        $satuan = DB::table('tb_satuan')->where('id_satuan', $produk->satuan_id)->get();

        foreach ($satuan as $k) {
            echo "<option value='" . $k->id_satuan  . "'>" . $k->nm_satuan . "</option>";
        }
    }

    public function tambah_baris_bk(Request $r)
    {
        $data =  [
            'produk' => DB::table('tb_produk')->get(),
            'count' => $r->count

        ];
        return view('pembelian_bk.tambah_baris', $data);
    }

    public function save_pembelian_bk(Request $r)
    {
        $tgl = $r->tgl;
        $suplier_awal = $r->suplier_awal;
        $suplier_akhir = $r->suplier_akhir;
        $id_produk = $r->id_produk;
        $qty = $r->qty;
        $id_satuan = $r->id_satuan;
        $h_satuan = $r->h_satuan;
        $total_harga = $r->total_harga;

        $max = DB::table('pembelian')->latest('urutan_nota')->first();

        if (empty($max)) {
            $nota_t = '1000';
        } else {
            $nota_t = $max->urutan_nota + 1;
        }


        for ($x = 0; $x < count($id_produk); $x++) {
            $data = [
                'tgl' => $tgl,
                'no_nota' => "BI-" . $nota_t,
                'id_produk' => $id_produk[$x],
                'suplier_awal' => $suplier_awal,
                'suplier_akhir' => $suplier_akhir,
                'qty' => $qty[$x],
                'h_satuan' => $h_satuan[$x],
                'urutan_nota' => $nota_t,
                'admin' => Auth::user()->name,
            ];

            DB::table('pembelian')->insert($data);
        }


        $button = $r->button;
        if ($button == 'simpan') {
            $data = [
                'id_suplier' => $suplier_awal,
                'tgl' => $tgl,
                'no_nota' => "BI-" . $nota_t,
                'suplier_akhir' => $suplier_akhir,
                'total_harga' => $total_harga,
                'tgl_bayar' => '0000-00-00',
                'lunas' => 'T',
                'admin' => Auth::user()->name,
            ];
            DB::table('invoice_bk')->insert($data);
        } else {
            $data = [
                'id_suplier' => $suplier_awal,
                'tgl' => $tgl,
                'no_nota' => "BI-" . $nota_t,
                'suplier_akhir' => $suplier_akhir,
                'total_harga' => $total_harga,
                'tgl_bayar' => '0000-00-00',
                'lunas' => 'D',
                'admin' => Auth::user()->name,
            ];
            DB::table('invoice_bk')->insert($data);
        }

        $data_tambahan = [
            'no_nota' => "BI-" . $nota_t,
            'debit' => $r->debit_tambahan,
            'kredit' => 0,
            'id_akun' => '35',
            'tgl' => $tgl,
            'admin' => Auth::user()->name,
            'ket' => $r->ket_lainnya
        ];
        DB::table('bayar_bk')->insert($data_tambahan);


        return redirect()->route('pembelian_bk')->with('sukses', 'Data berhasil ditambahkan');
    }

    public function edit_save(Request $r)
    {
        $tgl = $r->tgl;
        $suplier_awal = $r->suplier_awal;
        $suplier_akhir = $r->suplier_akhir;
        $id_produk = $r->id_produk;
        $qty = $r->qty;
        $id_satuan = $r->id_satuan;
        $h_satuan = $r->h_satuan;
        $total_harga = $r->total_harga;

        $nota = $r->no_nota;
        $urutan_nota = $r->urutan_nota;

        DB::table('pembelian')->where('no_nota', $nota)->delete();
        DB::table('invoice_bk')->where('no_nota', $nota)->delete();

        for ($x = 0; $x < count($id_produk); $x++) {
            $data = [
                'tgl' => $tgl,
                'no_nota' => $nota,
                'id_produk' => $id_produk[$x],
                'suplier_awal' => $suplier_awal,
                'suplier_akhir' => $suplier_akhir,
                'qty' => $qty[$x],
                'h_satuan' => $h_satuan[$x],
                'urutan_nota' => $urutan_nota,
                'admin' => Auth::user()->name,
            ];

            DB::table('pembelian')->insert($data);
        }


        $button = $r->button;
        if ($button == 'simpan') {
            $data = [
                'id_suplier' => $suplier_awal,
                'tgl' => $tgl,
                'no_nota' => $nota,
                'suplier_akhir' => $suplier_akhir,
                'total_harga' => $total_harga,
                'tgl_bayar' => '0000-00-00',
                'lunas' => 'T',
                'admin' => Auth::user()->name,
            ];
            DB::table('invoice_bk')->insert($data);
        } else {
            $data = [
                'id_suplier' => $suplier_awal,
                'tgl' => $tgl,
                'no_nota' => $nota,
                'suplier_akhir' => $suplier_akhir,
                'total_harga' => $total_harga,
                'tgl_bayar' => '0000-00-00',
                'lunas' => 'D',
                'admin' => Auth::user()->name,
            ];
            DB::table('invoice_bk')->insert($data);
        }


        return redirect()->route('pembelian_bk')->with('sukses', 'Data berhasil ditambahkan');
    }


    public function print(Request $r)
    {
        $pembelian = DB::selectOne("SELECT a.tgl, a.no_nota,b.nm_suplier, a.suplier_akhir, a.total_harga, a.lunas
        FROM invoice_bk as a 
        left join tb_suplier as b on b.id_suplier = a.id_suplier
        where a.no_nota = '$r->no_nota'
        ");

        $produk = DB::select("SELECT * FROM pembelian as a 
        left join tb_produk as b on b.id_produk = a.id_produk 
        left join tb_satuan as c on c.id_satuan = b.satuan_id
        WHERE a.no_nota ='$r->no_nota'");


        $data =  [
            'title' => 'Pembelian Bahan Baku',
            'pembelian' => $pembelian,
            'produk' => $produk

        ];
        return view('pembelian_bk.print', $data);
    }

    public function delete_bk(Request $r)
    {
        $nota = $r->no_nota;
        DB::table('invoice_bk')->where('no_nota', $nota)->delete();
        return redirect()->route('pembelian_bk')->with('sukses', 'Data berhasil ditambahkan');
    }

    public function edit_pembelian_bk(Request $r)
    {
        $nota = $r->nota;

        $invoice = DB::table('invoice_bk')->where('no_nota', $nota)->first();
        $gram = DB::table('pembelian')->where('no_nota', $nota)->get();
        $data =  [
            'title' => 'Edit Bahan Baku',
            'suplier' => DB::table('tb_suplier')->get(),
            'produk' => DB::table('tb_produk')->get(),
            'nota' => $nota,
            'invoice' => $invoice,
            'gram' => $gram

        ];
        return view('pembelian_bk.edit', $data);
    }
}
