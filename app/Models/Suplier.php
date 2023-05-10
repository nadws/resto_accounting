<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suplier extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'id_suplier';
    protected $table = 'tb_suplier';
    protected $guarded = [];
    
}
