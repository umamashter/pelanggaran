<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilMadrasah extends Model
{
    protected $table = 'profil_madrasah';

    protected $guarded = ['id'];

    public function misi()
    {
        return $this->hasMany(Misi::class)->orderBy('urutan');
    }
}
