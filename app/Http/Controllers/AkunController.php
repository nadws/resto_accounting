<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AkunController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Daftar Akun',
            'akun' => DB::table('akun as a')
                ->join('subklasifikasi_akun as b', 'a.id_klasifikasi', 'b.id_subklasifikasi_akun')
                ->where('is_active', 'Y')
                ->orderBy('id_akun', 'DESC')
                ->get(),
            'kategori_akun' => DB::table('subklasifikasi_akun')->get()

        ];
        return view('akun.home',$data);
    }
    function load()
    {
        $data = [
            'akun' => DB::table('akun as a')
                ->join('subklasifikasi_akun as b', 'a.id_klasifikasi', 'b.id_subklasifikasi_akun')
                ->where('is_active', 'Y')
                ->orderBy('id_akun', 'DESC')
                ->get()
        ];
        return view('akun.index', $data);
    }

    function save(Request $r)
    {
        $data = [
            'kode_akun' => $r->kode_akun,
            'inisial' => $r->inisial,
            'nm_akun' => $r->nm_akun,
            'id_klasifikasi' => $r->id_klasifikasi,
        ];

        DB::table('akun')->insert($data);
    }
    public function edit_load(Request $r)
    {
        $data = [
            'akun' => DB::table('akun')->where('id_akun', $r->id_akun)->first(),
            'kategori_akun' => DB::table('subklasifikasi_akun')->get()
        ];
        return view('akun.edit_load',$data);
    }

    public function update(Request $r)
    {
        $data = [
            'kode_akun' => $r->kode_akun,
            'inisial' => $r->inisial,
            'nm_akun' => $r->nm_akun,
            'id_klasifikasi' => $r->id_klasifikasi,
        ];

        DB::table('akun')->where('id_akun', $r->id_akun)->update($data);
    }

    public function hapus(Request $r)
    {
        $cek = DB::table('jurnal')->where('id_akun', $r->id_akun)->first();
        if(!$cek) {
            DB::table('akun')->where('id_akun', $r->id_akun)->delete();
            return 'Berhasil';
        }
        return 'Akun ada dijurnal';
    }
    public function post_center(Request $r)
    {
        $cek = DB::table('tb_post_center')->where('id_akun', $r->id_akun)->get();
        $data = [
            'post_center' => $cek,
            'id_akun' => $r->id_akun,
        ];
        return view('akun.post_center',$data);
    }

    public function create_post_center(Request $r)
    {
        DB::table('tb_post_center')->insert([
            'nm_post' => $r->nm_post,
            'id_akun' => $r->id_akun,
        ]);
        return $r->id_akun;
    }

    public function edit_post(Request $r)
    {
        $cek = DB::table('tb_post_center')->where('id_post_center', $r->id_post)->first();
        $data = [
            'post_center' => $cek,
        ];
        return view('akun.edit_post_center',$data);
    }

    public function update_post_center(Request $r)
    {
        DB::table('tb_post_center')->where('id_post_center', $r->id_post_center)->update([
            'nm_post' => $r->nm_post,
        ]);
        return $r->id_akun;
    }
    public function delete_post_center(Request $r)
    {
        DB::table('tb_post_center')->where('id_post_center', $r->id_post)->delete();
        return $r->id_akun;
    }
}
