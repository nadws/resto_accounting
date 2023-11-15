<?php

namespace App\Http\Controllers;

use App\Models\Profit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfitController extends Controller
{
    function prosesTransaksi($transactions, $type)
    {
        $data = [];

        foreach ($transactions as $transaction) {
            $month = date('F', strtotime("{$transaction->tahun}-{$transaction->bulan}-01"));

            // Ubah bulan dan tahun menjadi format yang benar
            switch ($type) {
                case 'pendapatan':
                    $nominal = $transaction->kredit;
                    break;
                case 'disusutkan':
                    $nominal = $transaction->debit;
                default:
                    $nominal = $transaction->debit - $transaction->kredit;
                    break;
            }

            // Menambahkan data akun dan nominal ke struktur data
            if (!isset($data[$transaction->id_akun])) {
                $data[$transaction->id_akun] = [
                    'Januari' => 0,
                    'Februari' => 0,
                    'Maret' => 0,
                    'April' => 0,
                    'Mei' => 0,
                    'Juni' => 0,
                    'Juli' => 0,
                    'Agustus' => 0,
                    'September' => 0,
                    'Oktober' => 0,
                    'November' => 0,
                    'Desember' => 0,
                ];
            }

            // Menambahkan data nominal ke struktur data
            $data[$transaction->id_akun][$month] = $nominal;
        }

        return $data;
    }

    public function index(Request $r)
    {

        $tahun = $r->tahun ?? date('Y');

        $pendapatan = Profit::pendapatan_setahun($tahun, '1');
        $biaya = Profit::pendapatan_setahun($tahun, '2');
        $biaya_penyesuaian = Profit::biaya_penyesuaian_setahun($tahun);
        $biaya_disusutkan = Profit::biaya_disusutkan_setahun($tahun);


        $data = $this->prosesTransaksi($pendapatan, 'pendapatan');
        $data2 = $this->prosesTransaksi($biaya, 'biaya');
        $data3 = $this->prosesTransaksi($biaya_penyesuaian, 'biaya');
        $data4 = $this->prosesTransaksi($biaya_disusutkan, 'disusutkan');
        $data = [
            'title' => 'Profit',
            'thn' => $tahun,
            'bulan' => DB::table('bulan')->get(),
            'data' => $data,
            'data2' => $data2,
            'data3' => $data3,
            'data4' => $data4,
            'tahun' => DB::table('tahun')->get()
        ];
        return view('dashboard.profit.index', $data);
    }

    public function createAkun(Request $r)
    {
        for ($i = 0; $i < count($r->nm_akun); $i++) {
            $data = [
                'kode_akun' => $r->kode_akun[$i],
                'nm_akun' => $r->nm_akun[$i],
                'id_klasifikasi' => $r->id_klasifikasi[$i],
            ];

            DB::table('akun')->insert($data);
        }
    }
}
