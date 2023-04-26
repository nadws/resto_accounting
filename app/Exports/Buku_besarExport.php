<?php

namespace App\Exports;

use App\Models\Jurnal;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromCollection;

class Buku_besarExport implements FromView, WithEvents
{
    protected $tgl1;
    protected $tgl2;
    protected $id_akun;

    public function __construct($tgl1, $tgl2, $id_akun, $totalrow)
    {
        $this->tgl1 = $tgl1;
        $this->tgl2 = $tgl2;
        $this->id_akun = $id_akun;
        $this->totalrow = $totalrow;
    }

    public function view(): View
    {

        $jurnal = DB::select("SELECT d.ket as ket2, a.ket, a.tgl,a.id_akun, d.nm_akun, a.no_nota, a.debit, a.kredit, a.saldo FROM `jurnal` as a
        LEFT JOIN (
            SELECT j.no_nota, j.id_akun, GROUP_CONCAT(DISTINCT j.ket SEPARATOR ', ') as ket, GROUP_CONCAT(DISTINCT b.nm_akun SEPARATOR ', ') as nm_akun 
            FROM jurnal as j
            LEFT JOIN akun as b ON b.id_akun = j.id_akun
            WHERE j.debit > 0
            GROUP BY j.no_nota
        ) d ON a.no_nota = d.no_nota AND d.id_akun != a.id_akun
        WHERE a.id_akun = '$this->id_akun' and a.tgl between '$this->tgl1' and '$this->tgl2'
        order by a.saldo DESC, a.id_jurnal ASC");

        return view('exports.detail_bukubesar', [
            'detail' => $jurnal
        ]);
    }



    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $totalrow = $this->totalrow + 1;
                $cellRange = 'A1:H1';
                // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->setAutoFilter($cellRange);
                $event->sheet->getStyle('A1:H1')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'font' => [
                        'name'  =>  'Calibri',
                        'size'  =>  10,
                        'bold' => true
                    ]
                ]);
                $event->sheet->getStyle('A2:H' . $totalrow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'font' => [
                        'name'  =>  'Calibri',
                        'size'  =>  10,
                        'bold' => false
                    ]
                ]);
            },
        ];
    }
}
