<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\WaliKelas;
use App\Models\StudentKelas;
use App\Models\PengampuMapel;
use App\Models\Jenjang;

class Kelas extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Relasi ke jenjang
     */
    public function jenjang()
    {
        return $this->belongsTo(Jenjang::class);
    }

    /**
     * Relasi ke wali kelas
     */
    public function waliKelas()
    {
        return $this->hasOne(WaliKelas::class);
    }

    /**
     * Relasi ke guru BK
     */
    public function bk()
    {
        return $this->hasOne(GuruBk::class);
    }

    /**
     * Riwayat anggota kelas
     */
    public function anggota()
    {
        return $this->hasMany(StudentKelas::class);
    }

    /**
     * Siswa aktif di kelas
     */
    public function siswaAktif()
    {
        return $this->hasMany(StudentKelas::class)
            ->where('aktif', true);
    }

    /**
     * Pengampu mapel kelas
     */
    public function pengampuMapel()
    {
        return $this->hasMany(PengampuMapel::class);
    }
}
