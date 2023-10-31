<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
if (!function_exists('tanggalFilter')) {
    function tanggalFilter(Request $r)
    {
        $period = $r->period;
        $bulan = $r->bulan;
        $tahun = $r->tahun;
        $tgl1 = $r->tgl1;
        $tgl2 = $r->tgl2;

        $result = [];
        $today = date('Y-m-d');
        $firstDayOfMonth = date('Y-m-01');

        switch ($period) {
            case 'daily':
                $result = ['tgl1' => $today, 'tgl2' => $today];
                break;
            case 'weekly':
                $sixDaysAgo = date('Y-m-d', strtotime("-6 days"));
                $result = ['tgl1' => $sixDaysAgo, 'tgl2' => $today];
                break;
            case 'mounthly':
                $tglawal = "$tahun-$bulan-01";
                $tglakhir = "$tahun-$bulan-" . date('t', strtotime($tglawal));
                $result = ['tgl1' => $tglawal, 'tgl2' => date('Y-m-t', strtotime($tglakhir))];
                break;
            case 'costume':
                $result = ['tgl1' => $tgl1, 'tgl2' => $tgl2];
                break;
            case 'years':
                $tgl_awal = "$tahun-01-01";
                $tgl_akhir = "$tahun-12-31";
                $result = ['tgl1' => date('Y-m-01', strtotime($tgl_awal)), 'tgl2' => date('Y-m-t', strtotime($tgl_akhir))];
                break;
            default:
                $result = ['tgl1' => $firstDayOfMonth, 'tgl2' => date('Y-m-t')];
                break;
        }

        return $result;
    }
}

if (!function_exists('tanggal')) {
    function tanggal($tgl)
    {
        $date = explode("-", $tgl);
        $tahun = $date[0];
        $bulan = $date[1];
        $tanggal = $date[2];

        $nama_bulan = array(
            '01' => "Januari",
            '02' => "Februari",
            '03' => "Maret",
            '04' => "April",
            '05' => "Mei",
            '06' => "Juni",
            '07' => "Juli",
            '08' => "Agustus",
            '09' => "September",
            '10' => "Oktober",
            '11' => "November",
            '12' => "Desember"
        );

        $bulan = $nama_bulan[$bulan];

        $timestamp = strtotime($tgl);
        $hari = date("l", $timestamp); // "l" akan mengembalikan nama hari dalam bahasa Inggris

        $nama_hari = array(
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu'
        );

        $hari = $nama_hari[$hari];

        $strTanggal = "$hari, $tanggal $bulan $tahun";
        return $strTanggal;
    }
}

class SettingHal
{

    public static function akses($halaman, $id_user)
    {
        return DB::selectOne("SELECT a.*, b.id_permission_page FROM permission_button
        AS
        a
        LEFT JOIN (
        SELECT b.id_user, b.id_permission_button, b.id_permission_page FROM permission_perpage AS b
        WHERE b.id_user ='$id_user' AND b.permission_id = '$halaman'
        ) AS b ON b.id_permission_button = a.id_permission_button WHERE b.id_user = '$id_user'");
    }

    public static function btnHal($whereId, $id_user)
    {
        return DB::table('permission_perpage as a')
            ->join('permission_button as b', 'b.id_permission_button', 'a.id_permission_button')
            ->where([['a.id_permission_button', $whereId], ['a.id_user', $id_user]])
            ->first();
    }

    public static function btnSetHal($halaman, $id_user, $jenis)
    {
        return DB::select("SELECT a.*, b.id_permission_page FROM permission_button AS
        a
        LEFT JOIN (
        SELECT b.id_permission_button, b.id_permission_page FROM permission_perpage AS b
        WHERE b.id_user ='$id_user'
        ) AS b ON b.id_permission_button = a.id_permission_button
        WHERE a.jenis = '$jenis' AND a.permission_id = '$halaman'");
    }
}
