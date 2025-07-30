<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPeraturan extends Model
{
    protected $table = 'jenis_peraturan';
    protected $guarded = ['id'];

    public function rule()
    {
        return $this->hasMany(Peraturan::class);
    }
}