<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use Illuminate\Http\Request;

class GudangController extends Controller
{
    protected 
    $id_departemen = 1;


    public function index()
    {
        $data = [
            'title' => 'Data Gudang',
            'gudang' => Gudang::all()
        ];
        return view('data_master.gudang.gudang',$data);
    }

    public function create(Request $r)
    {
        $route = $r->url ?? 'gudang';
        $route = $r->segment ? 'produk.detail' : $route;
        Gudang::create([
            'nm_gudang' => $r->nm_gudang,
            'kd_gudang' => $r->kd_gudang,
            'id_departemen' => $this->id_departemen,
            'admin' => auth()->user()->name,
        ]);

        return redirect()->route($route, $r->segment ?? '')->with('sukses', 'Berhasil tambah data');
    }

    public function edit_load($id_gudang)
    {
        $data = [
            'gudang' => Gudang::where('id_gudang',$id_gudang)->first()
        ];
        return view('data_master.gudang.edit',$data);
    }

    public function edit(Request $r)
    {
        Gudang::where('id_gudang',$r->id_gudang)->update([
            'nm_gudang' => $r->nm_gudang,
            'kd_gudang' => $r->kd_gudang,
            'admin' => auth()->user()->name,
        ]);

        return redirect()->route('gudang')->with('sukses', 'Berhasil update data');
    }

    public function delete($id_gudang)
    {
        Gudang::findOrFail($id_gudang)->delete();
        return redirect()->route('gudang')->with('sukses', 'Berhasil hapus data');
    }
}
