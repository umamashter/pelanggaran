<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $guarded = ['id'];

    public function jadwal()
    {
        return $this->belongsTo(JadwalPelajaran::class, 'jadwal_pelajaran_id');
    }

    public function details()
    {
        return $this->hasMany(AbsensiDetail::class);
    }
}
