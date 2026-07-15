<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToHaflah;

class Lomba extends Model
{
    use BelongsToHaflah;

    protected $guarded = ['id'];

    protected $casts = [];

    public function haflatulImtihan()
    {
        return $this->belongsTo(HaflatulImtihan::class, 'haflah_id');
    }

    public function sesiLomba()
    {
        return $this->belongsTo(SesiLomba::class);
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriLomba::class, 'kategori_lomba_id');
    }

    public function peserta()
    {
        return $this->hasMany(PesertaLomba::class);
    }

    public function kelompok()
    {
        return $this->hasMany(KelompokLomba::class);
    }

    public function juri()
    {
        return $this->hasMany(JuriLomba::class);
    }

    public function aspekPenilaians()
    {
        return $this->hasMany(AspekPenilaian::class);
    }

    public function hasil()
    {
        return $this->hasMany(HasilLomba::class);
    }

}
