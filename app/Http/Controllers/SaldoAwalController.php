<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Buku_besarExport;
use App\Exports\KlasifikasiExport;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class SaldoAwalController extends Controller
{
    function index(Request $r)
    {
        $data =  [
            'title' => 'Saldo Awal',
            'akun' => DB::select("SELECT a.* , b.debit, b.kredit, b.penutup
            FROM akun as a 
            left join (
            SELECT b.id_akun, b.penutup, sum(b.debit) as debit , sum(b.kredit) as kredit FROM jurnal as b 
            where b.saldo = 'Y'
            group by b.id_akun
            ) as b on b.id_akun = a.id_akun
            order by a.kode_akun ASC
            "),
            'tgl_saldo' => DB::SelectOne("SELECT a.tgl FROM jurnal as a where a.saldo = 'Y'")
        ];
        return view("pembukuan.saldo.index", $data);
    }

    public function saveSaldo(Request $r)
    {
        $id_akun = $r->id_akun;
        DB::table('jurnal')->where('saldo', 'Y')->delete();
        for ($i = 0; $i < count($id_akun); $i++) {
            $data = [
                'id_akun' => $r->id_akun[$i],
                'debit' => $r->debit[$i],
                'kredit' => $r->kredit[$i],
                'ket' => 'Saldo Awal',
                'id_buku' => '1',
                'tgl' => date('Y-12-31'),
                'admin' => Auth::user()->name,
                'saldo' => 'Y'
            ];
            DB::table('jurnal')->insert($data);
        }
        return redirect()->route('saldoawal.index')->with('sukses', 'Data berhasil ditambahkan');
    }

    public function export_saldo(Request $r)
    {
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
        $sheet1->setTitle('Saldo Awal');


        $sheet1->getStyle("A1:P1")->applyFromArray($style_atas);

        $sheet1->setCellValue('A1', '#');
        $sheet1->setCellValue('B1', 'Kode Akun');
        $sheet1->setCellValue('C1', 'Nama Akun');
        $sheet1->setCellValue('D1', 'Debit');
        $sheet1->setCellValue('E1', 'Kredit');
        $kolom = 2;

        $akun = DB::select("SELECT a.* , b.debit, b.kredit, b.penutup
            FROM akun as a 
            left join (
            SELECT b.id_akun, b.penutup, sum(b.debit) as debit , sum(b.kredit) as kredit FROM jurnal as b 
            where b.saldo = 'Y'
            group by b.id_akun
            ) as b on b.id_akun = a.id_akun
            order by a.kode_akun ASC
            ");

        foreach ($akun as $no => $d) {
            $sheet1->setCellValue('A' . $kolom, $no + 1);
            $sheet1->setCellValue('B' . $kolom, $d->kode_akun);
            $sheet1->setCellValue('C' . $kolom, $d->nm_akun);
            $sheet1->setCellValue('D' . $kolom, empty($d->debit) ? '0' : $d->debit);
            $sheet1->setCellValue('E' . $kolom, empty($d->kredit) ? '0' : $d->kredit);
            $kolom++;
        }
        $sheet1->getStyle('A2:E' . $kolom - 1)->applyFromArray($style);
        $namafile = "Saldo Awal.xlsx";

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $namafile);
        header('Cache-Control: max-age=0');


        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }
}
