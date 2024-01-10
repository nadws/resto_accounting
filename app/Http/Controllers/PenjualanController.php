<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class PenjualanController extends Controller
{
    public function getPenjualan($tgl1, $tgl2)
    {
        return DB::table('penjualan_peritem as a')
            ->join('tb_menu as b', 'a.id_menu', 'b.id_menu')
            ->join('tb_station as c', 'c.id_station', 'b.id_station')
            ->whereBetween('a.tgl', [$tgl1, $tgl2])
            ->orderBy('b.nm_menu', 'ASC')
            ->get();
    }
    public function getHistory($group = true, $tgl1, $tgl2)
    {
        $kredit = $group ? "SUM(a.kredit) as kredit" : "a.kredit";
        $groupBy = $group ? "GROUP BY a.id_bahan, a.tgl" : "";
        
        return DB::select("SELECT 
            a.invoice,
            $kredit,
            b.nm_bahan,
            a.id_bahan,
            c.nm_menu,
        e.nm_satuan,
            a.tgl
            FROM `stok_bahan` as a
            JOIN tb_list_bahan as b on a.id_bahan = b.id_list_bahan
            JOIN tb_menu as c on a.id_menu = c.id_menu
        JOIN tb_satuan as e on b.id_satuan = e.id_satuan
            where a.invoice LIKE '%KLR%' AND a.tgl BETWEEN '$tgl1' AND '$tgl2' $groupBy");
    }
    public function index(Request $r)
    {
        $id_lokasi = app('id_lokasi');
        $tglKemarin = now()->subDay()->format('Y-m-d');
        $tgl1 = $r->tgl1 ?? $tglKemarin;
        $tgl2 = $r->tgl2 ?? $tglKemarin;
        $datas = $this->getPenjualan($tgl1, $tgl2);
        $data  = [
            'title' => 'Data Penjualan',
            'datas' => $datas,
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
        ];
        return view('datamenu.penjualan.index', $data);
    }

    public function history(Request $r)
    {
        $tglKemarin = now()->subDay()->format('Y-m-d');
        $tgl1 = $r->tgl1 ?? $tglKemarin;
        $tgl2 = $r->tgl2 ?? $tglKemarin;
        $history = $this->getHistory(false, $tgl1, $tgl2);
        $data = [
            'title' => 'History bahan keluar',
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'history' => $history
        ];
        return view('datamenu.penjualan.history', $data);
    }

    public function detail(Request $r)
    {
        $detail = DB::select("SELECT 
        a.kredit,
        sum.kredit as ttl,
        b.nm_bahan,
        a.id_bahan,
        c.id_menu,
        c.nm_menu,
        e.nm_satuan,
        d.qty,
        terjual.terjual,
        a.tgl
        FROM `stok_bahan` as a
        JOIN tb_list_bahan as b on a.id_bahan = b.id_list_bahan
        JOIN tb_menu as c on a.id_menu = c.id_menu
        join resep as d on c.id_menu = d.id_menu AND d.id_bahan = b.id_list_bahan
        JOIN tb_satuan as e on b.id_satuan = e.id_satuan
        JOIN (
            select sum(kredit) as kredit,id_bahan from stok_bahan where invoice like '%KLR%'  AND tgl = '$r->tgl' group by id_bahan,tgl
        ) sum on a.id_bahan = sum.id_bahan
        join (
            select id_menu,tgl,sum(qty) as terjual from penjualan_peritem GROUP BY id_menu,tgl
        ) terjual on terjual.id_menu = c.id_menu and terjual.tgl = a.tgl
        where a.invoice LIKE '%KLR%' AND a.tgl = '$r->tgl' AND a.id_bahan = '$r->id_bahan';");
        $data = [
            'history' => $detail
        ];
        return view('datamenu.penjualan.detail', $data);
    }

    public function export(Request $r)
    {
        $tgl1 = $r->tgl1;
        $tgl2 = $r->tgl2;

        $style_atas = array(
            'font' => [
                'bold' => true, // Mengatur teks menjadi tebal
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ],
        );

        $style = [
            'borders' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ],
            ],
        ];

        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Penjualan');

        $koloms = [
            "a" => '#',
            "b" => 'Tanggal',
            "c" => 'Station',
            "d" => 'Nama Menu',
            "e" => 'Qty'
        ];
        $sheet1->getStyle("A1:E1")->applyFromArray($style_atas);
        foreach ($koloms as $kolom => $d) {
            $sheet1->setCellValue(strtoupper($kolom) . '1', $d);
        }
        $penjualan = $this->getPenjualan($tgl1, $tgl2);
        $row = 2;
        foreach ($penjualan as $i => $d) {
            $sheet1->setCellValue("A$row", $i + 1);
            $sheet1->setCellValue("B$row", $d->tgl);
            $sheet1->setCellValue("C$row", $d->nm_station);
            $sheet1->setCellValue("D$row", $d->nm_menu);
            $sheet1->setCellValue("E$row", $d->qty);
            $row++;
        }
        $sheet1->getStyle('A1:E' . $row - 1)->applyFromArray($style);

        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(1);
        $sheet2 = $spreadsheet->getActiveSheet(1);
        $sheet2->setTitle('History Bahan Keluar');
        $sheet2->getStyle("A1:E1")->applyFromArray($style_atas);

        $koloms = [
            "a" => '#',
            "b" => 'Tanggal',
            "c" => 'Nama Menu',
            "d" => 'Nama Bahan',
            "e" => 'Qty'
        ];
        $sheet2->getStyle("A1:E1")->applyFromArray($style_atas);
        foreach ($koloms as $kolom => $d) {
            $sheet2->setCellValue(strtoupper($kolom) . '1', $d);
        }

        $history = $this->getHistory(false, $tgl1, $tgl2);
        $row = 2;
        foreach ($history as $i => $d) {
            $sheet2->setCellValue("A$row", $i + 1);
            $sheet2->setCellValue("B$row", $d->tgl);
            $sheet2->setCellValue("C$row", $d->nm_menu);
            $sheet2->setCellValue("D$row", $d->nm_bahan);
            $sheet2->setCellValue("E$row", $d->kredit);
            $row++;
        }
        $sheet2->getStyle('A1:E' . $row - 1)->applyFromArray($style);

        $namafile = "Data Penjualan dan Bahan Keluar.xlsx";

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $namafile);
        header('Cache-Control: max-age=0');


        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }
}
