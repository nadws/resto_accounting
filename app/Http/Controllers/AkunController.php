<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\SubklasifikasiAkun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AkunController extends Controller
{
    public function index()
    {
        $data =  [
            'title' => 'Daftar Akun',
            'akun' => Akun::all(),
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

    public function get_edit_akun(Request $r)
    {
        $data = [
            'akun' => Akun::where('id_akun', $r->id_akun)->first(),
            'subklasifikasi' => SubklasifikasiAkun::all()
        ];
        return view('Akun.getEdit', $data);
    }
}
