<?php

namespace App\Http\Controllers;

use App\Models\Buku_besar;
use App\Models\Jurnal;
use App\Models\proyek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProyekController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $r)
    {
        $data =  [
            'title' => 'Proyek',
            'proyek' => proyek::orderBy('id_proyek', 'DESC')->get()

        ];
        return view('proyek.index', $data);
    }

    public function add(Request $r)
    {
        $data = [
            'kode_proyek' => $r->kd_proyek,
            'nm_proyek' => $r->nm_proyek,
            'tgl' => $r->tgl,
            'tgl_estimasi' => $r->tgl_estimasi,
            'manager_proyek' => $r->manager_proyek,
            'biaya_estimasi' => $r->biaya_estimasi,
        ];

        Proyek::create($data);
        return redirect()->route('proyek')->with('sukses', 'Data berhasil ditambahkan');
    }
    public function delete(Request $r)
    {
        $jurnal = Jurnal::where('id_proyek', $r->id_proyek)->first();

        if (!empty($jurnal)) {
            return redirect()->route('proyek')->with('error', 'Data gagal dihapus');
        } else {
            Proyek::where('id_proyek', $r->id_proyek)->delete();
            return redirect()->route('proyek')->with('sukses', 'Data berhasil dihapus');
        }
    }
}
