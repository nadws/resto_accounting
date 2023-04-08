<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Jurnal;
use App\Models\proyek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class JurnalController extends Controller
{
    public function index(Request $r)
    {
        if (empty($r->tgl1)) {
            $tgl1 =  date('Y-m-01');
            $tgl2 =  date('Y-m-t');
        } else {
            $tgl1 =  $r->tgl1;
            $tgl2 =  $r->tgl2;
        }
        if (empty($r->id_proyek)) {
            $id_proyek = 0;
        } else {
            $id_proyek = $r->id_proyek;
        }
        if ($id_proyek == 0) {
            $jurnal = Jurnal::whereBetween('tgl', [$tgl1, $tgl2])->orderBY('id_jurnal', 'DESC')->get();
        } else {
            $jurnal = Jurnal::whereBetween('tgl', [$tgl1, $tgl2])->where('id_proyek', $id_proyek)->orderBY('id_jurnal', 'DESC')->get();
        }



        $data =  [
            'title' => 'Jurnal Umum',
            'jurnal' => $jurnal,
            'proyek' => proyek::where('status', 'berjalan')->get()

        ];
        return view('Jurnal.index', $data);
    }

    public function add()
    {
        $max = DB::table('notas')->latest('nomor_nota')->first();

        if (empty($max)) {
            $nota_t = '1000';
        } else {
            $nota_t = $max->nomor_nota + 1;
        }
        $data =  [
            'title' => 'Jurnal Umum',
            'max' => $nota_t,
            'proyek' => proyek::all()

        ];
        return view('Jurnal.add', $data);
    }

    public function load_menu()
    {
        $data =  [
            'title' => 'Jurnal Umum',
            'akun' => Akun::all(),
            'proyek' => proyek::all()

        ];
        return view('Jurnal.load_menu', $data);
    }
    public function tambah_baris_jurnal(Request $r)
    {
        $data =  [
            'title' => 'Jurnal Umum',
            'akun' => Akun::all(),
            'count' => $r->count

        ];
        return view('Jurnal.tbh_baris', $data);
    }

    public function save_jurnal(Request $r)
    {
        $tgl = $r->tgl;
        // $no_nota = $r->no_nota;
        $id_akun = $r->id_akun;
        $keterangan = $r->keterangan;
        $debit = $r->debit;
        $kredit = $r->kredit;
        $id_proyek = $r->id_proyek;
        $no_urut = $r->no_urut;

        $max = DB::table('notas')->latest('nomor_nota')->first();

        if (empty($max)) {
            $nota_t = '1000';
        } else {
            $nota_t = $max->nomor_nota + 1;
        }
        DB::table('notas')->insert(['nomor_nota' => $nota_t]);


        for ($i = 0; $i < count($id_akun); $i++) {
            $data = [
                'tgl' => $tgl,
                'no_nota' => 'KS-' . $nota_t,
                'id_akun' => $id_akun[$i],
                'id_buku' => '2',
                'ket' => $keterangan[$i],
                'debit' => $debit[$i],
                'kredit' => $kredit[$i],
                'admin' => Auth::user()->name,
                'no_dokumen' => $r->no_dokumen,
                'tgl_dokumen' => $r->tgl_dokumen,
                'id_proyek' => $id_proyek,
                'no_urut' => $no_urut[$i]
            ];
            Jurnal::create($data);
        }


        return redirect()->route('jurnal')->with('sukses', 'Data berhasil ditambahkan');
    }

    public function delete(Request $r)
    {
        $nomer = substr($r->no_nota, 3);
        DB::table('notas')->where('nomor_nota', $nomer)->delete();
        Jurnal::where('no_nota', $r->no_nota)->delete();
        return redirect()->route('jurnal')->with('sukses', 'Data berhasil dihapus');
    }
}
