<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Misi extends Model
{
    protected $table = 'misi';

    protected $guarded = ['id'];

    public function profilMadrasah()
    {
        return $this->belongsTo(ProfilMadrasah::class);
    }
}
