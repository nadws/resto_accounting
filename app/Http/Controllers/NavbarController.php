<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NavbarController extends Controller
{
    public function queryNavbar($jenis)
    {
        $id_user = auth()->user()->id;

        $data = DB::table('sub_navbar as a')
            ->join('permission_navbar as b', 'a.id_sub_navbar', 'b.id_sub_navbar')
            ->where([
                ['a.navbar', $jenis],
                ['b.id_user', $id_user],
            ])
            ->get();
        return $data;
    }

    public function buku_besar()
    {
        $data = $this->queryNavbar(1);

        $title = 'Data Master';
        return view('navbar.data_master', compact(['data', 'title']));
    }
    public function pembukuan()
    {
        $data = $this->queryNavbar(2);

        $title = 'Data Master';
        return view('navbar.data_master', compact(['data', 'title']));
    }
    public function persediaan()
    {
        $data = $this->queryNavbar(3);

        $title = 'Data Master';
        return view('navbar.data_master', compact(['data', 'title']));
    }
    public function datamenu()
    {
        $data = $this->queryNavbar(4);


        $title = 'Data Master';
        return view('navbar.data_master', compact(['data', 'title']));
    }


    public function tbhNavbar()
    {
        return view('navbar.tbh_subnavbar');
    }

    public function createNavbar(Request $r)
    {
        for ($i = 0; $i < count($r->judul); $i++) {
            DB::table('sub_navbar')->insert([
                'navbar' => $r->navbar,
                'judul' => $r->judul[$i],
                'route' => $r->route[$i],
                'img' => $r->img[$i],
                'deskripsi' => $r->deskripsi[$i],
            ]);
        }
        return redirect()->route('tbhNavbar')->with('sukses', 'Data Berhasil');
    }
}
