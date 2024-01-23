<?php

namespace App\Http\Controllers;

use Exception;
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
            'ekspedisi' => DB::table('tb_ekspedisi')->get(),
            'akunPembayaran' => DB::table('akun')->where('id_klasifikasi', 5)->get(),
            'postCenter' => DB::table('tb_post_center')->where('id_akun', 42)->get(),
        ];
        return $tb[$data];
    }

    public function getQueryPo($where = false, $nota = '')
    {
        $adaWhere = $where ? "where a.no_nota = $nota GROUP by a.no_nota" : '';
        $db = $where ? 'selectOne' : 'select';
        return $po = DB::$db("SELECT 
        a.no_nota,a.tgl,a.nota_jurnal,a.nota_jurnal_pengiriman,a.nota_manual,a.admin,a.sub_total,a.ttl_pajak,a.status,a.biaya,a.uang_muka,a.catatan,a.diskon,a.potongan,
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
        return DB::select("SELECT a.pajak,a.diskon,b.nm_bahan,c.nm_satuan,a.ttl_rp,a.qty,a.ket FROM `po_item` as a
        JOIN tb_list_bahan as b on a.id_barang = b.id_list_bahan
        join tb_satuan as c on a.id_satuan_beli = c.id_satuan
        WHERE a.no_nota = '$no_nota'");
    }

    public function getCountTbhBayar($no_nota = null)
    {
        return DB::table('po_biaya_tambahan as a')
            ->join('tb_ekspedisi as b', 'a.id_ekspedisi', 'b.id_ekspedisi')
            ->where('a.no_nota', $no_nota)->count();
    }

    public function index(Request $r)
    {
        $po = $this->getQueryPo();
        $tgl1 = $r->tgl1 ?? date('Y-m-d');
        $tgl2 = $r->tgl2 ?? date('Y-m-t');

        $getCountTbhBayar = function ($no_nota) {
            return DB::table('po_biaya_tambahan as a')
                ->join('tb_ekspedisi as b', 'a.id_ekspedisi', 'b.id_ekspedisi')
                ->where('a.no_nota', $no_nota)->count();
        };
        $data = [
            'title' => 'Pesanan Pembelian',
            'po' => $po,
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'getCountTbhBayar' => $getCountTbhBayar,
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
        DB::beginTransaction();
        try {
            $tgl = $r->tgl;
            $no_nota = $r->no_nota;
            $nota_manual = $r->nota_manual;
            $id_suplier = $r->id_suplier;
            $catatan = $r->catatan;
            $id_akun = $r->id_akun;
            $id_akun_pembayaran = $r->id_akun_pembayaran;
            $id_akun_selisih = $r->id_akun_selisih;
            $pajakSum = $r->pajakSum;
            $biaya = str()->remove(',', $r->biaya);
            $uangMuka = str()->remove(',', $r->uangMuka);
            $selisihRp = str()->remove(',', $r->selisihRp);
            $bDiskon = str()->remove(',', $r->bDiskon);
            $bPotonganDiskon = str()->remove(',', $r->bPotonganDiskon);
            $admin = auth()->user()->name;
            $ttlRpSum = 0;
            for ($i = 0; $i < count($r->id_bahan); $i++) {
                $idBahan = $r->id_bahan[$i];
                $qty = str()->remove(',', $r->qty[$i]);
                $id_satuan_beli = $r->id_satuan_beli[$i];
                $persen = $r->persen[$i] ?? 0;
                $rp_satuan = str()->remove(',', $r->rp_satuan[$i]);
                $ttl_rp = str()->remove(',', $r->ttl_rp[$i]);
                $pajak = $r->pajak[$i];

                $ket = $r->ket[$i];
                DB::table('po_item')->insert([
                    'id_barang' => $idBahan,
                    'qty' => $qty,
                    'ttl_rp' => $ttl_rp,
                    'ket' => $ket,
                    'id_satuan_beli' => $id_satuan_beli,
                    'diskon' => $persen ?? 0,
                    'pajak' => $pajak ?? 0,
                    'no_nota' => $no_nota,
                    'admin' => $admin,
                ]);

                $ttlRpSum += $ttl_rp;
            }

            DB::table('po_pembelian')->insert([
                'tgl' => $tgl,
                'no_nota' => $no_nota,
                'nota_manual' => $nota_manual,
                'id_suplier' => $id_suplier,
                'catatan' => $catatan,
                'status' => 'disetujui',
                'biaya' => $biaya,
                'uang_muka' => $uangMuka,
                'potongan' => $bPotonganDiskon,
                'diskon' => $bDiskon,
                'ttl_pajak' => $pajakSum,
                'sub_total' => $ttlRpSum + $selisihRp,
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
            if ($biaya) {
                DB::table('po_transaksi')->insert([
                    'id_akun' => $id_akun_pembayaran,
                    'no_nota' => $no_nota,
                    'nm_transaksi' => "BIAYA TAMBAHAN PO/$no_nota",
                    'jumlah' => $biaya,
                    'tgl_transaksi' => $tgl,
                    'catatan' => $catatan,
                    'status' => 'dp',
                    'admin' => $admin,
                ]);
            }
            if ($selisihRp) {
                DB::table('po_transaksi')->insert([
                    'id_akun' => $id_akun_selisih,
                    'no_nota' => $no_nota,
                    'nm_transaksi' => "SELISIH NOTA PO/$no_nota",
                    'jumlah' => $selisihRp,
                    'tgl_transaksi' => $tgl,
                    'catatan' => $catatan,
                    'status' => 'selisih',
                    'admin' => $admin,
                ]);
            }
            DB::commit();
            return redirect()->route('po.detail', $no_nota)->with('sukses', 'Berhasil tambah pesanan pembelian');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('po.add')->with('error', $e->getMessage());
        }
    }

    public function load_bukukan(Request $r)
    {
        $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '2')->first();


        if (empty($max)) {
            $nota_t = '1000';
        } else {
            $nota_t = $max->nomor_nota + 1;
        }
        $no_nota = $r->no_nota;
        $data = [
            'no_nota'   => $no_nota,
            'cekSudahPernahBayar' => $this->getCekSudahPernahBayar($no_nota),
            'transaksi' => $this->getHistoryQuery(true, $no_nota),
            'poDetail' => $this->getQueryPo(true, $no_nota),
            'getBarang' => $this->getBarangPerNota($no_nota),
            'bayarSum' => $this->getSumBayarDitransaksi($no_nota),
            'nota_jurnal' => $nota_t,
            'akun' => $this->getDataMaster('akunPembayaran'),
            'postCenter' => $this->getDataMaster('postCenter')
        ];
        return view('datamenu.po.bukukan', $data);
    }
    public function load_bukukan_pengiriman(Request $r)
    {
        $max = DB::table('notas')->latest('nomor_nota')->where('id_buku', '2')->first();


        if (empty($max)) {
            $nota_t = '1000';
        } else {
            $nota_t = $max->nomor_nota + 1;
        }
        $no_nota = $r->no_nota;
        $data = [
            'no_nota'   => $no_nota,
            'cekSudahPernahBayar' => $this->getCekSudahPernahBayar($no_nota),
            'transaksi' => $this->getHistoryQuery(true, $no_nota),
            'poDetail' => $this->getQueryPo(true, $no_nota),
            'getBarang' => $this->getBarangPerNota($no_nota),
            'bayarSum' => $this->getSumBayarDitransaksi($no_nota),
            'nota_jurnal' => $nota_t,
            'akun' => $this->getDataMaster('akunPembayaran'),
            'postCenter' => $this->getDataMaster('postCenter')
        ];
        return view('datamenu.po.bukukan_pengiriman', $data);
    }
    public function create_bukukan(Request $r)
    {
        DB::beginTransaction();
        try {

            $no_nota = $r->no_nota;
            $tgl = $r->tgl_jurnal;
            $ket = $r->ket;
            $nota_manual = $r->nota_manual;
            $nota_jurnal = $r->nota_jurnal;
            $id_post_center = $r->id_post_center;
            $totalKredit = $r->totalKredit;
            $totalDebit = $r->totalDebit;

            // masukan ke stok
            $getItem = DB::table('po_item')->where('no_nota', $no_nota)->get();

            $invo = DB::selectOne("SELECT max(a.urutan) as urutan FROM stok_bahan as a WHERE a.invoice LIKE '%BHNMSK%'");

            $invoice = empty($invo->urutan) ? '1001' : $invo->urutan + 1;

            foreach ($getItem as $d) {
                DB::table('stok_bahan')->insert([
                    'id_bahan' => $d->id_barang,
                    'invoice' => "BHNMSKPO-$no_nota",
                    'urutan' => $invoice,
                    'tgl' => $r->tgl_jurnal,
                    'kredit' => 0,
                    'debit' => $d->qty,
                    'rupiah' => $d->ttl_rp,
                    'admin' => auth()->user()->name,
                ]);
            }
            //  end stok---------------

            // masukan jurnal
            for ($i = 0; $i < count($r->id_akun); $i++) {
                $id_buku = 2;
                DB::table('notas')->insert(['nomor_nota' => $nota_jurnal, 'id_buku' => $id_buku]);

                $id_akun = $r->id_akun[$i];
                $debit = $r->debit[$i];
                $kredit = $r->kredit[$i];
                $id_post_center = $r->id_post_center[$i];

                $max_akun = DB::table('jurnal')->latest('urutan')->where('id_akun', $id_akun)->first();
                $akun = DB::table('akun')->where('id_akun', $id_akun)->first();
                $urutan = empty($max_akun) ? '1001' : ($max_akun->urutan == 0 ? '1001' : $max_akun->urutan + 1);
                $data = [
                    'tgl' => $tgl,
                    'no_nota' => 'TGHNPO-' . $nota_jurnal,
                    'id_akun' => $id_akun,
                    'no_dokumen' => $nota_manual,
                    'id_buku' => $id_buku,
                    'ket' => "dari po-$no_nota-$ket",
                    'debit' => $debit,
                    'kredit' => $kredit,
                    'admin' => auth()->user()->name,
                    'tgl_dokumen' => $tgl,
                    'id_proyek' => '',
                    'id_suplier' => '',
                    'no_urut' => $akun->inisial . '-' . $urutan,
                    'urutan' => $urutan,
                    'id_post_center' => $id_post_center
                ];
                DB::table('jurnal')->insert($data);
            }
            // end jurnal--------------

            DB::table('po_pembelian')->where('no_nota', $d->no_nota)->update(['nota_jurnal' => 'TGHNPO-' . $nota_jurnal]);

            // cek jika ada selisih debit kredit
            if ($totalDebit !== $totalKredit) {
                return redirect()->route('po.index')->with('error', 'Data GAGAL : debit kredit tidak sama');
            }

            DB::commit();
            return redirect()->route('po.index')->with('sukses', 'Data Berhasil masuk akun');
        } catch (Exception $e) {

            DB::rollBack();
            return redirect()->route('po.index')->with('error', $e->getMessage());
        }
    }
    public function create_bukukan_pengiriman(Request $r)
    {
        dd('pengirmana');
        DB::beginTransaction();
        try {

            $no_nota = $r->no_nota;
            $tgl = $r->tgl_jurnal;
            $ket = $r->ket;
            $nota_manual = $r->nota_manual;
            $nota_jurnal = $r->nota_jurnal;
            $id_post_center = $r->id_post_center;
            $totalKredit = $r->totalKredit;
            $totalDebit = $r->totalDebit;

            // masukan jurnal
            for ($i = 0; $i < count($r->id_akun); $i++) {
                $id_buku = 2;
                DB::table('notas')->insert(['nomor_nota' => $nota_jurnal, 'id_buku' => $id_buku]);

                $id_akun = $r->id_akun[$i];
                $debit = $r->debit[$i];
                $kredit = $r->kredit[$i];
                $id_post_center = $r->id_post_center[$i];

                $max_akun = DB::table('jurnal')->latest('urutan')->where('id_akun', $id_akun)->first();
                $akun = DB::table('akun')->where('id_akun', $id_akun)->first();
                $urutan = empty($max_akun) ? '1001' : ($max_akun->urutan == 0 ? '1001' : $max_akun->urutan + 1);
                $data = [
                    'tgl' => $tgl,
                    'no_nota' => 'TGHNPO-' . $nota_jurnal,
                    'id_akun' => $id_akun,
                    'no_dokumen' => $nota_manual,
                    'id_buku' => $id_buku,
                    'ket' => "PENGIRIMAN PO/$no_nota-$ket",
                    'debit' => $debit,
                    'kredit' => $kredit,
                    'admin' => auth()->user()->name,
                    'tgl_dokumen' => $tgl,
                    'id_proyek' => '',
                    'id_suplier' => '',
                    'no_urut' => $akun->inisial . '-' . $urutan,
                    'urutan' => $urutan,
                    'id_post_center' => $id_post_center
                ];
                DB::table('jurnal')->insert($data);
            }
            // end jurnal--------------

            DB::table('po_pembelian')->where('no_nota', $no_nota)->update(['nota_jurnal_pengiriman' => 'TGHNPO-' . $nota_jurnal]);

            // cek jika ada selisih debit kredit
            if ($totalDebit !== $totalKredit) {
                return redirect()->route('po.index')->with('error', 'Data GAGAL : debit kredit tidak sama');
            }

            DB::commit();
            return redirect()->route('po.index')->with('sukses', 'Data Berhasil masuk akun');
        } catch (Exception $e) {

            DB::rollBack();
            return redirect()->route('po.index')->with('error', $e->getMessage());
        }
    }
    public function delete($no_nota)
    {
        $del = [
            'po_item',
            'po_pembelian',
            'po_transaksi',
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

    public function getTbhBiayaDiluarNota($no_nota)
    {
        return DB::table('po_biaya_tambahan as a')
            ->join('tb_ekspedisi as b', 'a.id_ekspedisi', 'b.id_ekspedisi')
            ->join('akun as c', 'a.id_akun', 'c.id_akun')
            ->where('a.no_nota', $no_nota)->get();
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
            'bayarSum' => $this->getSumBayarDitransaksi($no_nota),
            'getTbhBiaya' => $this->getTbhBiayaDiluarNota($no_nota),
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

    public function tambahan($no_nota)
    {
        $poDetail = $this->getQueryPo(true, $no_nota);
        $getBarang = $this->getBarangPerNota($no_nota);

        $data = [
            'title' => 'Tambahan Pembayaran',
            'poDetail' => $poDetail,
            'getBarang' => $getBarang,
            'no_nota' => $no_nota,
            'akunPembayaran' => $this->getDataMaster('akunPembayaran'),
            'bayarSum' => $this->getSumBayarDitransaksi($no_nota),
            'cekSudahPernahBayar' => $this->getCekSudahPernahBayar($no_nota),
        ];
        return view('datamenu.po.tambahan.index', $data);
    }

    public function load_selectEkspedisi()
    {
        $ekspedisi = $this->getDataMaster('ekspedisi');
        $dropdownHtml = '<select required name="id_ekspedisi" id="" class="select23 selectEkspedisi">';
        $dropdownHtml .= '<option value="">Pilih ekspedisi</option>';

        foreach ($ekspedisi as $s) {
            $dropdownHtml .= '<option value="' . $s->id_ekspedisi . '">' . strtoupper($s->nm_ekspedisi) . '</option>';
        }

        $dropdownHtml .= '<option value="tambah">Tambah Baru</option>';
        $dropdownHtml .= '</select>';
        return $dropdownHtml;
    }



    public function create_biaya_tambahan(Request $r)
    {
        $tbhBayarRp = str()->remove(',', $r->tbhBayarRp);
        $tgl = $r->tgl;
        $no_nota = $r->no_nota;
        $nota_manual = $r->nota_manual;
        $id_ekspedisi = $r->id_ekspedisi;
        $no_resi = $r->no_resi;
        $catatan = $r->catatan;
        $id_akun = $r->id_akun;

        DB::table('po_biaya_tambahan')->insert([
            'no_nota' => $no_nota,
            'tgl_tambahan' => $tgl,
            'nota_manual' => $nota_manual,
            'id_ekspedisi' => $id_ekspedisi,
            'no_resi' => $no_resi,
            'ket' => $catatan,
            'id_akun' => $id_akun,
            'ttl_rp_biaya' => $tbhBayarRp,
            'admin' => auth()->user()->name,
        ]);
        return redirect()->route('po.detail', $no_nota)->with('sukses', 'Data Berhasil ditambahkan');
    }
}
