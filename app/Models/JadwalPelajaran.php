<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPelajaran extends Model
{
    protected $table = 'jadwal_pelajaran';

    protected $guarded = ['id'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function jenjang()
    {
        return $this->belongsTo(Jenjang::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function mapel()
    {
        return $this->belongsTo(
            MataPelajaran::class,
            'mata_pelajaran_id'
        );
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(
            TahunAjaran::class,
            'tahun_ajaran_id'
        );
    }
    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
    public function penilaians()
    {
        return $this->hasMany(Penilaian::class);
    }
}
