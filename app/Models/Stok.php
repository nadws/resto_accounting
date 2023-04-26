<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Stok extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'id_stok_produk';
    protected $table = 'tb_stok_produk';
    protected $guarded = [];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function satuan()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }

    public static function getStatus($no_nota) 
    {
        return self::where('no_nota', $no_nota)->first();
    }

    public static function getStokMasuk($id_produk)
    {
        return DB::selectOne("SELECT a.rp_satuan, a.debit, a.id_produk,a.jml_sebelumnya,a.jml_sesudahnya,b.nm_produk,d.nm_satuan, c.ttlDebit, c.ttlKredit, a.id_stok_produk 
            FROM `tb_stok_produk` as a
            LEFT JOIN tb_produk as b ON a.id_produk = b.id_produk
            LEFT JOIN tb_satuan as d ON b.satuan_id = d.id_satuan
            LEFT JOIN (
                SELECT b.id_produk, sum(b.debit) as ttlDebit, sum(b.kredit) as ttlKredit
            FROM tb_stok_produk as b WHERE b.jenis = 'selesai' AND b.id_produk = '$id_produk' GROUP BY b.id_produk
            ) as c ON c.id_produk = a.id_produk WHERE a.id_produk = '$id_produk'
            ");
    }
}
