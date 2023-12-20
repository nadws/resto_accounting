<?php

namespace App\Http\Controllers;

use App\Models\MenuModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index(Request $r)
    {
        $data = [
            'title' => 'Data Menu',
            'st' => DB::table('tb_station')
                ->where('id_lokasi', '1')
                ->orderBy('id_station', 'ASC')
                ->get(),
            'menu' => DB::table('tb_menu')
                ->orderBy('kd_menu', 'desc')
                ->where('lokasi', '1')
                ->first(),
            'kategori' => DB::table('tb_kategori')->where('lokasi', 'TAKEMORI')->get(),
            'handicap' => DB::table('tb_handicap')->where('id_lokasi', '1')->get()
        ];
        return view('datamenu.menu.index', $data);
    }

    public function get_menu(Request $request)
    {
        $perPage = 10; // Adjust the per-page limit as needed
        $search = $request->input('search');
        $query = MenuModel::tbmenu();

        // Filter data sesuai kriteria pencarian

        $query
            ->where(function ($query) use ($search) {
                $query->where('d.kategori', 'like', '%' . $search . '%')
                    ->orWhere('a.kd_menu', 'like', '%' . $search . '%')
                    ->orWhere('a.nm_menu', 'like', '%' . $search . '%')
                    ->orWhere('a.tipe', 'like', '%' . $search . '%')
                    ->orWhere('c.nm_station', 'like', '%' . $search . '%');
            });

        $menu = $query->orderBy('a.id_menu', 'DESC')->paginate($perPage);

        $data = [
            'menu' => $menu,
        ];

        return view('datamenu.menu.getmenu', $data);
    }



    function addresep(Request $r)
    {
        $data = [
            'title' => 'Tambah resep',
            'atk' => DB::table('atk')->get()
        ];
        return view('datamenu.menu.resep', $data);
    }

    function aktif(Request $r)
    {
        DB::table('tb_menu')->where('id_menu', $r->id_menu)->update(['aktif' => $r->status]);
    }
}
