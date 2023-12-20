<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MenuModel extends Model
{
    use HasFactory;

    public static function tbmenu()
    {
        return DB::table('tb_menu as a')
            ->select('a.id_menu', 'd.kategori', 'b.point', 'b.handicap', 'a.kd_menu', 'a.nm_menu', 'a.tipe', 'c.nm_station', 'a.aktif')
            ->leftJoin('tb_handicap as b', 'b.id_handicap', '=', 'a.id_handicap')
            ->leftJoin('tb_station as c', 'c.id_station', '=', 'a.id_station')
            ->leftJoin('tb_kategori as d', 'd.kd_kategori', '=', 'a.id_kategori')
            ->where('a.lokasi', '1');
    }
}
