<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengorderanController extends Controller
{
    public function getDataMaster($data)
    {
        $tb = [
            'kategori' => DB::table('tb_kategori_bahan')->orderBy('nm_kategori', 'ASC')->get(),
            'satuan' => DB::table('tb_satuan')->orderBy('nm_satuan', 'ASC')->get(),
        ];
        return $tb[$data];
    }
    public function getItemPoGroup()
    {
        $po = DB::select("SELECT count(*) as ttlBahan,a.tgl_order,a.no_nota, sum(qty) as qty,a.admin, sum(a.ttl_rp) as ttl_rp FROM `po_item` as a GROUP BY a.no_nota ");
        return $po;
    }
    public function load_selectSatuan()
    {
        $satuan = $this->getDataMaster('satuan');
        $dropdownHtml = '<select required name="id_satuan_beli[]" id="" class="select23 selectSatuan">';
        $dropdownHtml .= '<option value="">Pilih Satuan Beli</option>';

        foreach ($satuan as $s) {
            $dropdownHtml .= '<option value="' . $s->id_satuan . '">' . strtoupper($s->nm_satuan) . '</option>';
        }

        $dropdownHtml .= '<option value="tambah">Tambah Baru</option>';
        $dropdownHtml .= '</select>';
        return $dropdownHtml;
    }

    public function createSatuan(Request $r)
    {
        DB::table('tb_satuan')->insert(['nm_satuan' => $r->nm_satuan]);
    }
    public function getItemPo($no_nota)
    {
        $po = DB::select("SELECT d.nm_kategori,a.admin,a.no_nota,b.id_list_bahan as id_bahan,b.nm_bahan,c.nm_satuan,a.qty,a.tgl_order 
        FROM `po_item` as a
        JOIN tb_list_bahan as b on a.id_barang = b.id_list_bahan
        JOIN tb_satuan as c on c.id_satuan = b.id_satuan
        JOIN tb_kategori_bahan as d on b.id_kategori = d.id_kategori_bahan
        where a.no_nota = '$no_nota';");
        return $po;
    }
    public function index()
    {
        $po = $this->getItemPoGroup();
        $data = [
            'title' => 'Pengorderan Bahan & Makanan',
            'po' => $po,
        ];
        return view('datamenu.pengorderan.index', $data);
    }

    public function add()
    {
        $no_po = DB::table('po_item')->orderBy('id_item_po', 'DESC')->first();
        $no_po = !$no_po ? 1001 : $no_po->no_nota + 1;
        $data = [
            'title' => 'Tambah Pengorderan Bahan & Makanan',
            'kategori' => $this->getDataMaster('kategori'),
            'no_po' => $no_po,
            'satuanBeli' => $this->getDataMaster('satuan')
        ];
        return view('datamenu.pengorderan.add', $data);
    }

    public function create(Request $r)
    {
        try {
            DB::beginTransaction();
            $no_nota = $r->no_nota;
            $ket = $r->ket;
            $tgl_order = $r->tgl;
            $admin = auth()->user()->name;
            for ($i = 0; $i < count($r->qty); $i++) {
                $qty = $r->qty[$i];
                $id_bahan = $r->id_bahan[$i];
                $id_satuan_beli = $r->id_satuan_beli[$i];

                DB::table('po_item')->insert([
                    'id_barang' => $id_bahan,
                    'qty' => $qty,
                    'no_nota' => $no_nota,
                    'tgl_order' => $tgl_order,
                    'admin' => $admin,
                    'id_satuan_beli' => $id_satuan_beli,
                    'ket' => $ket,
                ]);
            }
            DB::commit();
            return redirect()->route('pengorderan.index')->with('sukses', 'Data Berhasil ditambahkan');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('pengorderan.add')->with('error', $e->getMessage())->withInput();
        }
    }

    public function print($no_nota)
    {
        $po = $this->getItemPo($no_nota);
        $data = [
            'title' => 'Cetak Pengorderan',
            'poDetail' => $po,
            'kategori' => $this->getDataMaster('kategori')
        ];
        return view('datamenu.pengorderan.print', $data);
    }
}
