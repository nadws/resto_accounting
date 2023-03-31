<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    use HasFactory;
    protected $table = 'akun';
    protected $guarded = [];

    public function klasifikasi()
    {
        return $this->belongsTo(SubklasifikasiAkun::class, 'id_klasifikasi', 'id_subklasifikasi_akun');
    }
    // public function stok()
    // {
    //     return $this->belongsTo(StokProduk::class, 'id_product', 'id_produk')
    //         ->selectRaw('sum(debit) as debit, sum(kredit) as kredit')->groupBy('id_produk');
    // }


}
