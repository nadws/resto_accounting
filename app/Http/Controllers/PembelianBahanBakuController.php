<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembelianBahanBakuController extends Controller
{
    public function index(Request $r)
    {
        $pembelian = DB::select("SELECT a.id_invoice_bk, a.tgl, a.no_nota,b.nm_suplier, a.suplier_akhir, a.total_harga, a.lunas, c.kredit, c.debit, a.approve, d.id_invoice
        FROM invoice_bk as a 
        left join tb_suplier as b on b.id_suplier = a.id_suplier
        left join (
        SELECT c.no_nota , sum(c.debit) as debit, sum(c.kredit) as kredit  FROM bayar_bk as c
        group by c.no_nota
        ) as c on c.no_nota = a.no_nota
        left join grading as d on d.id_invoice = a.id_invoice_bk
        order by a.no_nota DESC");
        $data =  [
            'title' => 'Pembelian Bahan Baku',
            'pembelian' => $pembelian

        ];
        return view('pembelian_bk.index', $data);
    }

    public function add(Request $r)
    {
        $b = date('m');
        $max = DB::table('pembelian')->whereMonth('tgl', $b)->latest('urutan_nota')->first();;

        $year = date("Y");
        $year = DB::table('tahun')->where('tahun', $year)->first();
        if (empty($max)) {
            $nota_t = '1';
        } else {
            $nota_t = $max->urutan_nota + 1;
        }
        $date = date('m');
        $bulan = DB::table('bulan')->where('bulan', $date)->first();
        $sub_po = "BI$year->kode" . "$bulan->kode" . str_pad($nota_t, 3, '0', STR_PAD_LEFT);
        $data =  [
            'title' => 'Tambah Bahan Baku',
            'suplier' => DB::table('tb_suplier')->get(),
            'nota' => $nota_t,
            'produk' => DB::table('tb_produk')->get(),
            'bulan' => $bulan,
            'akun' => DB::table('akun')->get(),
            'sub_po' => $sub_po

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

        $b = date('m');
        $max = DB::table('pembelian')->whereMonth('tgl', $b)->latest('urutan_nota')->first();;

        $year = date("Y");
        $year = DB::table('tahun')->where('tahun', $year)->first();
        if (empty($max)) {
            $nota_t = '1';
        } else {
            $nota_t = $max->urutan_nota + 1;
        }
        $date = date('m');
        $bulan = DB::table('bulan')->where('bulan', $date)->first();
        $sub_po = "BI$year->kode" . "$bulan->kode" . str_pad($nota_t, 3, '0', STR_PAD_LEFT);

        for ($x = 0; $x < count($id_produk); $x++) {
            $data = [
                'tgl' => $tgl,
                'no_nota' => $sub_po,
                'id_produk' => $id_produk[$x],
                // 'suplier_awal' => $suplier_awal,
                // 'suplier_akhir' => $suplier_akhir,
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
                'no_nota' => $sub_po,
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
                'no_nota' => $sub_po,
                'suplier_akhir' => $suplier_akhir,
                'total_harga' => $total_harga,
                'tgl_bayar' => '0000-00-00',
                'lunas' => 'D',
                'admin' => Auth::user()->name,
            ];
            DB::table('invoice_bk')->insert($data);
        }

        if (empty($r->debit_tambahan) || $r->debit_tambahan == '0') {
            # code...
        } else {
            $data_tambahan = [
                'no_nota' => $sub_po,
                'debit' => $r->debit_tambahan,
                'kredit' => 0,
                'id_akun' => $r->id_akun_lainnya,
                'tgl' => $tgl,
                'admin' => Auth::user()->name,
                'ket' => $r->ket_lainnya
            ];
            DB::table('bayar_bk')->insert($data_tambahan);
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

        $bayar = DB::selectOne("SELECT a.tgl, c.nm_suplier, b.suplier_akhir, a.kredit, d.nm_akun, a.ket, a.debit
        FROM bayar_bk as a
        left join invoice_bk as b on b.no_nota = a.no_nota
        left join tb_suplier as c on c.id_suplier = b.id_suplier 
        left join akun as d on d.id_akun = a.id_akun
        where a.no_nota = '$r->no_nota' and a.id_akun = '35'
        group by a.id_bayar_bk;");




        $data =  [
            'title' => 'Print Bahan Baku',
            'pembelian' => $pembelian,
            'produk' => $produk,
            'bayar' => $bayar

        ];
        return view('pembelian_bk.print', $data);
    }

    public function delete_bk(Request $r)
    {
        $nota = $r->no_nota;
        DB::table('invoice_bk')->where('no_nota', $nota)->delete();
        DB::table('bayar_bk')->where('no_nota', $nota)->delete();
        DB::table('pembelian')->where('no_nota', $nota)->delete();
        return redirect()->route('pembelian_bk')->with('sukses', 'Data berhasil ditambahkan');
    }

    public function edit_pembelian_bk(Request $r)
    {
        $nota = $r->nota;

        $invoice = DB::table('invoice_bk')->where('no_nota', $nota)->first();
        $invoice2 = DB::table('bayar_bk')->where(['no_nota' => $nota, 'bayar' => 'T'])->first();
        $gram = DB::table('pembelian')->where('no_nota', $nota)->get();
        $data =  [
            'title' => 'Edit Bahan Baku',
            'suplier' => DB::table('tb_suplier')->get(),
            'produk' => DB::table('tb_produk')->get(),
            'nota' => $nota,
            'invoice' => $invoice,
            'invoice2' => $invoice2,
            'gram' => $gram,
            'akun' => DB::table('akun')->get()

        ];
        return view('pembelian_bk.edit', $data);
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
        DB::table('bayar_bk')->where(['no_nota' => $nota, 'bayar' => 'T'])->delete();

        for ($x = 0; $x < count($id_produk); $x++) {
            $data = [
                'tgl' => $tgl,
                'no_nota' => $nota,
                'id_produk' => $id_produk[$x],
                // 'suplier_awal' => $suplier_awal,
                // 'suplier_akhir' => $suplier_akhir,
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
        if (empty($r->debit_tambahan) || $r->debit_tambahan == '0') {
            # code...
        } else {
            $data_tambahan = [
                'no_nota' => $nota,
                'debit' => $r->debit_tambahan,
                'kredit' => 0,
                'id_akun' => $r->id_akun_lainnya,
                'tgl' => $tgl,
                'admin' => Auth::user()->name,
                'ket' => $r->ket_lainnya
            ];
            DB::table('bayar_bk')->insert($data_tambahan);
        }
        return redirect()->route('pembelian_bk')->with('sukses', 'Data berhasil ditambahkan');
    }

    public function grading(Request $r)
    {
        DB::table('grading')->where('id_invoice', $r->id_invoice)->delete();
        $data = [
            'tgl' => $r->tgl,
            'id_invoice' => $r->id_invoice,
            'no_campur' => $r->no_campur,
            'gr_basah' => $r->gr_basah,
            'pcs_awal' => $r->pcs_awal,
            'gr_kering' => $r->gr_kering
        ];
        DB::table('grading')->insert($data);
        return redirect()->route('pembelian_bk')->with('sukses', 'Data berhasil ditambahkan');
    }

    public function approve_invoice_bk(Request $r)
    {
        for ($x = 0; $x < count($r->ceknota); $x++) {
            $data = [
                'approve' => "Y"
            ];
            DB::table('invoice_bk')->where('no_nota', $r->ceknota[$x])->update($data);
        }
        return redirect()->route('pembelian_bk')->with('sukses', 'Data berhasil diapprove');
    }

    public function get_grading(Request $r)
    {
        $data = [
            'grading' => DB::table('grading')->where('id_invoice', $r->id_invoice)->first(),
            'invoice' => DB::table('invoice_bk')->where('id_invoice_bk', $r->id_invoice)->first()
        ];

        return view('pembelian_bk.grading', $data);
    }
}
