<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengampuMapel extends Model
{
    protected $guarded = ['id'];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(
            TahunAjaran::class,
            'tahun_ajaran_id'
        );
    }
}
