<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CashflowModel extends Model
{
    use HasFactory;

    public static function cashflow_uangmasuk_setahun($id_buku, $id_klasifikasi, $tahun)
    {
        $id_klasifikasi_value = implode(",", $id_klasifikasi);
        $result = DB::select("SELECT a.id_akun, b.nm_akun , sum(a.debit) as debit,MONTH(a.tgl) as bulan , YEAR(a.tgl) as tahun
        FROM jurnal as a 
        left join akun as b on b.id_akun = a.id_akun
        where b.id_klasifikasi in({$id_klasifikasi_value}) and a.id_buku = ? and YEAR(a.tgl) = ?
        group by a.id_akun, MONTH(a.tgl), YEAR(a.tgl);
        ", [$id_buku, $tahun]);

        return $result;
    }
}
