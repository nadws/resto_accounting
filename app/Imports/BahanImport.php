<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BahanImport implements ToModel, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function model(array $row)
    {
        if (empty(array_filter($row))) {
            // Jika semua elemen kosong, lewati ke iterasi berikutnya
            return null;
        }
        DB::table('tb_list_bahan')->insert([
            'nm_bahan' =>$row['nm_bahan'],
            'id_satuan' => $row['satuan_id'],
            'id_kategori' => $row['kategori_id'],
            'admin' => auth()->user()->name,
            'tgl' => date('Y-m-d')
        ]);
    }
}
