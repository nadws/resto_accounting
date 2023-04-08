<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
