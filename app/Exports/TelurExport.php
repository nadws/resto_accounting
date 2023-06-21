<?php

namespace App\Exports;

use App\Models\Jurnal;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;


class TelurExport implements FromView, WithEvents
{
    protected $tgl1;
    protected $tgl2;
    protected $totalrow;

    public function __construct($tgl1, $tgl2, $totalrow)
    {
        $this->tgl1 = $tgl1;
        $this->tgl2 = $tgl2;
        $this->totalrow = $totalrow;
    }
    public function view(): View
    {
        $telur = DB::select("SELECT a.tgl, b.nm_kandang, c.nm_telur, a.pcs, a.kg, a.pcs_kredit, a.kg_kredit, a.ket, a.admin
        FROM stok_telur as a 
        left join kandang as b on b.id_kandang = a.id_kandang
        left join telur_produk as c on c.id_produk_telur = a.id_telur
        where a.tgl BETWEEN '$this->tgl1' and '$this->tgl2' and a.id_gudang = '1';");


        return view('exports.telur', [
            'telur' => $telur,
        ]);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $totalrow = $this->totalrow + 1;
                $cellRange = 'A1:I1';
                // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->setAutoFilter($cellRange);
                $event->sheet->getStyle('A1:I1')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'font' => [
                        'name'  =>  'Calibri',
                        'size'  =>  12,
                        'bold' => true
                    ]
                ]);
                $event->sheet->getStyle('A2:I' . $totalrow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'font' => [
                        'name'  =>  'Calibri',
                        'size'  =>  12,
                        'bold' => false
                    ]
                ]);
            },
        ];
    }
}
