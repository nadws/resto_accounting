<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'id_gudang';
    protected $table = 'tb_gudang';
    protected $guarded = [];
}
