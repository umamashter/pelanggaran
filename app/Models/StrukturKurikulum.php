<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrukturKurikulum extends Model
{
    protected $guarded = ['id'];

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(
            MataPelajaran::class,
            'mata_pelajaran_id'
        );
    }
}
