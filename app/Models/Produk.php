<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'id_produk';
    protected $table = 'tb_produk';
    protected $guarded = [];

    public function satuan()
    {
        return $this->hasOne(Satuan::class, 'id_satuan', 'satuan_id');
    }
}
