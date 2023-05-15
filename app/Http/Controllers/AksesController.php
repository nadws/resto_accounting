<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AksesController extends Controller
{
    public function index()
    {
        $boleh = [
            '1'
        ];
        if (in_array(auth()->user()->id, $boleh)) {
            $data = [
                'title' => 'Permission Halaman',
                'permissionHalaman' => DB::table('permission')->get(),
                'permissionButton' => DB::table('permission_button as a')->join('permission as b', 'b.id_permission', 'a.permission_id')->get(),
            ];
            return view('permission_halaman.index', $data);
        } else {
            abort(403, 'akses tidak ada');
        }
    }

    public function addMenu(Request $r)
    {
        if (empty($r->detail)) {
            $id = DB::table('permission')->insertGetId([
                'nm_permission' => $r->nm_permission,
                'url' => $r->url,
            ]);

            for ($i = 0; $i < count($r->nm_button); $i++) {
                DB::table('permission_button')->insert([
                    'permission_id' => $id,
                    'nm_permission_button' => $r->nm_button[$i],
                    'jenis' => $r->jenis[$i],
                ]);
            }
        } else {
            for ($i = 0; $i < count($r->nm_button_detail); $i++) {
                DB::table('permission_button')->where('id_permission_button', $r->id_permission_button[$i])->update([
                    'permission_id' => $r->id_permission_gudang,
                    'nm_permission_button' => $r->nm_button_detail[$i],
                    'jenis' => $r->jenis[$i],
                ]);
            }

            if (!empty($r->tambah_row)) {
                for ($i = 0; $i < count($r->nm_button_row); $i++) {
                    DB::table('permission_button')->insert([
                        'permission_id' => $r->id_permission_gudang,
                        'nm_permission_button' => $r->nm_button_row[$i],
                        'jenis' => $r->jenis_row[$i],
                    ]);
                }
            }
        }


        return redirect()->route('akses.index')->with('sukses', 'Data Berhasil');
    }

    public function detail($id)
    {
        $detail = DB::table('permission_button as a')->join('permission as b', 'a.permission_id', 'b.id_permission')->where('id_permission', $id)->get();

        return response()->json($detail);
    }

    public function editMenu(Request $r)
    {
    }
}
