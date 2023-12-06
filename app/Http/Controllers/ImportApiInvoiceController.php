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
        $tgl1 = $r->tgl1;
        $tgl2 = $r->tgl2;

        $response = Http::get("https://ptagafood.com/api/invoice_nanda?id_lokasi=$id_lokasi&id_distribusi=$id_distribusi&tgl1=$tgl1&tgl2=$tgl2");
        $invoice = $response['data']['invoice'] ?? null;
        $invo = json_decode(json_encode($invoice));


        foreach ($invo as $i) {
            $data = [
                'no_nota' => $i->no_order,
                'tgl' => $i->tgl_transaksi,
                'id_buku' => '1',
                'ket' => 'penjualan resto',
                'debit' => 0,
                'kredit' => $i->sub_total + $i->stk,
                'id_akun' => '1'
            ];
            DB::table('jurnal')->insert($data);
            if ($i->voucher != 0) {
                $data = [
                    'no_nota' => $i->no_order,
                    'tgl' => $i->tgl_transaksi,
                    'id_buku' => '1',
                    'ket' => 'penjualan resto',
                    'debit' => $i->voucher,
                    'kredit' => 0,
                    'id_akun' => '6'
                ];
                DB::table('jurnal')->insert($data);
            }
            if ($i->service != 0) {
                $data = [
                    'no_nota' => $i->no_order,
                    'tgl' => $i->tgl_transaksi,
                    'id_buku' => '1',
                    'ket' => 'penjualan resto',
                    'debit' => 0,
                    'kredit' => $i->service,
                    'id_akun' => '2'
                ];
                DB::table('jurnal')->insert($data);
                $data = [
                    'no_nota' => $i->no_order,
                    'tgl' => $i->tgl_transaksi,
                    'id_buku' => '1',
                    'ket' => 'penjualan resto',
                    'debit' => $i->service,
                    'kredit' => 0,
                    'id_akun' => '8'
                ];
                DB::table('jurnal')->insert($data);
            }
            if ($i->tax != 0) {
                $data = [
                    'no_nota' => $i->no_order,
                    'tgl' => $i->tgl_transaksi,
                    'id_buku' => '1',
                    'ket' => 'penjualan resto',
                    'debit' => 0,
                    'kredit' => $i->tax,
                    'id_akun' => '3'
                ];
                DB::table('jurnal')->insert($data);
                $data = [
                    'no_nota' => $i->no_order,
                    'tgl' => $i->tgl_transaksi,
                    'id_buku' => '1',
                    'ket' => 'penjualan resto',
                    'debit' => $i->tax,
                    'kredit' => 0,
                    'id_akun' => '9'
                ];
                DB::table('jurnal')->insert($data);
            }
            if ($i->rounding != 0) {
                $data = [
                    'no_nota' => $i->no_order,
                    'tgl' => $i->tgl_transaksi,
                    'id_buku' => '1',
                    'ket' => 'penjualan resto',
                    'debit' => 0,
                    'kredit' => $i->rounding,
                    'id_akun' => '4'
                ];
                DB::table('jurnal')->insert($data);
            }
            if ($i->discount != 0) {
                $data = [
                    'no_nota' => $i->no_order,
                    'tgl' => $i->tgl_transaksi,
                    'id_buku' => '1',
                    'ket' => 'penjualan resto',
                    'debit' => $i->discount,
                    'kredit' => 0,
                    'id_akun' => '7'
                ];
                DB::table('jurnal')->insert($data);
            }
        }
    }
}
