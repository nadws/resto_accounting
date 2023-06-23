<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OpnamemtdController extends Controller
{
    public function index(Request $r)
    {
        $max = DB::table('invoice_telur')->latest('urutan')->first();

        if (empty($max)) {
            $nota_t = '1000';
        } else {
            $nota_t = $max->urutan + 1;
        }
        $data = [
            'title' => 'Opname Telur',
            'nota' => $nota_t,
            'produk' => DB::table('telur_produk')->get()

        ];
        return view('opname_telur_mtd.index', $data);
    }

    public function save_opname_telur_mtd(Request $r)
    {
        $urutan_opname = DB::selectOne("SELECT count(a.nota_transfer) as urutan
        FROM (SELECT a.nota_transfer
              FROM stok_telur as a where a.ket = 'Opname' group by a.nota_transfer) as a;");
        if (empty($urutan_opname) || $urutan_opname->urutan == '0') {
            $urutan = '1001';
        } else {
            $urutan = '1001' + $urutan_opname->urutan;
        }

        $max_customer = DB::table('invoice_telur')->latest('urutan_customer')->where('id_customer', '3')->first();

        if (empty($max_customer)) {
            $urutan_cus = '1';
        } else {
            $urutan_cus = $max_customer->urutan_customer + 1;
        }
        DB::table('stok_telur')->where(['opname' => 'T', 'id_gudang' => '1'])->update(['opname' => 'Y']);
        for ($x = 0; $x < count($r->id_telur); $x++) {
            $data = [
                'id_telur' => $r->id_telur[$x],
                'tgl' => $r->tgl,
                'pcs' => $r->pcs[$x],
                'kg' => $r->kg[$x],
                'admin' => Auth::user()->name,
                'id_gudang' => '1',
                'ket' => 'Opname',
                'nota_transfer' => 'ST-' . $urutan,
                'opname' => 'T'
            ];
            DB::table('stok_telur')->insert($data);

            if ($r->pcs_selisih[$x] + $r->kg_selisih[$x] == 0) {
                # code...
            } else {
                $data = [
                    'id_customer' => '3',
                    'tgl' => $r->tgl,
                    'no_nota' => 'ST-' . $urutan,
                    'urutan_customer' => $urutan_cus,
                    'pcs' => $r->pcs_selisih[$x],
                    'kg' => $r->kg_selisih[$x],
                    'admin' => Auth::user()->name,
                    'lokasi' => 'opname',
                    'id_produk' => $r->id_telur[$x],
                ];
                DB::table('invoice_telur')->insert($data);
            }
        }

        return redirect()->route('bayar_opname', ['no_nota' => 'ST-' . $urutan])->with('sukses', 'Data berhasil ditambahkan');
    }

    public function bayar_opname(Request $r)
    {
        $data = [
            'title' => 'Pembayran Opname',
            'produk' => DB::table('telur_produk')->get(),
            'customer' => DB::table('customer')->get(),
            'akun' => DB::table('akun')->whereIn('id_klasifikasi', ['1', '7'])->get(),
            'nota' => $r->no_nota,
            'invoice2' => DB::selectOne("SELECT a.urutan, a.urutan_customer, a.tgl, a.id_customer, a.id_produk, a.tipe, a.driver, sum(a.total_rp) as total_rp FROM invoice_telur as a where a.no_nota='$r->no_nota'"),
            'invoice' => DB::table('invoice_telur')->where('no_nota', $r->no_nota)->get(),
        ];
        return view('opname_telur_mtd.bayar_opname', $data);
    }

    public function save_bayar_opname(Request $r)
    {
        for ($x = 0; $x < count($r->id_invoice_telur); $x++) {
            # code...
            $data = [
                'rp_satuan' => $r->rp_satuan[$x],
                'total_rp' => $r->total_rp[$x],
            ];
            DB::table('invoice_telur')->where('id_invoice_telur', $r->id_invoice_telur[$x])->update($data);
        }
        return redirect()->route('opnamemtd')->with('sukses', 'Data berhasil di opname');
    }
}
