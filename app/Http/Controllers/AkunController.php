<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\PostCenter;
use App\Models\SubklasifikasiAkun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Nonaktif;

class AkunController extends Controller
{
    public function index()
    {
        $data =  [
            'title' => 'Daftar Akun',
            'akun' => Akun::where('nonaktif', 'T')->orderBy('id_akun', 'DESC')->get(),
            'subklasifikasi' => SubklasifikasiAkun::all()
        ];
        return view('Akun.index', $data);
    }

    public function create(Request $r)
    {
        $data = [
            'id_klasifikasi' => $r->id_klasifikasi,
            'kode_akun' => $r->kode_akun,
            'nm_akun' => $r->nm_akun,
            'inisial' => strtoupper($r->inisial),
            'iktisar' => $r->iktisar,
        ];
        Akun::create($data);
        return redirect()->route('akun');
    }

    public function get_kode(Request $r)
    {
        $id_sub = $r->id_sub;

        $kode =  SubklasifikasiAkun::where('id_subklasifikasi_akun', $id_sub)->first();
        $max = Akun::where('id_klasifikasi', $id_sub)->latest('kode_akun')->first();

        if (empty($max->kode_akun)) {
            $kodemax = '1';
        } else {
            $kodemax = $max->kode_akun + 1;
        }
        $data = [
            'kode' => "$kode->kode_sub-$kodemax",
            'kode_max' => $kodemax
        ];
        return $data;
    }

    public function get_edit_akun($id_akun)
    {
        $data = [
            'akun' => DB::table("akun as a")->join('subklasifikasi_akun as b', 'a.id_klasifikasi', 'b.id_subklasifikasi_akun')->orderBy('a.id_akun', 'DESC')->get(),
            'subklasifikasi' => SubklasifikasiAkun::all()
        ];
        return view('Akun.getEdit', $data);
    }

    public function update(Request $r)
    {
        $data = [
            'id_klasifikasi' => $r->id_klasifikasi,
            'kode_akun' => $r->kode_akun,
            'nm_akun' => $r->nm_akun,
            'inisial' => $r->inisial,
            'iktisar' => $r->iktisar,
        ];
        Nonaktif::edit('akun', 'id_akun', $r->id_akun, $data);
        return redirect()->route('akun');
    }

    public function load_sub_akun($id_akun)
    {
        $data = [
            'detail' => PostCenter::where('id_akun', $id_akun)->get(),
            'id_akun' => $id_akun,
        ];
        return view('Akun.sub_akun', $data);
    }

    public function add_sub(Request $r)
    {
        PostCenter::insert([
            'id_akun' => $r->id_akun,
            'nm_post' => $r->nm_post,
        ]);
    }

    public function remove_sub(Request $r)
    {
        PostCenter::where('id_post_center', $r->id)->update(['nonaktif', 'T']);
    }
}
