<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasFactory;
    protected $table = 'jurnal';
    protected $guarded = [];

    public function Akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun', 'id_akun');
    }
    public function proyek()
    {
        return $this->belongsTo(proyek::class, 'id_proyek', 'id_proyek');
    }
    public function postCenter()
    {
        return $this->belongsTo(PostCenter::class, 'id_post_center', 'id_post_center');
    }
}
