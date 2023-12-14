<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JurnalAktivaController extends Controller
{
    public function add_balik_aktiva(Request $r)
    {
        $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '2')->first();

        if (empty($max)) {
            $nota_t = '1000';
        } else {
            $nota_t = $max->nomor_nota + 1;
        }
        if (empty($r->kategori)) {
            $kategori =  'aktiva';
        } else {
            $kategori =  $r->kategori;
        }

        if ($kategori == 'aktiva') {
            $akun_gantung = DB::table('akun')->where('id_akun', 25)->first();
            $akun_aktiva = DB::table('akun')->where('id_akun', 26)->first();
            $post = DB::select("SELECT * FROM tb_post_center as a where a.id_akun = '$akun_gantung->id_akun' and a.nm_post not in(SELECT b.nm_aktiva FROM aktiva as b)");
        }
        // else if ($kategori == 'peralatan') {
        //     $akun_gantung = DB::table('akun')->whereIn('id_akun', [61, 76])->get();
        //     $akun_aktiva = DB::table('akun')->where('id_akun', 16)->first();
        //     $post = 'peralatan';
        // } else if ($kategori == 'pullet') {
        //     $akun_gantung = DB::table('akun')->where('id_akun', 76)->first();
        //     $akun_aktiva = DB::table('akun')->where('id_akun', 75)->first();
        //     $post = DB::select("SELECT * FROM tb_post_center as a where a.id_akun = '$akun_gantung->id_akun' and a.nm_post not in(SELECT b.nm_aktiva FROM peralatan as b)");
        // } else {
        //     $akun_gantung = DB::table('akun')->where('id_akun', 60)->first();
        //     $akun_aktiva = DB::table('akun')->where('id_akun', 30)->first();
        //     $post = DB::select("SELECT * FROM tb_post_center as a where a.id_akun = '$akun_gantung->id_akun' and a.nm_post not in(SELECT b.nm_produk FROM tb_produk as b)");
        // }

        $data =  [
            'title' => 'Tambah Jurnal Pembalik Aktiva Gantung',
            'max' => $nota_t,
            'suplier' => DB::table('tb_suplier')->get(),
            'id_buku' => $r->id_buku,
            'akun_gantung' => $akun_gantung,
            'akun_aktiva' => $akun_aktiva,
            'post' => $post,
            'kategori' => $kategori

        ];

        return view('pembukuan.jurnal_pembalik_aktiva.add_aktiva', $data);
    }

    public function get_total_post(Request $r)
    {
        $total =  DB::selectOne("SELECT sum(a.debit) as debit FROM jurnal as a where a.id_post_center = $r->id_post");
        $formattedTotal = number_format($total->debit, 0, ',', '.');

        $data = [
            'format' => "Rp. $formattedTotal",
            'biasa' => $total->debit
        ];
        return response()->json($data);
    }

    public function save_jurnal_aktiva(Request $r)
    {
        $tgl = $r->tgl;
        // $no_nota = $r->no_nota;
        $id_akun = $r->id_akun;
        $keterangan = $r->keterangan;
        $debit = $r->debit;
        $kredit = $r->kredit;
        $id_proyek = $r->id_proyek;
        $id_suplier = $r->id_suplier;
        $no_urut = $r->no_urut;
        $id_post = $r->id_post;
        $id_buku = $r->id_buku;


        $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '2')->first();

        if (empty($max)) {
            $nota_t = '1000';
        } else {
            $nota_t = $max->nomor_nota + 1;
        }
        DB::table('notas')->insert(['nomor_nota' => $nota_t, 'id_buku' => '2']);

        for ($i = 0; $i < count($id_akun); $i++) {
            $max_akun = DB::table('jurnal')->latest('urutan')->where('id_akun', $id_akun[$i])->first();
            $akun = DB::table('akun')->where('id_akun', $id_akun[$i])->first();
            $urutan = empty($max_akun) ? '1001' : ($max_akun->urutan == 0 ? '1001' : $max_akun->urutan + 1);


            $data = [
                'tgl' => $tgl,
                'no_nota' => 'JU-' . $nota_t,
                'id_akun' => $id_akun[$i],
                'no_dokumen' => $no_urut[$i],
                'id_buku' => $id_buku,
                'ket' => $keterangan[$i],
                'debit' => $debit[$i],
                'kredit' => $kredit[$i],
                'admin' => auth()->user()->name,
                // 'no_dokumen' => $r->no_dokumen,
                'tgl_dokumen' => $r->tgl_dokumen,
                'id_proyek' => $id_proyek,
                'id_suplier' => $id_suplier,
                'no_urut' => $akun->inisial . '-' . $urutan,
                'urutan' => $urutan,
                'id_post_center' => $id_post
            ];
            DB::table('jurnal')->insert($data);
        }

        $nota_cek = 'JU-' . $nota_t;

        return redirect()->route('jurnal.cek_aktiva', ['no_nota' => $nota_cek, 'kategori' => $r->kategori])->with('sukses', 'Data berhasil ditambahkan');
    }

    public function get_data_kelompok(Request $r)
    {
        $id_kelompok = $r->id_kelompok;
        $kelompok =  DB::table('kelompok_aktiva')->where('id_kelompok', $id_kelompok)->first();

        $data = [
            'nilai_persen' => $kelompok->tarif,
            'tahun' => $kelompok->umur
        ];
        echo json_encode($data);
    }

    public function cek_aktiva(Request $r)
    {
        if ($r->kategori == 'aktiva') {
            $kelompok = DB::table('kelompok_aktiva')->get();
        } else if ($r->kategori == 'peralatan') {
            $kelompok = DB::table('kelompok_peralatan')->get();
        } else {
            $kelompok = 0;
        }

        $data =  [
            'title' => 'Cek Nota',
            'no_nota' => $r->no_nota,
            'gudang' => DB::table('tb_gudang')->where('kategori_id', 1)->get(),
            'jurnal' => DB::table('jurnal as a')
                ->join('akun as b', 'a.id_akun', 'b.id_akun')
                ->where('a.no_nota', $r->no_nota)
                ->get(),
            'head_jurnal' => DB::selectOne("SELECT a.ket,c.nm_suplier, a.tgl, b.nm_proyek, a.id_proyek, a.no_dokumen,a.tgl_dokumen, a.no_nota, sum(a.debit) as debit , sum(a.kredit) as kredit , d.nm_post
            FROM jurnal as a 
            left join proyek as b on b.id_proyek = a.id_proyek
            left join tb_suplier as c on c.id_suplier = a.id_suplier
            left join tb_post_center as d on d.id_post_center = a.id_post_center
            where a.no_nota = '$r->no_nota'"),
            'kelompok' => $kelompok,
            'kategori' => $r->kategori,
            'pembelian' => $r->pembelian ?? '',
            'satuan' => DB::table('tb_satuan')->get()
        ];

        return view('pembukuan.jurnal_pembalik_aktiva.cek_aktiva', $data);
    }

    public function save_aktiva(Request $r)
    {
        $id_kelompok = $r->id_kelompok;
        $nm_aktiva = $r->nm_aktiva;
        $tgl = $r->tgl;
        $h_perolehan = $r->h_perolehan;

        for ($x = 0; $x < count($id_kelompok); $x++) {
            $kelompok =  DB::table('kelompok_aktiva')->where('id_kelompok', $id_kelompok[$x])->first();
            $biaya_depresiasi = ($h_perolehan[$x] * $kelompok->tarif) / 12;

            $data = [
                'id_kelompok' => $id_kelompok[$x],
                'nm_aktiva' => $nm_aktiva[$x],
                'tgl' => $tgl[$x],
                'h_perolehan' => $h_perolehan[$x],
                'biaya_depresiasi' => $biaya_depresiasi,
                'admin' => auth()->user()->name,
            ];
            DB::table('aktiva')->insert($data);
        }
        return 'berhasil';
        // return redirect()->route('aktiva')->with('sukses', 'Data berhasil ditambahkan');
    }
}
