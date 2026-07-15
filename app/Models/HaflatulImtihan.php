<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HaflatulImtihan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function sesiLombas()
    {
        return $this->hasMany(SesiLomba::class, 'haflah_id');
    }

    public function lombas()
    {
        return $this->hasMany(Lomba::class, 'haflah_id');
    }

    public function kategoriLombas()
    {
        return $this->hasMany(KategoriLomba::class, 'haflah_id');
    }

    public function pesertaLombas()
    {
        return $this->hasMany(PesertaLomba::class, 'haflah_id');
    }

    public function kelompokLombas()
    {
        return $this->hasMany(KelompokLomba::class, 'haflah_id');
    }

    public function anggotaKelompoks()
    {
        return $this->hasMany(AnggotaKelompok::class, 'haflah_id');
    }

    public function juriLombas()
    {
        return $this->hasMany(JuriLomba::class, 'haflah_id');
    }

    public function aspekPenilaians()
    {
        return $this->hasMany(AspekPenilaian::class, 'haflah_id');
    }

    public function penilaianLombas()
    {
        return $this->hasMany(PenilaianLomba::class, 'haflah_id');
    }

    public function hasilLombas()
    {
        return $this->hasMany(HasilLomba::class, 'haflah_id');
    }

    public function sesis()
    {
        return $this->hasMany(Sesi::class, 'haflah_id');
    }
}
