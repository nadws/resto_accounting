<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PoController extends Controller
{

    public function getDataMaster($data)
    {
        $tb = [
            'suplier' => DB::table('tb_suplier')->get(),
            'bahan' => DB::table('tb_list_bahan')->get(),
            'satuan' => DB::table('tb_satuan')->get(),
            'akunPembayaran' => DB::table('akun')->where('id_klasifikasi', 5)->get(),
        ];
        return $tb[$data];
    }

    public function getQueryPo($where = false, $nota = '')
    {
        $adaWhere = $where ? "where a.no_nota = $nota GROUP by a.no_nota" : '';
        $db = $where ? 'selectOne' : 'select';
        return $po = DB::$db("SELECT 
        a.no_nota,a.tgl,a.admin,a.sub_total,a.status,a.biaya,a.uang_muka,a.catatan,
        b.nm_suplier,b.npwp,b.alamat,b.telepon,b.email
        FROM po_pembelian a
        JOIN tb_suplier b ON a.id_suplier = b.id_suplier $adaWhere order by a.id_pembelian DESC");
    }
    public function getCekSudahPernahBayar($no_nota)
    {
        return DB::table('po_transaksi as a')
            ->join('akun as b', 'a.id_akun', 'b.id_akun')
            ->where('a.no_nota', $no_nota)->get();
    }
    public function getBarangPerNota($no_nota)
    {
        return DB::select("SELECT b.nm_bahan,c.nm_satuan,a.ttl_rp,a.qty,a.ket FROM `po_item` as a
        JOIN tb_list_bahan as b on a.id_barang = b.id_list_bahan
        join tb_satuan as c on a.id_satuan_beli = c.id_satuan
        WHERE a.no_nota = '$no_nota'");
    }

    public function index(Request $r)
    {
        $po = $this->getQueryPo();
        $tgl1 = $r->tgl1 ?? date('Y-m-d');
        $tgl2 = $r->tgl2 ?? date('Y-m-t');
        $data = [
            'title' => 'Pesanan Pembelian',
            'po' => $po,
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
        ];
        return view('datamenu.po.index', $data);
    }

    public function add()
    {
        $no_po = DB::table('po_pembelian')->orderBy('id_pembelian', 'DESC')->first();
        $no_po = !$no_po ? 1001 : $no_po->no_nota + 1;
        $data = [
            'title' => 'Tambah Pesanan Pembelian',
            'no_po' => $no_po,
            'suplier' => $this->getDataMaster('suplier'),
            'bahan' => $this->getDataMaster('bahan'),
            'satuan' => $this->getDataMaster('satuan'),
            'akunPembayaran' => $this->getDataMaster('akunPembayaran')
        ];
        return view('datamenu.po.add', $data);
    }

    public function tbh_baris(Request $r)
    {
        $data = [
            'count' => $r->count,
            'suplier' => $this->getDataMaster('suplier'),
            'bahan' => $this->getDataMaster('bahan'),
            'satuan' => $this->getDataMaster('satuan'),
        ];
        return view('datamenu.po.tbh_baris', $data);
    }

    public function create(Request $r)
    {
        try {
            DB::beginTransaction();
            $tgl = $r->tgl;
            $no_nota = $r->no_nota;
            $id_suplier = $r->id_suplier;
            $catatan = $r->catatan;
            $id_akun = $r->id_akun;
            $biaya = str()->remove(',', $r->biaya);
            $uangMuka = str()->remove(',', $r->uangMuka);

            $admin = auth()->user()->name;
            $ttlRpSum = 0;
            for ($i = 0; $i < count($r->id_bahan); $i++) {
                $idBahan = $r->id_bahan[$i];
                $qty = str()->remove(',', $r->qty[$i]);
                $id_satuan_beli = $r->id_satuan_beli[$i];
                $rp_satuan = str()->remove(',', $r->rp_satuan[$i]);
                $ttl_rp = str()->remove(',', $r->ttl_rp[$i]);

                $ket = $r->ket[$i];
                DB::table('po_item')->insert([
                    'id_barang' => $idBahan,
                    'qty' => $qty,
                    'ttl_rp' => $ttl_rp,
                    'ket' => $ket,
                    'id_satuan_beli' => $id_satuan_beli,
                    'no_nota' => $no_nota,
                    'admin' => $admin,
                ]);

                $ttlRpSum += $ttl_rp;
            }

            DB::table('po_pembelian')->insert([
                'tgl' => $tgl,
                'no_nota' => $no_nota,
                'id_suplier' => $id_suplier,
                'catatan' => $catatan,
                'status' => 'disetujui',
                'biaya' => $biaya,
                'uang_muka' => $uangMuka,
                'sub_total' => $ttlRpSum,
                'admin' => $admin,
            ]);
            if ($uangMuka) {
                DB::table('po_transaksi')->insert([
                    'id_akun' => $id_akun,
                    'no_nota' => $no_nota,
                    'nm_transaksi' => "DP PO/$no_nota",
                    'jumlah' => $uangMuka,
                    'tgl_transaksi' => $tgl,
                    'catatan' => $catatan,
                    'status' => 'dp',
                    'admin' => $admin,
                ]);
            }
            DB::commit();
            return redirect()->route('po.detail', $no_nota)->with('sukses', 'Berhasil tambah pesanan pembelian');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('po.add')->with('error', 'Gagal : ' . $e);
        }
    }

    public function delete($no_nota)
    {
        $del = [
            'po_item',
            'po_pembelian',
        ];
        foreach ($del as $d) {
            DB::table($d)->where('no_nota', $no_nota)->delete();
        }
        return redirect()->route('po.index')->with('sukses', 'Data Berhasil dihapus');
    }

    public function print($no_nota)
    {
        $poDetail = $this->getQueryPo(true, $no_nota);
        $getBarang = $this->getBarangPerNota($no_nota);

        $data = [
            'title' => 'Pesanan Pembelian',
            'poDetail' => $poDetail,
            'getBarang' => $getBarang,
            'bayarSum' => $this->getSumBayarDitransaksi($no_nota),
            'cekSudahPernahBayar' => $this->getCekSudahPernahBayar($no_nota),
        ];
        return view('datamenu.po.print', $data);
    }

    public function detail($no_nota)
    {
        $poDetail = $this->getQueryPo(true, $no_nota);
        $getBarang = $this->getBarangPerNota($no_nota);

        $data = [
            'title' => 'Detail Pesanan Pembelian ' . "PO/$no_nota",
            'poDetail' => $poDetail,
            'getBarang' => $getBarang,
            'akunPembayaran' => $this->getDataMaster('akunPembayaran'),
            'cekSudahPernahBayar' => $this->getCekSudahPernahBayar($no_nota),
            'bayarSum' => $this->getSumBayarDitransaksi($no_nota),
        ];
        return view('datamenu.po.detail', $data);
    }

    public function getSumBayarDitransaksi($nota)
    {
        return DB::selectOne("SELECT sum(jumlah) as ttlBayar FROM po_transaksi WHERE no_nota = '$nota'");
    }

    public function bayar(Request $r)
    {
        $ttl_dibayar = str()->remove(',', $r->ttl_dibayar);
        $tgl_transaksi = $r->tgl_transaksi;
        $no_nota = $r->no_nota;
        $id_akun = $r->id_akun;
        $catatan = $r->catatan;
        $sisaTagihan = $r->sisaTagihan;
        $admin = auth()->user()->name;
        $status = $sisaTagihan < $ttl_dibayar && $sisaTagihan != $ttl_dibayar ? 'hutang' : 'lunas';
        DB::table('po_transaksi')->insert([
            'id_akun' => $id_akun,
            'no_nota' => $no_nota,
            'nm_transaksi' => "Pembayaran PO/$no_nota",
            'jumlah' => $ttl_dibayar,
            'tgl_transaksi' => $tgl_transaksi,
            'catatan' => $catatan,
            'status' => $status,
            'admin' => $admin,
        ]);
        DB::table('po_pembelian')->where('no_nota', $no_nota)->update(['status' => $status == 'lunas' ? 'selesai' : 'hutang']);

        return redirect()->route('po.detail', $no_nota)->with('sukses', 'Data Berhasil Dibayar');
    }

    public function getHistoryQuery($where = false, $nota = '')
    {
        $adaWhere = $where ? "where a.no_nota = $nota" : '';
        $db = $where ? 'selectOne' : 'select';

        return DB::$db("SELECT
        a.tgl_transaksi,
        a.no_nota,
        a.nm_transaksi,
        c.nm_suplier,c.npwp,c.alamat,c.telepon,c.email,
        a.jumlah,
        d.nm_akun
        FROM po_transaksi as a
        JOIN po_pembelian as b on a.no_nota = b.no_nota
        JOIN tb_suplier as c on b.id_suplier = c.id_suplier
        JOIN akun as d on a.id_akun = d.id_akun $adaWhere ORDER BY a.id_transaksi DESC");
    }

    public function history(Request $r)
    {
        $tgl1 = $r->tgl1 ?? date('Y-m-d');
        $tgl2 = $r->tgl2 ?? date('Y-m-t');
        $transaksi = $this->getHistoryQuery();
            
        $data = [
            'title' => 'History Pembelian',
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'transaksi' => $transaksi,
        ];

        return view('datamenu.po.history', $data);
    }

    public function transaksi_print($no_nota)
    {
        $transaksi = $this->getHistoryQuery(true, $no_nota);
            
        $data = [
            'title' => 'Pemberitahuan Pembayaran',
            'transaksi' => $transaksi,
        ];

        return view('datamenu.po.transaksi_print', $data);
    }
}
