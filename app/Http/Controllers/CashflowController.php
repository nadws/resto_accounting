<?php

namespace App\Http\Controllers;

use App\Models\CashflowModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class CashflowController extends Controller
{
    public function index(Request $r)
    {
        if (empty($r->tahun)) {
            $tahun = date('Y');
        } else {
            $tahun = $r->tahun;
        }
        $id_klasifikasi = ['5', '6', '7'];
        $pendapatan = CashflowModel::cashflow_uangmasuk_setahun('1', $id_klasifikasi, $tahun);

        $id_klasifikasi2 = ['18'];
        $hutang = CashflowModel::cashflow_uangmasuk_setahun('4', $id_klasifikasi2, $tahun);


        $data = [];
        foreach ($pendapatan as $transaction) {
            $month = date('F', strtotime("{$transaction->tahun}-{$transaction->bulan}-01"));

            // Ubah bulan dan tahun menjadi format yang benar
            $nominal = $transaction->debit; // Menghitung nominal

            // Menambahkan data akun dan nominal ke struktur data
            if (!isset($data[$transaction->id_akun])) {
                $data[$transaction->id_akun] = [
                    'January' => 0,
                    'February' => 0,
                    'March' => 0,
                    'April' => 0,
                    'May' => 0,
                    'June' => 0,
                    'July' => 0,
                    'August' => 0,
                    'September' => 0,
                    'October' => 0,
                    'November' => 0,
                    'December' => 0,
                ];
            }
            // Menambahkan data nominal ke struktur data
            $data[$transaction->id_akun][$month] = $nominal;
        }
        $data2 = [];
        foreach ($hutang as $transaction) {
            $month = date('F', strtotime("{$transaction->tahun}-{$transaction->bulan}-01"));

            // Ubah bulan dan tahun menjadi format yang benar
            $nominal = $transaction->debit; // Menghitung nominal

            // Menambahkan data akun dan nominal ke struktur data
            if (!isset($data2[$transaction->id_akun])) {
                $data2[$transaction->id_akun] = [
                    'January' => 0,
                    'February' => 0,
                    'March' => 0,
                    'April' => 0,
                    'May' => 0,
                    'June' => 0,
                    'July' => 0,
                    'August' => 0,
                    'September' => 0,
                    'October' => 0,
                    'November' => 0,
                    'December' => 0,
                ];
            }
            // Menambahkan data nominal ke struktur data
            $data2[$transaction->id_akun][$month] = $nominal;
        }
        $datas = [
            'cahsflow' => 'tes',
            'tahun' => DB::select("SELECT YEAR(a.tgl) as tahun FROM jurnal as a where YEAR(a.tgl) != 0 group by YEAR(a.tgl);"),
            'thn' => $tahun
        ];
        return view('dashboard.cashflow.index', compact('data', 'data2'), $datas);
    }
}
