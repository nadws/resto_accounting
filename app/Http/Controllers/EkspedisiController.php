<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EkspedisiController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Data Ekspedisi',
            'ekspedisi' => DB::table('tb_ekspedisi')->get()
        ];
        return view('datamaster.ekspedisi.index', $data);
    }

    public function create(Request $r)
    {
        DB::table('tb_ekspedisi')->insert(['nm_ekspedisi' => $r->nm_ekspedisi]);
        if($r->route){
            return redirect()->route('ekspedisi.index')->with('sukses', 'Data Berhasil ditambahkan');
        }
    }

    public function update(Request $r)
    {
        DB::table('tb_ekspedisi')->where('id_ekspedisi', $r->id_ekspedisi)->update(['nm_ekspedisi' => $r->nm_ekspedisi]);
        return redirect()->route('ekspedisi.index')->with('sukses', 'Data Berhasil ditambahkan');

    }
    public function delete($id)
    {
        DB::table('tb_ekspedisi')->where('id_ekspedisi', $id)->delete();
        return redirect()->route('ekspedisi.index')->with('sukses', 'Data Berhasil ditambahkan');

    }
}
