<?php

namespace App\Imports;

use App\Models\Jurnal;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class JurnalImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {

        return new Jurnal(
            [
                'tgl' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal']),
                'no_nota' => $row['no_nota'],
                'id_buku' => '2',
                'id_akun' => $row['akun'],
                'ket' => $row['keterangan'],
                'debit' => $row['debit'],
                'kredit' => $row['kredit'],
                'admin' => $row['admin'],
            ]
        );
    }
}
