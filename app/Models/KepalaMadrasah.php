<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KepalaMadrasah extends Model
{
    protected $table = 'kepala_madrasah';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenjang()
    {
        return $this->belongsTo(Jenjang::class);
    }
}
