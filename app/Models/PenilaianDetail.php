<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianDetail extends Model
{
    protected $guarded = ['id'];

    public function penilaian()
    {
        return $this->belongsTo(
            Penilaian::class
        );
    }

    public function student()
    {
        return $this->belongsTo(
            Student::class
        );
    }

    public function getNilaiAkhirAttribute()
    {
        return (
            ($this->tugas * 0.20) +
            ($this->uh * 0.30) +
            ($this->pts * 0.20) +
            ($this->pas * 0.30)
        );
    }
}
