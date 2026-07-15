<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $guarded = ['id'];

    public function jadwal()
    {
        return $this->belongsTo(
            JadwalPelajaran::class,
            'jadwal_pelajaran_id'
        );
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(
            TahunAjaran::class,
            'tahun_ajaran_id'
        );
    }

    public function details()
    {
        return $this->hasMany(
            PenilaianDetail::class
        );
    }
}
