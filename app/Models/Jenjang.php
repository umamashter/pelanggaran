<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jenjang extends Model
{
    protected $fillable = [
        'kode',
        'nama_jenjang',
        'tingkat_awal',
        'tingkat_akhir'
    ];
}
