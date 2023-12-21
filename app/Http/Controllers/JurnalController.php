<?php

namespace App\Http\Controllers;

use App\Exports\JurnalExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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
            5 => 'pengeluaran aktiva gantung',
            6 => 'pembalikan aktiva gantung',
        ];
        $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '2')->first();


        if (empty($max)) {
            $nota_t = '1000';
        } else {
            $nota_t = $max->nomor_nota + 1;
        }

        $data = [
            'title' => "Tambah Jurnal " . ucwords($kategori[$r->id_buku]),
            'max' => $nota_t,
            'suplier' => DB::table('tb_suplier')->get(),
            'id_buku' => $r->id_buku,
        ];
        switch ($r->id_buku) {
            case '6':
                return redirect()->route('jurnal.add_balik_aktiva', ['id_buku' => $r->id_buku]);
            default:
                return view('pembukuan.jurnal.add', $data);
                break;
        }
    }

    public function get_post(Request $r)
    {
        $id_akun = $r->id_akun;
        $post = DB::table('tb_post_center')->where('id_akun', $id_akun)->get();

        echo "<option value=''>Pilih sub akun</option>";
        foreach ($post as $k) {
            echo "<option value='" . $k->id_post_center  . "'>" . $k->nm_post . "</option>";
        }
    }

    public function load_menu(Request $r)
    {
        $data =  [
            'title' => 'Jurnal Umum',
            'akun' => DB::table('akun')->whereBetween('id_akun', [39.54])->get(),
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

    public function edit_jurnal(Request $r)
    {
        $data =  [
            'title' => 'Edit Jurnal Umum',
            'jurnal' => DB::table('jurnal')->where('no_nota', $r->no_nota)->get(),
            'akun' => DB::table('akun')->get(),
            'no_nota' => $r->no_nota,
            'head_jurnal' => DB::selectOne("SELECT a.id_buku, a.tgl, a.id_proyek, a.no_dokumen,a.tgl_dokumen, sum(a.debit) as debit , sum(a.kredit) as kredit FROM jurnal as a where a.no_nota = '$r->no_nota'")
        ];
        return view('pembukuan.jurnal.edit', $data);
    }
    public function update_jurnal(Request $r)
    {
        $tgl = $r->tgl;
        // $no_nota = $r->no_nota;
        $id_akun = $r->id_akun;
        $id_akun2 = $r->id_akun2;
        $keterangan = $r->keterangan;
        $debit = $r->debit;
        $kredit = $r->kredit;
        $id_proyek = $r->id_proyek;
        $no_urut = $r->no_urut;
        $nota_t = $r->no_nota;
        $id_post = $r->id_post;
        $id_jurnal = $r->id_jurnal;
        $no_dokumen = $r->no_dokumen;

        DB::table('jurnal')->where('no_nota', $nota_t)->delete();

        for ($i = 0; $i < count($id_akun); $i++) {
            if ($id_akun[$i] == $id_akun2[$i] || !empty($id_akun2[$i])) {
                $no_urutan = $no_urut[$i];
            } else {
                $max_akun = DB::table('jurnal')->latest('urutan')->where('id_akun', $id_akun[$i])->first();
                $akun = DB::table('akun')->where('id_akun', $id_akun[$i])->first();
                if (empty($max_akun) || $max_akun->urutan == 0) {
                    $urutan = '1001';
                } else {
                    $urutan = $max_akun->urutan + 1;
                }
                $no_urutan = $akun->inisial . '-' . $urutan;
            }

            $data = [
                'tgl' => $tgl,
                'no_nota' => $nota_t,
                'id_akun' => $id_akun[$i],
                'id_buku' => $r->id_buku,
                'ket' => $keterangan[$i],
                'debit' => $debit[$i],
                'kredit' => $kredit[$i],
                'admin' => Auth::user()->name,
                'no_dokumen' => empty($no_dokumen[$i]) ? ' ' : $no_dokumen[$i],
                'tgl_dokumen' => $r->tgl_dokumen,
                'id_proyek' => $id_proyek,
                'no_urut' => $no_urutan,
                'id_post_center' => $id_post[$i]
            ];
            DB::table('jurnal')->insert($data);
        }

        $tgl1 = date('Y-m-01', strtotime($r->tgl));
        $tgl2 = date('Y-m-t', strtotime($r->tgl));
        return redirect()->route('jurnal.index', ['period' => 'costume', 'tgl1' => $tgl1, 'tgl2' => $tgl2, 'id_proyek' => 0, 'id_buku' => $r->id_buku])->with('sukses', 'Data berhasil diupdate');
    }

    public function delete(Request $r)
    {
        $nomer = substr($r->no_nota, 3);
        DB::table('notas')->where('nomor_nota', $nomer)->delete();
        DB::table('jurnal')->where('no_nota', $r->no_nota)->delete();

        $tgl1 = $r->tgl1;
        $tgl2 = $r->tgl2;
        $id_proyek = $r->id_proyek;
        return redirect()->route('jurnal.index', ['period' => 'costume', 'tgl1' => $tgl1, 'tgl2' => $tgl2, 'id_proyek' => $id_proyek, 'id_buku' => $r->id_buku])->with('sukses', 'Data berhasil dihapus');
    }

    public function export_jurnal(Request $r)
    {
        $tgl1 =  $r->tgl1;
        $tgl2 =  $r->tgl2;
        $id_proyek = $r->id_proyek;
        $id_buku = $r->id_buku;

        $idp = $id_proyek == 0 ? '' : "and a.id_proyek = '$id_proyek'";

        $total = DB::selectOne("SELECT count(a.id_jurnal) as jumlah FROM jurnal as a where a.id_buku not in('6','4') and a.tgl between '$tgl1' and '$tgl2' and a.debit != '0'");

        $totalrow = $total->jumlah;

        return Excel::download(new JurnalExport($tgl1, $tgl2, $id_proyek, $id_buku, $totalrow), 'jurnal.xlsx');
    }
}
