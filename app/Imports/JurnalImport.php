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
                'id_jurnal' => $row['id_jurnal'],
                'tgl' => $row['tgl'],
                'id_akun' => $row['id_akun'],
                'id_buku' => $row['id_buku'],
                'no_nota' => $row['no_nota'],
                'ket' => $row['ket'],
                'no_dokumen' => $row['no_dokumen'],
                'tgl_dokumen' => $row['tgl_dokumen'],
                'debit' => $row['debit'],
                'kredit' => $row['kredit'],
                'admin' => $row['admin'],
                'id_proyek' => $row['id_proyek'],
                'id_departemen' => $row['id_departemen'],
                'id_post_center' => $row['id_post_center'],
                'no_urut' => $row['no_urut'],
                'urutan' => $row['urutan'],
                'saldo' => $row['saldo'],
                'penutup' => $row['penutup'],
                'kode_penyesuaian' => $row['kode_penyesuaian'],
                'setor' => $row['setor'],
            ]
        );
    }
}
