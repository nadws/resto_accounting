<?php

namespace App\Http\Controllers;

use App\Imports\BahanImport;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class BahanController extends Controller
{
    protected $bahan;

    public function __construct()
    {
        $this->bahan = DB::select("SELECT a.*, b.nm_satuan, c.nm_kategori,(d.debit - d.kredit) as stok
                        FROM tb_list_bahan a
                        JOIN tb_satuan b ON a.id_satuan = b.id_satuan
                        JOIN tb_kategori_bahan c ON a.id_kategori = c.id_kategori_bahan
                        LEFT join (
                            SELECT b.id_bahan, SUM(b.debit) as debit, sum(b.kredit) as kredit
                            FROM stok_bahan as b 
                            group by b.id_bahan
                        ) as d on d.id_bahan = a.id_list_bahan
                        ORDER BY a.id_list_bahan DESC
                    ");
    }
    public function singkron()
    {

        try {
            DB::beginTransaction();
            $tglAwal = "2024-01-01";
            $tglAkhir = now()->subDay()->format('Y-m-d');
            $id_lokasi = app('id_lokasi'); // Ganti dengan id lokasi yang sesuai
            $kode = "BHNKLR";

            $currentDate = Carbon::parse($tglAwal);
            $endDate = Carbon::parse($tglAkhir);
            // Cek apakah stok untuk tanggal kemarin sudah diimport
            while ($currentDate <= $endDate) {
                $tgl = $currentDate->format('Y-m-d');

                // Cek apakah stok untuk tanggal ini sudah diimport
                $cekSudahImport = DB::table('stok_bahan')->where([['tgl', $tgl],['invoice', 'like', "%$kode%"]])->exists();
                $cekSudahImportItem = DB::table('penjualan_peritem')->where('tgl', $tgl)->exists();

                // Jika belum diimport, lakukan import
                if (!$cekSudahImport) {
                    $response = Http::get("https://ptagafood.com/api/menu?id_lokasi=$id_lokasi&tgl1=$tgl&tgl2=$tgl");
                    $invoice = $response['data']['menu'] ?? null;
                    $invos = json_decode(json_encode($invoice));

                    $invo = DB::selectOne("SELECT max(a.urutan) as urutan FROM stok_bahan as a WHERE a.invoice LIKE '%$kode%'");
                    DB::table('penjualan_peritem')->where('tgl',$tgl)->delete();
                    $invoice = empty($invo->urutan) ? 1001 : $invo->urutan + 1;
                    foreach ($invos as $i) {
                        $resep = DB::table('resep')->where('id_menu', $i->id_menu)->get();
                        DB::table('penjualan_peritem')->insert([
                            'id_menu' => $i->id_menu,
                            'qty' => $i->qty,
                            'tgl' => $tgl,
                        ]);
                        

                        foreach ($resep as $r) {
                            DB::table('stok_bahan')->insert([
                                'id_bahan' => $r->id_bahan,
                                'id_menu' => $i->id_menu,
                                'invoice' => "$kode-$invoice",
                                'urutan' => $invoice,
                                'tgl' => $tgl,
                                'debit' => 0,
                                'kredit' => $r->qty * $i->qty,
                                'admin' => auth()->user()->name,
                            ]);
                        }
                    }

                }

                // Tambah satu hari ke tanggal saat ini
                $currentDate->addDay();
            }
            DB::commit();
            return redirect()->route('sinkron.index')->with('sukses', 'Data Berhasil diimport');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('sinkron.index')->with('error', 'Data GAGAL : ' . $e);
        }
    }
    public function singkronPalsu()
    {

        DB::beginTransaction();
        try {
            $id_lokasi = app('id_lokasi');
            $tgl = date('Y-m-d', strtotime('- 1 days'));


            // $tgl = date('Y-m-d', strtotime('- 1 days'));
            $response = Http::get("https://ptagafood.com/api/menu?id_lokasi=$id_lokasi&tgl1=$tgl&tgl2=$tgl");
            $invoice = $response['data']['menu'] ?? null;
            $invo = json_decode(json_encode($invoice));
            $kode = "BHNKLR";
            $cekSudahImport = DB::table('stok_bahan')->where([['tgl', $tgl]])->first();
            if (empty($cekSudahImport)) {
                foreach ($invo as $i) {
                    $resep = DB::table('resep')->where('id_menu', $i->id_menu)->get();

                    $invo = DB::selectOne("SELECT max(a.urutan) as urutan
                    FROM stok_bahan as a WHERE a.invoice LIKE '%$kode%'
                    ");

                    $invoice = empty($invo->urutan) ? 1001 : $invo->urutan + 1;

                    foreach ($resep as $r) {
                        DB::table('stok_bahan')->insert([
                            'id_bahan' => $r->id_bahan,
                            'invoice' => "$kode-$invoice",
                            'urutan' => $invoice,
                            'tgl' => $tgl,
                            'debit' => 0,
                            'kredit' => $r->qty * $i->qty,
                            'admin' => auth()->user()->name,
                        ]);
                    }
                }
            }
            DB::commit();
            return redirect()->route('sinkron.index')->with('sukses', 'Data Berhasil diimport');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('sinkron.index')->with('error', 'Data GAGAL : ' . $e);
        }
    }
    public function index()
    {
        $data = [
            'title' => 'Bahan dan Barang Makanan',
            'satuan' => DB::table('tb_satuan')->get(),
            'kategori' => DB::table('tb_kategori_bahan')->get(),
            'bahan' => $this->bahan
        ];
        return view('persediaan.bahan_makanan.index', $data);
    }

    public function save(Request $r)
    {

        $id = DB::table('tb_list_bahan')->insertGetId([
            'nm_bahan' => $r->nm_bahan,
            'id_satuan' => $r->satuan_id,
            'id_kategori' => $r->kategori_id,
            'admin' => auth()->user()->name ?? 'tamu',
            'tgl' => date('Y-m-d')
        ]);

        $stok = (int) str()->remove(',', $r->stok);
        $ttl_rp = (int) str()->remove(',', $r->ttl_rp);
        if ($stok > 0) {
            $invo = DB::selectOne("SELECT max(a.urutan) as urutan
            FROM stok_bahan as a  WHERE a.invoice LIKE '%BHNMSK%'
            ");


            $invoice = empty($invo->urutan) ? 1001 : $invo->urutan + 1;
            DB::table('stok_bahan')->insert([
                'id_bahan' => $id,
                'invoice' => "BHNMSK-$invoice",
                'urutan' => $invoice,
                'tgl' => date('Y-m-d'),
                'kredit' => 0,
                'debit' => $stok,
                'rupiah' => $ttl_rp,
                'admin' => auth()->user()->name ?? 'tamu',
            ]);
        }
        return redirect()->route('bahan.index')->with('sukses', 'Data Berhasil ditambahkan');
    }

    public function opname(Request $r)
    {

        $data = [
            'title' => 'Opname Bahan dan Barang Makanan',
            'satuan' => DB::table('tb_satuan')->get(),
            'kategori' => DB::table('tb_kategori_bahan')->get(),
            'bahan' => $this->bahan
        ];
        return view('persediaan.bahan_makanan.opname', $data);
    }

    public function save_opname(Request $r)
    {
        $invo = DB::selectOne("SELECT max(a.urutan) as urutan
        FROM stok_bahan as a  WHERE a.invoice LIKE '%BHNOPN%'
        ");

        if (empty($invo->urutan)) {
            $invoice = '1001';
        } else {
            $invoice = $invo->urutan + 1;
        }
        for ($i = 0; $i < count($r->id_bahan); $i++) {
            $stok_program = (int) str()->remove(',', $r->stok_program[$i]);
            $stok_aktual = (int) str()->remove(',', $r->stok_aktual[$i]);
            $total = $stok_program - $stok_aktual;
            if ($total == 0) {
                continue;
            }
            if ($total < 0) {
                DB::table('stok_bahan')->insert([
                    'id_bahan' => $r->id_bahan[$i],
                    'invoice' => "BHNOPN-$invoice",
                    'urutan' => $invoice,
                    'tgl' => date('Y-m-d'),
                    'debit' => $total * -1,
                    'program' => $stok_program,
                    'kredit' => 0,
                    'admin' => auth()->user()->name,
                ]);
            } else {
                DB::table('stok_bahan')->insert([
                    'id_bahan' => $r->id_bahan[$i],
                    'invoice' => "BHNOPN-$invoice",
                    'urutan' => $invoice,
                    'tgl' => date('Y-m-d'),
                    'kredit' => $total,
                    'program' => $stok_program,

                    'debit' => 0,
                    'admin' => auth()->user()->name,
                ]);
            }
        }
        return redirect()->route('bahan.opname')->with('sukses', 'Data Berhasil diopname');
    }

    public function template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle('A1:D4')
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        $style = [
            'borders' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ],
            ],
        ];
        $sheet
            ->setCellValue('A1', 'nm_bahan')
            ->setCellValue('B1', 'satuan_id')
            ->setCellValue('C1', 'kategori_id');

        $sheet
            ->setCellValue('F1', 'id satuan')
            ->setCellValue('G1', 'nama satuan');
        $row = 2;
        $tbSatuan = DB::table('tb_satuan')->get();
        foreach ($tbSatuan as $d) {
            $sheet
                ->setCellValue("F$row", $d->id_satuan)
                ->setCellValue("G$row", $d->nm_satuan);
            $row++;
        }
        $sheet->getStyle('F1:G' . $row - 1)->applyFromArray($style);
        $sheet
            ->setCellValue('J1', 'id kategori')
            ->setCellValue('K1', 'nama kategori');
        $row = 2;
        $tbKategori = DB::table('tb_kategori_bahan')->get();
        foreach ($tbKategori as $d) {
            $sheet
                ->setCellValue("J$row", $d->id_kategori_bahan)
                ->setCellValue("K$row", $d->nm_kategori);
            $row++;
        }
        $sheet->getStyle('J1:K' . $row - 1)->applyFromArray($style);

        $writer = new Xlsx($spreadsheet);


        $lokasi = app('id_lokasi') == 1 ? 'Takemori' : 'Soondobu';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Data Bahan ' . strtoupper($lokasi) . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }
    public function import(Request $r)
    {
        Excel::import(new BahanImport, $r->file('file'));
        return redirect()->route('bahan.index')->with('sukses', 'Data berhasil import');
    }

    public function load_edit(Request $r)
    {
        $data = [
            'satuan' => DB::table('tb_satuan')->get(),
            'kategori' => DB::table('tb_kategori_bahan')->get(),
            'bahan' => DB::table('tb_list_bahan')->where('id_list_bahan', $r->id_bahan)->first()
        ];
        return view('persediaan.bahan_makanan.edit', $data);
    }

    public function update(Request $r)
    {
        DB::table('tb_list_bahan')->where('id_list_bahan', $r->id_bahan)->update([
            'nm_bahan' => $r->nm_bahan,
            'id_satuan' => $r->satuan_id,
            'id_kategori' => $r->kategori_id,
        ]);
        return redirect()->route('bahan.index')->with('sukses', 'Data Berhasil ditambahkan');
    }

    public function delete($id)
    {
        DB::table('tb_list_bahan')->where('id_list_bahan', $id)->delete();
        DB::table('stok_bahan')->where('id_bahan', $id)->delete();
        return redirect()->route('bahan.index')->with('sukses', 'Data Berhasil dihapus');
    }

    public function stok()
    {
        $data = [
            'title' => 'Stok Masuk',
            'invoice' => DB::select("SELECT a.tgl, a.invoice, sum(a.debit) as stok, a.admin
            FROM stok_bahan as a 
            where a.debit != 0 AND a.invoice LIKE '%BHNMSK%'
            group by a.invoice
            "),
        ];
        return view('persediaan.bahan_makanan.stok', $data);
    }

    public function stok_add()
    {
        $invo = DB::selectOne("SELECT max(a.urutan) as urutan
        FROM stok_bahan as a WHERE a.invoice LIKE '%BHNMSK%'
        ");

        if (empty($invo->urutan)) {
            $invoice = '1001';
        } else {
            $invoice = $invo->urutan + 1;
        }
        $data = [
            'title' => 'Stok Masuk',
            'invoice' => $invoice,
            'satuan' => DB::table('tb_satuan')->get(),
            'kategori' => DB::table('tb_kategori_bahan')->get(),
        ];
        return view('persediaan.bahan_makanan.stok_add', $data);
    }

    public function stok_tbh_baris(Request $r)
    {
        $data = [
            'count' => $r->count,
        ];
        return view('persediaan.bahan_makanan.stok_tbh_baris', $data);
    }

    public function load_produk_stok()
    {
        $atk = $this->bahan;

        $html = "<select name='id_bahan[]' class='select2_add pilihProduk'>";
        $html .= "<option value=''>Pilih Produk</option>";

        foreach ($atk as $a) {
            $html .= "<option value='{$a->id_list_bahan}'>{$a->nm_bahan} ({$a->nm_satuan})</option>";
        }

        // $html .= "<option value='tambah'>+ tambah baru</option></select>";
        return $html;
    }

    public function save_stk_masuk(Request $r)
    {
        for ($i = 0; $i < count($r->id_bahan); $i++) {
            $debit = (int) str()->remove(',', $r->debit[$i]);
            $total_rp = (int) str()->remove(',', $r->total_rp[$i]);

            DB::table('stok_bahan')->insert([
                'id_bahan' => $r->id_bahan[$i],
                'invoice' => "BHNMSK-$r->invoice",
                'urutan' => $r->invoice,
                'tgl' => date('Y-m-d'),
                'kredit' => 0,
                'debit' => $debit,
                'rupiah' => $total_rp,
                'admin' => auth()->user()->name,
            ]);
        }

        return redirect()->route('bahan.stok')->with('sukses', 'Data Berhasil ditambahkan');
    }

    public function history(Request $r)
    {
        
        $tgl = tanggalFilter($r);
        $tgl1 = $tgl['tgl1'];
        $tgl2 = $tgl['tgl2'];

        $stokMasuk = DB::select("SELECT 
        a.invoice,
        a.debit,
        a.kredit,
        b.nm_bahan,
        a.tgl
        FROM `stok_bahan` as a
        JOIN tb_list_bahan as b on a.id_bahan = b.id_list_bahan
        where a.invoice NOT LIKE '%BHNOPN%' AND a.tgl BETWEEN '$tgl1' AND '$tgl2'");


        $stokOpname = DB::select("SELECT 
                a.invoice,
                a.debit,
                a.kredit,
                a.program,
                b.nm_bahan,
                a.tgl
                FROM `stok_bahan` as a
                JOIN tb_list_bahan as b on a.id_bahan = b.id_list_bahan
                where a.invoice LIKE '%BHNOPN%'  AND a.tgl BETWEEN '$tgl1' AND '$tgl2'");

        $data = [
            'title' =>  'History Stok',
            'stokMasuk' => $stokMasuk,
            'stokOpname' => $stokOpname,
        ];
        return view('persediaan.bahan_makanan.history', $data);
    }

    public function kategori()
    {
        $data = [
            'title' => 'Kategori Bahan Makanan',
            'kategori' => DB::table('tb_kategori_bahan')->orderBy('id_kategori_bahan', 'DESC')->get()
        ];
        return view('persediaan.bahan_makanan.kategori', $data);
    }
    public function kategori_create(Request $r)
    {
        for ($i = 0; $i < count($r->nm_kategori); $i++) {
            DB::table('tb_kategori_bahan')->insert([
                'nm_kategori' => $r->nm_kategori[$i]
            ]);
        }
        return redirect()->route('bahan.kategori')->with('sukses', 'Data Berhasil ditambahkan');
    }

    public function kategori_hapus($id)
    {
        DB::table('tb_kategori_bahan')->where('id_kategori_bahan', $id)->delete();
        return redirect()->route('bahan.kategori')->with('sukses', 'Data Berhasil ditambahkan');
    }
}
