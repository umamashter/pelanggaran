<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiDetail extends Model
{
    protected $guarded = ['id'];

    public function absensi()
    {
        return $this->belongsTo(Absensi::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
