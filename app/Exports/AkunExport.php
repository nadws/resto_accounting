<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;


class AkunExport  implements FromView, WithEvents
{
    protected $data = [];

    public function __construct($data)
    {
        $this->data = [
            'tbl1' => $data['tbl1'],
            'row1' => $data['row1'],
            'tbl2' => $data['tbl2'],
            'row2' => $data['row2'],
        ];
    }

    public function view(): View
    {
        return view('akun.export', [
            'query' => $this->data['tbl1'],
            'klasifikasi' => $this->data['tbl2'],
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {

                $sheet = $event->sheet;

                $cellRange = 'A1:G1';
                $cellRangeLoop = 'A1:G' . $this->data['row1'] + 1;

                $cellRangeB = 'K1:L1';
                $cellRangeLoopB = 'K1:L' . $this->data['row2'] + 1;

                $style = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'font' => [
                        'name'  =>  'Calibri',
                        'size'  =>  11,
                        'bold' => false
                    ]
                ];

                $sheet->getStyle($cellRangeLoop)->applyFromArray($style);
                $sheet->getStyle($cellRangeLoopB)->applyFromArray($style);
                $sheet->getStyle("J1")->getFont()->setBold(true);
                
                $sheet->getStyle("A1")->getFont()->setColor(new Color(Color::COLOR_RED));
                $sheet->getStyle("E1")->getFont()->setColor(new Color(Color::COLOR_RED));
                $sheet->getStyle("K1")->getFont()->setColor(new Color(Color::COLOR_RED));
                $sheet->getStyle("$cellRange")->getFont()->setBold(true);
                $sheet->getStyle("$cellRangeB")->getFont()->setBold(true);

                $sheet->setCellValue('J1', 'Klasifikasi Akun');
                $sheet->setCellValue('K1', 'ID Klasifikasi');
                $sheet->setCellValue('L1', 'Klasifikasi');

                $sheet->setCellValue('K2', 'untuk menambah klasifikasi maka kosongi saja kolom id');
                $sheet->setCellValue('L2', 'isi Klasifikasi');

                foreach ($this->data['tbl2'] as $i => $d) {
                    $row = $i + 3;
                    $sheet->setCellValue('K' . $row, $d->id_subklasifikasi_akun);
                    $sheet->setCellValue('L' . $row, $d->nm_subklasifikasi);
                }
            },
        ];
    }
}
