<?php

namespace App\Http\Controllers;

use App\Models\Profit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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

    public function loadListAkunProfit()
    {
        $data = [
            'akun' => DB::table('akun as a')
                ->join('klasifikasi_akun as b', 'a.id_klasifikasi', 'b.id_klasifikasi')
                ->whereIn('a.id_klasifikasi', [1, 2, 3, 4])->orderBy('a.id_klasifikasi', 'ASC')->get()
        ];
        return view('dashboard.profit.load_list_akun_profit', $data);
    }

    public function loadEdit(Request $r)
    {
        $getAkun = DB::table('akun as a')
            ->join('klasifikasi_akun as b', 'a.id_klasifikasi', 'b.id_klasifikasi')
            ->where('a.id_akun', $r->id_akun)
            ->first();
        $html = <<<HTML
            <div class="row">
                <input type="hidden" name="id_akun" value="{$getAkun->id_akun}">
                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="">Kode</label>
                        <input value="{$getAkun->kode_akun}" type="text" name="kode" class="form-control">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="">Inisial</label>
                        <input value="{$getAkun->inisial}" type="text" name="inisial" class="form-control">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="">Nama Akun</label>
                        <input value="{$getAkun->nm_akun}" type="text" name="nm_akun" class="form-control">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="">Klasifikasi</label>
                        <input readonly type="text" value="{$getAkun->nm_klasifikasi}" name="klasifikasi" class="form-control">
                    </div>
                </div>
            </div>
        HTML;

        return $html;
    }

    public function updateAkun(Request $r)
    {
        DB::table('akun')->where('id_akun', $r->id_akun)->update([
            'kode_akun' => $r->kode,
            'inisial' => $r->inisial,
            'nm_akun' => $r->nm_akun,
        ]);
    }

    public function hapusAkun(Request $r)
    {
        $cek = DB::table('jurnal')->where('id_akun', $r->id_akun)->first();
        if (!$cek) {
            DB::table('akun')->where('id_akun', $r->id_akun)->delete();
            $pesan = 'Data dihapus';
        }
        return $pesan ?? 'Gagal Hapus Akun karena ada dijurnal';
    }

    public function importLaporan()
    {

        $id_buku = 1;
        $akunTipe = [
            'pnjl' => 1,
            'rounding' => 9,
            'penjualanGojek' => 10,
            'penjualanStk' => 11,

            'pb1gojek' => 2,
            'pb1stk' => 6,
            'pb1dinein' => 7,
            'serviceCharge' => 8,
        ];
        $tglMulai = date('Y-01-1');
        $tglAkhir = date('Y-01-t');
        $currentDate = $tglMulai;
        while (strtotime($currentDate) <= strtotime($tglAkhir)) {
            $getLaporan = Http::get("https://ptagafood.com/api/laporan/1/$currentDate/$currentDate");
            $getLaporan = json_decode($getLaporan);

            // cek nul penjulaan
            $penjualanArray = (array) $getLaporan->penjualan;
            $isSemuaNolPnjl = empty(array_filter($penjualanArray, function ($value) {
                return $value != 0;
            }));

            // cek nul biaya
            $biayaArray = (array) $getLaporan->biaya;
            $isSemuaNolBiaya = empty(array_filter($biayaArray, function ($value) {
                return $value != 0;
            }));

            $getLatestNota = DB::table('jurnal')->latest('no_nota')->where('id_buku', 1)->first();
            $nota_t = empty($getLatestNota) ? 1000 : str()->remove('PNJL-', $getLatestNota->no_nota) + 1;

            $getLastBiayaNota = DB::table('jurnal')->latest('no_nota')->where('id_buku', 2)->first();
            $nota_t_b = empty($getLastBiayaNota) ? 1000 : str()->remove('BYA-', $getLastBiayaNota->no_nota) + 1;


            if (!$isSemuaNolPnjl) {
                // foreach ($getLaporan->penjualan as $i => $d) {

                //     $id_akun = $akunTipe[$i];
                //     $max_akun = DB::table('jurnal')->latest('urutan')->where('id_akun', $id_akun)->first();
                //     $akun = DB::table('akun')->where('id_akun', $id_akun)->first();
                //     $urutan = empty($max_akun) ? '1001' : ($max_akun->urutan == 0 ? '1001' : $max_akun->urutan + 1);

                //     $data = [
                //         'tgl' => $currentDate,
                //         'no_nota' => 'PNJL-' . $nota_t,
                //         'id_akun' => $id_akun,
                //         'no_dokumen' => '',
                //         'id_buku' => 1,
                //         'ket' => '',
                //         'debit' => 0,
                //         'kredit' => $d,
                //         'admin' => 'import',
                //         'tgl_dokumen' => $currentDate,
                //         'id_proyek' => 0,
                //         'id_suplier' => 0,
                //         'no_urut' => $akun->inisial . '-' . $urutan,
                //         'urutan' => $urutan,
                //         'id_post_center' => 0
                //     ];
                //     DB::table('jurnal')->insert($data);
                // }

            }
            if (!$isSemuaNolBiaya) {
                foreach ($getLaporan->biaya as $i => $d) {

                    $id_akun = $akunTipe[$i];
                    $max_akun = DB::table('jurnal')->latest('urutan')->where('id_akun', $id_akun)->first();
                    $akun = DB::table('akun')->where('id_akun', $id_akun)->first();
                    $urutan = empty($max_akun) ? '1001' : ($max_akun->urutan == 0 ? '1001' : $max_akun->urutan + 1);

                    $data = [
                        'tgl' => $currentDate,
                        'no_nota' => 'BYA-' . $nota_t_b,
                        'id_akun' => $id_akun,
                        'no_dokumen' => '',
                        'id_buku' => 2,
                        'ket' => '',
                        'debit' => 0,
                        'kredit' => $d,
                        'admin' => 'import',
                        'tgl_dokumen' => $currentDate,
                        'id_proyek' => 0,
                        'id_suplier' => 0,
                        'no_urut' => $akun->inisial . '-' . $urutan,
                        'urutan' => $urutan,
                        'id_post_center' => 0
                    ];
                    DB::table('jurnal')->insert($data);
                }
            }
            $currentDate = date("Y-m-d", strtotime("+1 day", strtotime($currentDate)));
        }
    }
}
