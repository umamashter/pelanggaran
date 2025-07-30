<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TindakLanjut extends Model
{
    protected $table = 'tindak_lanjut';
    protected $guarded = ['id'];

    public function penanganan()
    {
        return $this->hasMany(Penanganan::class);
    }
}