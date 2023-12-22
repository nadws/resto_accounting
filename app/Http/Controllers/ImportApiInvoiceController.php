<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportApiInvoiceController extends Controller
{
    function invoice(Request $r)
    {
        $id_lokasi = $r->id_lokasi;
        $id_distribusi = $r->id_distribusi;
        // $selectedMonth = $r->selected_month;
        // $selectedYear = $r->selected_year; 

        // $firstDayOfMonth = "$selectedYear-$selectedMonth-01";
        // $lastDayOfMonth = date("Y-m-t", strtotime($firstDayOfMonth));

        $tgl1 = $r->tgl1;
        $tgl2 = $r->tgl2;

        if (!empty($r->id_lokasi)) {
            $response = Http::get("https://ptagafood.com/api/invoice_nanda?id_lokasi=$id_lokasi&id_distribusi=$id_distribusi&tgl1=$tgl1&tgl2=$tgl2");
            $invoice = $response['data']['invoice'] ?? null;
            $invo = json_decode(json_encode($invoice));
            foreach ($invo as $i) {
                $max_akun = DB::table('jurnal')->latest('urutan')->where('id_akun', $i->id_akun_pembayaran)->first();
                $akun = DB::table('akun')->where('id_akun', $i->id_akun_pembayaran)->first();
                $urutan = empty($max_akun) ? '1001' : ($max_akun->urutan == 0 ? '1001' : $max_akun->urutan + 1);

                $data = [
                    'no_nota' => $i->no_order,
                    'tgl' => $i->tgl_transaksi,
                    'id_buku' => '3',
                    'ket' => 'penjualan resto',
                    'debit' => $i->nominal,
                    'kredit' => 0,
                    'id_akun' => $i->id_akun_pembayaran,
                    'no_urut' => $akun->inisial . '-' . $urutan,
                    'urutan' => $urutan,
                ];
                DB::table('jurnal')->insert($data);


                if ($i->rounding != 0) {
                    $max_akun = DB::table('jurnal')->latest('urutan')->where('id_akun', 19)->first();
                    $akun = DB::table('akun')->where('id_akun', 19)->first();
                    $urutan = empty($max_akun) ? '1001' : ($max_akun->urutan == 0 ? '1001' : $max_akun->urutan + 1);
                    $data = [
                        'no_nota' => $i->no_order,
                        'tgl' => $i->tgl_transaksi,
                        'id_buku' => '3',
                        'ket' => 'penjualan resto',
                        'debit' => 0,
                        'kredit' => $i->rounding,
                        'id_akun' => '19',
                        'no_urut' => $akun->inisial . '-' . $urutan,
                        'urutan' => $urutan,
                    ];
                    DB::table('jurnal')->insert($data);
                }
                if ($i->discount != 0) {
                    $max_akun = DB::table('jurnal')->latest('urutan')->where('id_akun', 20)->first();
                    $akun = DB::table('akun')->where('id_akun', 20)->first();
                    $urutan = empty($max_akun) ? '1001' : ($max_akun->urutan == 0 ? '1001' : $max_akun->urutan + 1);
                    $data = [
                        'no_nota' => $i->no_order,
                        'tgl' => $i->tgl_transaksi,
                        'id_buku' => '3',
                        'ket' => 'penjualan resto',
                        'debit' => $i->discount + $i->diskon_bank,
                        'kredit' => 0,
                        'id_akun' => '20',
                        'no_urut' => $akun->inisial . '-' . $urutan,
                        'urutan' => $urutan,
                    ];
                    DB::table('jurnal')->insert($data);
                }
                if ($i->tax != 0) {
                    $max_akun = DB::table('jurnal')->latest('urutan')->where('id_akun', 18)->first();
                    $akun = DB::table('akun')->where('id_akun', 18)->first();
                    $urutan = empty($max_akun) ? '1001' : ($max_akun->urutan == 0 ? '1001' : $max_akun->urutan + 1);
                    $data = [
                        'no_nota' => $i->no_order,
                        'tgl' => $i->tgl_transaksi,
                        'id_buku' => '3',
                        'ket' => 'penjualan resto',
                        'debit' => 0,
                        'kredit' => $i->tax,
                        'id_akun' => '18',
                        'no_urut' => $akun->inisial . '-' . $urutan,
                        'urutan' => $urutan,
                    ];
                    DB::table('jurnal')->insert($data);
                }
                if ($i->service != 0) {
                    $max_akun = DB::table('jurnal')->latest('urutan')->where('id_akun', 17)->first();
                    $akun = DB::table('akun')->where('id_akun', 17)->first();
                    $urutan = empty($max_akun) ? '1001' : ($max_akun->urutan == 0 ? '1001' : $max_akun->urutan + 1);
                    $data = [
                        'no_nota' => $i->no_order,
                        'tgl' => $i->tgl_transaksi,
                        'id_buku' => '3',
                        'ket' => 'penjualan resto',
                        'debit' => 0,
                        'kredit' => $i->service,
                        'id_akun' => '17',
                        'no_urut' => $akun->inisial . '-' . $urutan,
                        'urutan' => $urutan,
                    ];
                    DB::table('jurnal')->insert($data);
                }
                if ($i->voucher != 0) {
                    $max_akun = DB::table('jurnal')->latest('urutan')->where('id_akun', 17)->first();
                    $akun = DB::table('akun')->where('id_akun', 17)->first();
                    $urutan = empty($max_akun) ? '1001' : ($max_akun->urutan == 0 ? '1001' : $max_akun->urutan + 1);
                    $data = [
                        'no_nota' => $i->no_order,
                        'tgl' => $i->tgl_transaksi,
                        'id_buku' => '3',
                        'ket' => 'penjualan resto',
                        'debit' => $i->voucher,
                        'kredit' => 0,
                        'id_akun' => '21',
                        'no_urut' => $akun->inisial . '-' . $urutan,
                        'urutan' => $urutan,
                    ];
                    DB::table('jurnal')->insert($data);
                }

                $max_akun = DB::table('jurnal')->latest('urutan')->where('id_akun', 16)->first();
                $akun = DB::table('akun')->where('id_akun', 16)->first();
                $urutan = empty($max_akun) ? '1001' : ($max_akun->urutan == 0 ? '1001' : $max_akun->urutan + 1);
                $data = [
                    'no_nota' => $i->no_order,
                    'tgl' => $i->tgl_transaksi,
                    'id_buku' => '3',
                    'ket' => 'penjualan resto',
                    'debit' => 0,
                    'kredit' => $i->penjualanKurangVoucherDiskon + $i->stk,
                    'id_akun' => '16',
                    'no_urut' => $akun->inisial . '-' . $urutan,
                    'urutan' => $urutan,
                ];
                DB::table('jurnal')->insert($data);
            }
        }
    }
}
