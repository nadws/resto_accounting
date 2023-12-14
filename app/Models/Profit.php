<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Profit extends Model
{
    use HasFactory;
    public static function pendapatan_setahun($tahun, $id_klasifikasi)
    {
        $result = DB::select("SELECT a.id_akun,a.nm_akun, if(b.penutup = 'Y',c.kredit,b.kredit) as kredit, if(b.penutup = 'Y',c.debit,b.debit) as debit,
        b.bulan, b.tahun
        FROM akun as a
        left join (
         SELECT b.id_akun, sum(b.debit) as debit, sum(b.kredit) as kredit, MONTH(b.tgl) as bulan, YEAR(b.tgl) as tahun,b.penutup
         FROM jurnal as b
         WHERE b.id_buku not in(5,13)  and Year(b.tgl) = ?  
         group by b.id_akun , MONTH(b.tgl), YEAR(b.tgl)
        ) as b on b.id_akun = a.id_akun
        
        left JOIN (
          SELECT c.id_akun , sum(c.debit) as debit, sum(c.kredit) as kredit , MONTH(c.tgl) as bulan2, YEAR(c.tgl) as tahun2
           FROM jurnal_saldo_sebelum_penutup as c
           where Year(c.tgl) = ?
           group by c.id_akun , MONTH(c.tgl), YEAR(c.tgl)
        ) as c on c.id_akun = a.id_akun and b.tahun = c.tahun2 and b.bulan = c.bulan2
        where a.id_klasifikasi = ?;
        ", [$tahun, $tahun, $id_klasifikasi]);
        return $result;
    }

    public static function biaya_penyesuaian_setahun($tahun)
    {
        $result = DB::select("SELECT a.id_akun,a.nm_akun, if(b.penutup = 'Y',c.kredit,b.kredit) as kredit, if(b.penutup = 'Y',c.debit,b.debit) as debit, b.bulan, b.tahun, c.bulan2, c.tahun2
        FROM akun as a
        left join (
         SELECT b.id_akun, sum(b.debit) as debit, sum(b.kredit) as kredit, MONTH(b.tgl) as bulan, YEAR(b.tgl) as tahun, b.penutup
         FROM jurnal as b
         WHERE b.id_buku not in(8,6)  and Year(b.tgl) = ?  
         group by b.id_akun , MONTH(b.tgl), YEAR(b.tgl)
        ) as b on b.id_akun = a.id_akun
        
        left JOIN (
          SELECT c.id_akun , sum(c.debit) as debit, sum(c.kredit) as kredit , MONTH(c.tgl) as bulan2, YEAR(c.tgl) as tahun2
           FROM jurnal_saldo_sebelum_penutup as c
           where Year(c.tgl) = ?
           group by c.id_akun , MONTH(c.tgl), YEAR(c.tgl)
        ) as c on c.id_akun = a.id_akun and b.tahun = c.tahun2 and b.bulan = c.bulan2
        where a.id_klasifikasi = '3';
        ", [$tahun, $tahun]);
        return $result;
    }
    public static function biaya_disusutkan_setahun($tahun)
    {
        $result = DB::select("SELECT a.id_akun,a.nm_akun, if(b.penutup = 'Y',c.kredit,b.kredit) as kredit, if(b.penutup = 'Y',c.debit,b.debit) as debit, b.bulan, b.tahun, c.bulan2, c.tahun2
        FROM akun as a
        left join (
         SELECT b.id_akun, sum(b.debit) as debit, sum(b.kredit) as kredit, MONTH(b.tgl) as bulan, YEAR(b.tgl) as tahun,b.penutup
         FROM jurnal as b
         WHERE b.id_buku not in(8,6)  and Year(b.tgl) = ?  
         group by b.id_akun , MONTH(b.tgl), YEAR(b.tgl)
        ) as b on b.id_akun = a.id_akun
        
        left JOIN (
          SELECT c.id_akun , sum(c.debit) as debit, sum(c.kredit) as kredit , MONTH(c.tgl) as bulan2, YEAR(c.tgl) as tahun2
           FROM jurnal_saldo_sebelum_penutup as c
           where Year(c.tgl) = ?
           group by c.id_akun , MONTH(c.tgl), YEAR(c.tgl)
        ) as c on c.id_akun = a.id_akun and b.tahun = c.tahun2 and b.bulan = c.bulan2
        where a.id_klasifikasi = '13';
        ", [$tahun, $tahun]);
        return $result;
    }
}
