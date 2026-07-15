<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'kurikulum_id',
        'jenjang_id',
        'kelompok',
        'status'
    ];

    public function pengampuMapel()
    {
        return $this->hasMany(PengampuMapel::class);
    }

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }

    public function jenjang()
    {
        return $this->belongsTo(Jenjang::class);
    }
}
