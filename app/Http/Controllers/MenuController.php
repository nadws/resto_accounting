<?php

namespace App\Http\Controllers;

use App\Models\MenuModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MenuController extends Controller
{
    public function index(Request $r)
    {
        $data = [
            'title' => 'Data Menu',
            'st' => DB::table('tb_station')
                ->where('id_lokasi', '1')
                ->orderBy('id_station', 'ASC')
                ->get(),
            'menu' => DB::table('tb_menu')
                ->orderBy('kd_menu', 'desc')
                ->where('lokasi', '1')
                ->first(),
            'kategori' => DB::table('tb_kategori')->where('lokasi', 'TAKEMORI')->get(),
            'handicap' => DB::table('tb_handicap')->where('id_lokasi', '1')->get(),
            'bahan' => DB::table('tb_list_bahan')->get()
        ];
        return view('datamenu.menu.index', $data);
    }

    public function get_menu(Request $request)
    {
        $perPage = $request->perpage;

        $search = $request->input('search');
        $query = MenuModel::tbmenu();

        // Filter data sesuai kriteria pencarian

        $query
            ->where(function ($query) use ($search) {
                $query->where('d.kategori', 'like', '%' . $search . '%')
                    ->orWhere('a.kd_menu', 'like', '%' . $search . '%')
                    ->orWhere('a.nm_menu', 'like', '%' . $search . '%')
                    ->orWhere('a.tipe', 'like', '%' . $search . '%')
                    ->orWhere('c.nm_station', 'like', '%' . $search . '%');
            });

        $menu = $query->orderBy('a.id_menu', 'DESC')->paginate($perPage);

        $data = [
            'menu' => $menu,
        ];

        return view('datamenu.menu.getmenu', $data);
    }


    function aktif(Request $r)
    {
        DB::table('tb_menu')->where('id_menu', $r->id_menu)->update(['aktif' => $r->status]);
    }
    function tambah_baris_resep(Request $r)
    {
        $data = [
            'bahan' => DB::table('tb_list_bahan')->get(),
            'count' => $r->count
        ];
        return view('datamenu.menu.resep', $data);
    }

    function get_satuan_resep(Request $r)
    {
        $satuan = DB::table('tb_list_bahan as a')
            ->leftJoin('tb_satuan as b', 'b.id_satuan', '=', 'a.id_satuan')
            ->where('a.id_list_bahan', $r->id_bahan)
            ->first();

        echo "$satuan->nm_satuan";
    }

    function save_menu(Request $r)
    {
        DB::beginTransaction(); // Start transaction

        try {
            $menu = DB::table('tb_menu')
                ->orderBy('kd_menu', 'desc')
                ->where('lokasi', '1')
                ->first();

            $kd_menu = $menu->kd_menu + 1;

            if ($r->hasFile('image')) {
                $r->file('image')->move('assets/tb_menu/', $r->file('image')->getClientOriginalName());
                $foto = $r->file('image')->getClientOriginalName();
            } else {
                $foto = '';
            }

            $data1 = [
                'id_kategori' => $r->id_kategori,
                'kd_menu' => $kd_menu,
                'nm_menu' => $r->nm_menu,
                'id_handicap' => $r->id_handicap,
                'tipe' => $r->tipe,
                'id_station' => $r->id_station,
                'image' => $foto,
                'lokasi' => 1,
                'aktif' => 'on',
            ];

            $id_menu = DB::table('tb_menu')->insertGetId($data1);

            $id_distribusi = $r->id_distribusi;
            $harga = $r->harga;

            for ($i = 0; $i < count($r->id_distribusi); $i++) {
                if ($harga[$i] != 0 && !empty($harga[$i])) {
                    $data2 = [
                        'id_menu' => $id_menu,
                        'id_distribusi' => $id_distribusi[$i],
                        'harga' => $harga[$i],
                    ];
                    DB::table('tb_harga')->insert($data2);
                }
            }

            for ($i = 0; $i < count($r->id_bahan); $i++) {
                if (!empty($r->id_bahan[$i])) {
                    $data = [
                        'id_menu' => $id_menu,
                        'id_bahan' => $r->id_bahan[$i],
                        'qty' => $r->qty[$i],
                    ];
                    DB::table('resep')->insert($data);
                }
            }

            DB::commit(); // If all steps are successful, commit the transaction
        } catch (\Exception $e) {
            DB::rollBack(); // If any error occurs, roll back the transaction
            return response()->json(['error' => $e->getMessage()], 500); // You can customize the error response as needed
        }
    }

    function delete_menu(Request $r)
    {
        DB::table('tb_menu')->where('id_menu', $r->id_menu)->delete();
        DB::table('tb_harga')->where('id_menu', $r->id_menu)->delete();
        DB::table('resep')->where('id_menu', $r->id_menu)->delete();
    }

    function get_edit(Request $r)
    {

        $data = [
            'menu' => DB::table('tb_menu')->where('id_menu', $r->id_menu)->first(),
            'harga1' => DB::table('tb_harga')->where('id_menu', $r->id_menu)->where('id_distribusi', '1')->first(),
            'harga2' => DB::table('tb_harga')->where('id_menu', $r->id_menu)->where('id_distribusi', '2')->first(),
            'resep' => DB::table('resep as a')
                ->leftJoin('tb_list_bahan as b', 'b.id_list_bahan', '=', 'a.id_bahan')
                ->leftJoin('tb_satuan as c', 'c.id_satuan', '=', 'b.id_satuan')
                ->where('a.id_menu', $r->id_menu)->get(),


            'st' => DB::table('tb_station')
                ->where('id_lokasi', '1')
                ->orderBy('id_station', 'ASC')
                ->get(),
            'kategori' => DB::table('tb_kategori')->where('lokasi', 'TAKEMORI')->get(),
            'handicap' => DB::table('tb_handicap')->where('id_lokasi', '1')->get(),
            'bahan' => DB::table('tb_list_bahan')->get()
        ];

        return view('datamenu.menu.edit_menu', $data);
    }

    function edit(Request $r)
    {
        DB::beginTransaction(); // Start transaction

        try {
            DB::table('tb_menu')->where('id_menu', $r->id_menu)->delete();
            DB::table('tb_harga')->where('id_menu', $r->id_menu)->delete();
            DB::table('resep')->where('id_menu', $r->id_menu)->delete();

            if ($r->hasFile('image')) {
                $r->file('image')->move('assets/tb_menu/', $r->file('image')->getClientOriginalName());
                $foto = $r->file('image')->getClientOriginalName();
            } else {
                $foto = '';
            }

            $data1 = [
                'id_menu' => $r->id_menu,
                'id_kategori' => $r->id_kategori,
                'kd_menu' => $r->kd_menu,
                'nm_menu' => $r->nm_menu,
                'id_handicap' => $r->id_handicap,
                'tipe' => $r->tipe,
                'id_station' => $r->id_station,
                'image' => $foto,
                'lokasi' => 1,
                'aktif' => 'on',
            ];

            $id_menu = DB::table('tb_menu')->insert($data1);

            $id_distribusi = $r->id_distribusi;
            $harga = $r->harga;

            for ($i = 0; $i < count($r->id_distribusi); $i++) {
                if ($harga[$i] != 0 && !empty($harga[$i])) {
                    $data2 = [
                        'id_menu' => $r->id_menu,
                        'id_distribusi' => $id_distribusi[$i],
                        'harga' => $harga[$i],
                    ];
                    DB::table('tb_harga')->insert($data2);
                }
            }

            for ($i = 0; $i < count($r->id_bahan); $i++) {
                if (!empty($r->id_bahan[$i])) {
                    $data = [
                        'id_menu' => $r->id_menu,
                        'id_bahan' => $r->id_bahan[$i],
                        'qty' => $r->qty[$i],
                    ];
                    DB::table('resep')->insert($data);
                }
            }

            DB::commit(); // If all steps are successful, commit the transaction
        } catch (\Exception $e) {
            DB::rollBack(); // If any error occurs, roll back the transaction
            return response()->json(['error' => $e->getMessage()], 500); // You can customize the error response as needed
        }
    }

    function get_resep(Request $r)
    {
        $data = [
            'menu' => DB::table('tb_menu')->where('id_menu', $r->id_menu)->first(),
            'resep' => DB::table('resep as a')
                ->leftJoin('tb_list_bahan as b', 'b.id_list_bahan', '=', 'a.id_bahan')
                ->leftJoin('tb_satuan as c', 'c.id_satuan', '=', 'b.id_satuan')
                ->where('a.id_menu', $r->id_menu)->get(),
            'bahan' => DB::table('tb_list_bahan')->get()
        ];

        return view('datamenu.menu.get_resep', $data);
    }

    function save_resep(Request $r)
    {
        DB::beginTransaction(); // Start transaction

        try {
            DB::table('resep')->where('id_menu', $r->id_menu)->delete();
            for ($i = 0; $i < count($r->id_bahan); $i++) {
                if (!empty($r->id_bahan[$i])) {
                    $data = [
                        'id_menu' => $r->id_menu,
                        'id_bahan' => $r->id_bahan[$i],
                        'qty' => $r->qty[$i],
                    ];
                    DB::table('resep')->insert($data);
                }
            }

            DB::commit(); // If all steps are successful, commit the transaction
        } catch (\Exception $e) {
            DB::rollBack(); // If any error occurs, roll back the transaction
            return response()->json(['error' => $e->getMessage()], 500); // You can customize the error response as needed
        }
    }

    function export_menu(Request $r)
    {
        $id_lokasi = 1;
        $lokasiTs = $id_lokasi == 1 ? 'TAKEMORI' : 'SOONDOBU';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle('A1:D4')
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        // lebar kolom
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(13);
        $sheet->getColumnDimension('F')->setWidth(13);
        // header text
        $sheet
            ->setCellValue('A1', 'ID KATEGORI')
            ->setCellValue('B1', 'KATEGORI')
            ->setCellValue('C1', 'ID MENU')
            ->setCellValue('D1', 'NAMA MENU')
            ->setCellValue('E1', 'TIPE(FOOD/DRINK)')
            ->setCellValue('F1', 'DINE IN / TAKEAWAY')
            ->setCellValue('G1', 'GOJEK')
            ->setCellValue('H1', 'DELIVERY')
            ->setCellValue('I1', 'ID LEVEL')
            ->setCellValue('J1', 'POINT')
            ->setCellValue('K1', 'ID STATION')
            ->setCellValue('L1', 'STATION');

        $sheet
            ->setCellValue('N1', 'Level Point')
            ->setCellValue('O1', 'Id Level')
            ->setCellValue('P1', 'Level')
            ->setCellValue('Q1', 'Point')
            ->setCellValue('R1', 'Keterangan');

        $sheet
            ->setCellValue('T1', 'Station')
            ->setCellValue('U1', 'Id Station')
            ->setCellValue('V1', 'Station');

        $sheet
            ->setCellValue('X1', 'Kategori')
            ->setCellValue('Y1', 'Id Kategori')
            ->setCellValue('Z1', 'Kategori');

        $level = DB::table('tb_handicap')->where('id_lokasi', $id_lokasi)->get();
        $station = DB::table('tb_station')->where('id_lokasi', $id_lokasi)->get();
        $tbMenu = DB::table('mHandicap as a')->join('tb_station as b', 'a.id_station', 'b.id_station')->where([['a.lokasiMenu', $id_lokasi], ['a.aktif', 'on']])->orderBy('a.id_menu', 'DESC')
            ->get();
        $lom = 2;
        foreach ($tbMenu as $t) {
            $sheet
                ->setCellValue('A' . $lom, $t->kd_kategori)
                ->setCellValue('B' . $lom, $t->kategori)
                ->setCellValue('C' . $lom, $t->id_menu)
                ->setCellValue('D' . $lom, $t->nm_menu)
                ->setCellValue('E' . $lom, $t->tipe);
            $h1 = DB::table('tb_harga')->where([['id_menu', $t->id_menu], ['id_distribusi', 1]])
                ->first();
            $h2 = DB::table('tb_harga')->where([['id_menu', $t->id_menu], ['id_distribusi', 2]])
                ->first();
            $h3 = DB::table('tb_harga')->where([['id_menu', $t->id_menu], ['id_distribusi', 3]])
                ->first();
            $sheet->setCellValue('F' . $lom, $h1 != '' ? $h1->harga : '');
            $sheet->setCellValue('G' . $lom, $h2 != '' ? $h2->harga : '');
            $sheet->setCellValue('H' . $lom, $h3 != '' ? $h3->harga : '');

            // $sheet->setCellValue('G'.$lom,$h->harga);
            // $sheet->setCellValue('H'.$lom,$h->harga);

            $sheet->setCellValue('I' . $lom, $t->id_handicap)
                ->setCellValue('J' . $lom, $t->point)
                ->setCellValue('K' . $lom, $t->id_station)
                ->setCellValue('L' . $lom, $t->nm_station);

            $lom++;
        }
        $kolom = 2;
        foreach ($level as $k) {
            $sheet
                ->setCellValue('O' . $kolom, $k->id_handicap)
                ->setCellValue('P' . $kolom, $k->handicap)
                ->setCellValue('Q' . $kolom, $k->point)
                ->setCellValue('R' . $kolom, $k->ket);
            $kolom++;
        }

        $kom = 2;
        foreach ($station as $k) {
            $sheet
                ->setCellValue('U' . $kom, $k->id_station)
                ->setCellValue('V' . $kom, $k->nm_station);
            $kom++;
        }
        $kategori = DB::table('tb_kategori')->where('lokasi', $lokasiTs)->get();
        $ko = 2;
        foreach ($kategori as $k) {
            $sheet
                ->setCellValue('Y' . $ko, $k->kd_kategori)
                ->setCellValue('Z' . $ko, $k->kategori);
            $ko++;
        }

        $writer = new Xlsx($spreadsheet);
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


        // tambah style
        $batas1 = count($tbMenu) + 1;
        $sheet->getStyle('A1:L' . $batas1)->applyFromArray($style);
        $batas = count($level) + 1;
        $sheet->getStyle('O1:R' . $batas)->applyFromArray($style);
        $batas2 = count($station) + 1;
        $sheet->getStyle('U1:V' . $batas2)->applyFromArray($style);
        $batas3 = count($kategori) + 1;
        $sheet->getStyle('Y1:Z' . $batas3)->applyFromArray($style);



        $sheet->getStyle('I1')->getAlignment()->setHorizontal('center');
        // $sheet->getStyle('M1')->getAlignment()->setHorizontal('center');


        $sheet->getStyle('I1')
            ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
        $sheet->getStyle('K1')
            ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

        $merah = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'cd4c4c'
                ]
            ]
        ];
        $sheet->getStyle('A1')->applyFromArray($merah);
        $sheet->getStyle('F1')->applyFromArray($merah);
        $sheet->getStyle('H1')->applyFromArray($merah);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Format Level & Station Menu ' . $lokasiTs . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }

    public function importMenuLevel(Request $request)
    {
        // include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $file = $request->file('file');
        $ext = $file->getClientOriginalExtension();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($file);
        // $loadexcel = $excelreader->load('excel/'.$this->filename.'.xlsx'); // Load file yang telah diupload ke folder excel
        // $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
        $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $lokasi = $request->id_lokasi;


        $data = array();
        $numrow = 1;
        // cek
        $cek = 0;
        foreach ($sheet as $row) {

            if ($row['A'] == "" && $row['B'] == "" && $row['C'] == "" && $row['D'] == "" && $row['E'] == "" && $row['F'] == "" && $row['G'] == "" && $row['H'] == "")
                continue;
            $numrow++; // Tambah 1 setiap kali looping
        }
        // endcek
        foreach ($sheet as $row) {

            if ($numrow > 1) {

                $data = [
                    'id_handicap' => $row['I'],
                    'id_station' => $row['K'],
                    'id_kategori' => $row['A'],
                ];
                DB::table('tb_menu')->where('id_menu', $row['C'])->update($data);


                if ($row['O'] == '' && $row['P'] == '' && $row['Q'] == '') {
                    continue;
                } elseif ($row['O'] == '') {
                    $data = [
                        'handicap' => $row['P'],
                        'point' => $row['Q'],
                        'id_lokasi' => $request->lokasi,
                    ];
                    DB::table('tb_handicap')->insert($data);
                } else {
                    $data = [
                        'handicap' => $row['P'],
                        'point' => $row['Q'],
                    ];
                    DB::table('tb_handicap')->where('id_handicap', $row['O'])->update($data);
                }

                if ($row['U'] == '' && $row['V'] == '') {
                    continue;
                } elseif ($row['U'] == '') {
                    $dataS = [
                        'nm_station' => $row['V'],
                        'id_lokasi' => $request->lokasi,
                    ];
                    DB::table('tb_station')->insert($dataS);
                } else {
                    $dataS = [
                        'nm_station' => $row['V'],
                    ];
                    DB::table('tb_station')->where('id_station', $row['U'])->update($dataS);
                }

                if ($row['Y'] == '' && $row['Z'] == '') {
                    continue;
                } elseif ($row['V'] == '') {
                    $dataK = [
                        'kategori' => $row['Z'],
                        'lokasi' => $request->lokasi == 1 ? 'TAKEMORI' : 'SOONDOBU',
                    ];
                    DB::table('tb_kategori')->insert($dataK);
                } else {
                    $dataK = [
                        'kategori' => $row['Z'],
                    ];
                    DB::table('tb_kategori')->where('kd_kategori', $row['Y'])->update($dataK);
                }
            }
            $numrow++; // Tambah 1 setiap kali looping
        }

        return redirect()->route('menu.index')->with('sukses', 'Data berhasil Diimport');
    }


    function export_resep(Request $r)
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
        $sheet1->setTitle('Resep');
        $sheet1->getStyle("A1:G1")->applyFromArray($style_atas);

        $sheet1->setCellValue('A1', 'ID Resep');
        $sheet1->setCellValue('B1', 'ID Menu');
        $sheet1->setCellValue('C1', 'Nama Menu');
        $sheet1->setCellValue('D1', 'ID Bahan');
        $sheet1->setCellValue('E1', 'Nama Bahan');
        $sheet1->setCellValue('F1', 'Qty');
        $sheet1->setCellValue('G1', 'Satuan');

        $resep = DB::select("SELECT a.id_resep, b.id_menu, b.nm_menu, c.id_list_bahan, c.nm_bahan, a.qty, d.nm_satuan
        FROM resep as a
        left join tb_menu as b on b.id_menu = a.id_menu 
        left join tb_list_bahan as c on c.id_list_bahan = a.id_bahan 
        left join tb_satuan as d on d.id_satuan = c.id_satuan
        where b.id_menu is not null
        order by b.id_menu DESC
        ");

        $kolom = 2;
        foreach ($resep as $r) {
            $sheet1->setCellValue('A' . $kolom, $r->id_resep);
            $sheet1->setCellValue('B' . $kolom, $r->id_menu);
            $sheet1->setCellValue('C' . $kolom, $r->nm_menu);
            $sheet1->setCellValue('D' . $kolom, $r->id_list_bahan);
            $sheet1->setCellValue('E' . $kolom, $r->nm_bahan);
            $sheet1->setCellValue('F' . $kolom, $r->qty);
            $sheet1->setCellValue('G' . $kolom, $r->nm_satuan);

            $kolom++;
        }
        $sheet1->getStyle('A2:G' . $kolom - 1)->applyFromArray($style);

        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(1);
        $sheet2 = $spreadsheet->getActiveSheet(1);
        $sheet2->setTitle('Menu');
        $sheet2->getStyle("A1:H1")->applyFromArray($style_atas);

        $sheet2->setCellValue('A1', 'ID Menu');
        $sheet2->setCellValue('B1', 'Kategori');
        $sheet2->setCellValue('C1', 'Level');
        $sheet2->setCellValue('D1', 'Kode Menu');
        $sheet2->setCellValue('E1', 'Nama Menu');
        $sheet2->setCellValue('F1', 'Tipe');
        $sheet2->setCellValue('G1', 'Station');
        $sheet2->setCellValue('H1', 'Aktif');

        $menu = MenuModel::tbmenu()->get();

        $kolom = 2;
        foreach ($menu as $r) {
            $sheet2->setCellValue('A' . $kolom, $r->id_menu);
            $sheet2->setCellValue('B' . $kolom, $r->kategori);
            $sheet2->setCellValue('C' . $kolom, $r->handicap . ' (' . $r->point . ')');
            $sheet2->setCellValue('D' . $kolom, $r->kd_menu);
            $sheet2->setCellValue('E' . $kolom, $r->nm_menu);
            $sheet2->setCellValue('F' . $kolom, $r->tipe);
            $sheet2->setCellValue('G' . $kolom, $r->nm_station);
            $sheet2->setCellValue('H' . $kolom, $r->aktif);

            $kolom++;
        }
        $sheet2->getStyle('A2:H' . $kolom - 1)->applyFromArray($style);


        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(2);
        $sheet3 = $spreadsheet->getActiveSheet(2);
        $sheet3->setTitle('Bahan');
        $sheet3->getStyle("A1:C1")->applyFromArray($style_atas);

        $sheet3->setCellValue('A1', 'ID Bahan');
        $sheet3->setCellValue('B1', 'Nama Bahan');
        $sheet3->setCellValue('C1', 'Satuan');

        $bahan = DB::table('tb_list_bahan as a')->leftJoin('tb_satuan as b', 'b.id_satuan', '=', 'a.id_satuan')->get();

        $kolom = 2;
        foreach ($bahan as $r) {
            $sheet3->setCellValue('A' . $kolom, $r->id_list_bahan);
            $sheet3->setCellValue('B' . $kolom, $r->nm_bahan);
            $sheet3->setCellValue('C' . $kolom, $r->nm_satuan);

            $kolom++;
        }
        $sheet3->getStyle('A2:C' . $kolom - 1)->applyFromArray($style);



        $namafile = "Resep.xlsx";

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $namafile);
        header('Cache-Control: max-age=0');


        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }

    function import_resep(Request $r)
    {
        $uploadedFile = $r->file('file');
        $allowedExtensions = ['xlsx'];
        $extension = $uploadedFile->getClientOriginalExtension();

        if (in_array($extension, $allowedExtensions)) {
            $spreadsheet = IOFactory::load($uploadedFile->getPathname());
            $sheet2 = $spreadsheet->getSheetByName('Resep');
            $data = [];

            foreach ($sheet2->getRowIterator() as $index => $row) {
                if ($index === 1) {
                    continue;
                }

                $rowData = [];
                foreach ($row->getCellIterator() as $cell) {
                    $rowData[] = $cell->getValue();
                }
                $data[] = $rowData;
            }



            DB::beginTransaction(); // Mulai transaksi database

            try {
                foreach ($data as $rowData) {
                    if (empty($rowData[0])) {
                        $data = [
                            'id_menu' => $rowData[1],
                            'id_bahan' => $rowData[3],
                            'qty' => $rowData[5],
                        ];
                        DB::table('resep')->insert($data);
                    } else {
                        $data = [
                            'id_menu' => $rowData[1],
                            'id_bahan' => $rowData[3],
                            'qty' => $rowData[5],
                        ];
                        DB::table('resep')->where('id_resep', $rowData[0])->update($data);
                    }
                }
                DB::commit(); // Konfirmasi transaksi jika berhasil
                return redirect()->route('menu.index')->with('sukses', 'Data berhasil import');
            } catch (\Exception $e) {
                DB::rollback(); // Batalkan transaksi jika terjadi kesalahan lain
                return redirect()->route('menu.index')->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
            }
        } else {
            return redirect()->route('menu.index')->with('error', 'File yang diunggah bukan file Excel yang valid');
        }
    }

    public function station(Request $r)
    {
        $data = [
            'id_lokasi' => $r->id_lokasi
        ];

        return view('datamenu.menu.station', $data);
    }
}
