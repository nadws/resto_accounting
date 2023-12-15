<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AtkController extends Controller
{
    function index(Request $r)
    {
        $data = [
            'title' => 'Data ATK',
            'atk' => DB::select("SELECT a.id_atk, a.cfm, a.nm_atk, sum(b.debit - b.kredit) as stok, c.nm_satuan, d.nm_kategori
            FROM atk as a 
            left join stok_atk as b on b.id_atk = a.id_atk
            left join tb_satuan as c on c.id_satuan = a.id_satuan
            left join kategori_atk as d on d.id_kategori = a.id_kategori
            group by a.id_atk
            "),
            'satuan' => DB::table('tb_satuan')->get(),
            'kategori' => DB::table('kategori_atk')->get()
        ];
        return view('persediaan.atk.index', $data);
    }

    function save(Request $r)
    {
        $fotoPath = null;
        if ($r->hasFile('foto')) {
            $fotoPath = $r->file('foto')->store('foto_atk', 'public');
        }

        // Simpan data ke dalam database
        DB::table('atk')->insert([
            'cfm' => $r->cfm,
            'id_kategori' => $r->kategori_id,
            'nm_atk' => $r->nm_atk,
            'id_satuan' => $r->satuan_id,
            'foto' => $fotoPath,
            'kontrol_stok' => $r->kontrol_stok,
        ]);

        // Redirect atau berikan respons sesuai kebutuhan
        return redirect()->back()->with('success', 'Data ATK berhasil disimpan');
    }

    function stok_masuk(Request $r)
    {

        $data = [
            'title' => 'Stok Masuk',
            'invoice' => DB::select("SELECT a.tgl, a.invoice, sum(a.debit) as stok, a.admin
            FROM stok_atk as a 
            where a.debit != 0
            group by a.invoice
            "),
            'atk' => DB::table('atk')->get()
        ];
        return view('persediaan.atk.stok_masuk', $data);
    }

    function tmbh_stok(Request $r)
    {
        $invo = DB::selectOne("SELECT max(a.urutan) as urutan
        FROM stok_atk as a 
        ");

        if (empty($invo->urutan)) {
            $invoice = '1001';
        } else {
            $invoice = $invo->urutan + 1;
        }

        $data = [
            'title' => 'Tambah stok masuk',
            'invoice' => $invoice,
            'atk' => DB::table('atk')->join('tb_satuan', 'tb_satuan.id_satuan', 'atk.id_satuan')->get()
        ];
        return view('persediaan.atk.add_stk_masuk', $data);
    }

    function save_stk_masuk(Request $r)
    {
        $invo = DB::selectOne("SELECT max(a.urutan) as urutan
        FROM stok_atk as a 
        ");

        if (empty($invo->urutan)) {
            $invoice = '1001';
        } else {
            $invoice = $invo->urutan + 1;
        }

        for ($x = 0; $x < count($r->id_atk); $x++) {
            if (empty($r->id_atk[$x])) {
                # code...
            } else {
                $data = [
                    'invoice' => 'STKM-' . $invoice,    
                    'tgl' => $r->tgl,
                    'id_atk' => $r->id_atk[$x],
                    'debit' => $r->debit[$x],
                    'rupiah' => $r->total_rp[$x],
                    'urutan' => $invoice,
                    'admin' => auth()->user()->name
                ];
                DB::table('stok_atk')->insert($data);
            }
        }

        return redirect()->route('atk.stok_masuk')->with('sukses', 'Data berhasil ditambahkan');
    }
}
