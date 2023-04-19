<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCenter extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'id_post_center';
    protected $table = 'tb_post_center';
    protected $guarded = [];
}
