<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JurnalController extends Controller
{

    public function index(Request $r)
    {
        $tgl = tanggalFilter($r);
        $tgl1 = $tgl['tgl1'];
        $tgl2 = $tgl['tgl2'];
        $id_buku = $r->id_buku ?? 2;

        $jurnal =  DB::select("SELECT a.penutup, a.no_dokumen, a.id_jurnal,a.no_urut,a.admin, a.id_akun, a.tgl, a.debit, a.kredit, a.ket,a.no_nota, b.nm_akun, c.nm_post, d.nm_proyek FROM jurnal as a 
            left join akun as b on b.id_akun = a.id_akun
            left join tb_post_center as c on c.id_post_center = a.id_post_center
            left join proyek as d on d.id_proyek = a.id_proyek
            where a.id_buku ='$id_buku' and a.tgl between '$tgl1' and '$tgl2' order by  a.id_jurnal DESC");


        $data = [
            'title' => 'Jurnal',
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'id_buku' => $id_buku,
            'jurnal' => $jurnal
        ];
        return view('pembukuan.jurnal.index', $data);
    }

    public function add(Request $r)
    {
        $kategori = [
            2 => 'biaya',
            4 => 'Hutang',
        ];
        $nota_t = 1000;
        // $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '2')->first();

        $data = [
            'title' => "Tambah Jurnal " . ucwords($kategori[$r->id_buku]),
            'max' => $nota_t,
            'suplier' => DB::table('tb_suplier')->get(),
            'id_buku' => $r->id_buku,
        ];
        return view('pembukuan.jurnal.add', $data);
    }

    public function load_menu(Request $r)
    {
        $data =  [
            'title' => 'Jurnal Umum',
            'akun' => DB::table('akun')->get(),
            'satuan' => DB::table('tb_satuan')->get(),
            'id_akun' => $r->id_akun,
            'id_buku' => $r->id_buku
        ];
        return view('pembukuan.jurnal.load_menu', $data);
    }

    public function tambah_baris_jurnal(Request $r)
    {
        $data =  [
            'title' => 'Jurnal Umum',
            'akun' => DB::table('akun')->get(),
            'count' => $r->count

        ];
        return view('pembukuan.jurnal.tbh_baris', $data);
    }

    public function create(Request $r)
    {
        $tgl = $r->tgl;
        // $no_nota = $r->no_nota;
        $id_akun = $r->id_akun;
        $keterangan = $r->keterangan;
        $debit = $r->debit;
        $kredit = $r->kredit;
        $id_proyek = $r->id_proyek;
        $id_suplier = $r->id_suplier;
        // $tipe_jurnal = $r->tipe_jurnal;
        $no_urut = $r->no_urut;
        $id_post = $r->id_post;
        $id_buku = $r->id_buku;


        $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', $id_buku)->first();

        if (empty($max)) {
            $nota_t = '1000';
        } else {
            $nota_t = $max->nomor_nota + 1;
        }
        DB::table('notas')->insert(['nomor_nota' => $nota_t, 'id_buku' => $id_buku]);

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
                'admin' => Auth::user()->name,
                'tgl_dokumen' => $r->tgl_dokumen,
                'id_proyek' => $id_proyek,
                'id_suplier' => $id_suplier,
                'no_urut' => $akun->inisial . '-' . $urutan,
                'urutan' => $urutan,
                'id_post_center' => $id_post[$i] ?? 0
            ];
            DB::table('jurnal')->insert($data);
        }

        $tgl1 = date('Y-m-01', strtotime($r->tgl));
        $tgl2 = date('Y-m-t', strtotime($r->tgl));
        return redirect()->route('jurnal.index', ['period' => 'costume', 'tgl1' => $tgl1, 'tgl2' => $tgl2, 'id_proyek' => 0, 'id_buku' => $id_buku])->with('sukses', 'Data berhasil ditambahkan');
    }
}
