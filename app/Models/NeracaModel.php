<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NeracaModel extends Model
{
    use HasFactory;

    public static function Getaktiva_tetap($tgl1, $tgl2, $id_klasifikasi)
    {
        $result = DB::select("SELECT a.id_akun, a.nm_akun, b.kredit, b.debit
            FROM akun as a
            LEFT JOIN (
                SELECT b.id_akun, SUM(b.debit) as debit, SUM(b.kredit) as kredit
                FROM jurnal as b
                WHERE b.id_buku NOT IN (5, 13) AND b.tgl BETWEEN ? AND ?
                GROUP BY b.id_akun
            ) as b ON b.id_akun = a.id_akun
            WHERE a.id_klasifikasi = ?;
        ", [$tgl1, $tgl2, $id_klasifikasi]);

        return $result;
    }
}
