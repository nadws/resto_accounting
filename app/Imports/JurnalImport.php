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
                'tgl' => $row['tgl'],
                'id_akun' => $row['id_akun'],
                'id_buku' => $row['id_buku'],
                'no_nota' => $row['no_nota'],
                'ket' => $row['ket'],
                'no_dokumen' => $row['no_dokumen'],
                'debit' => $row['debit'],
                'kredit' => $row['kredit'],
                'admin' => 'import',
                'id_post_center' => $row['id_post_center'],
                'setor' => $row['setor'],
            ]
        );
    }
}
