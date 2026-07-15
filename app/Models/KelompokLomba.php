<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToHaflah;

class KelompokLomba extends Model
{
    use BelongsToHaflah;

    protected $guarded = ['id'];

    public function lomba()
    {
        return $this->belongsTo(Lomba::class);
    }

    public function anggota()
    {
        return $this->hasMany(AnggotaKelompok::class);
    }

    public function pesertaLomba()
    {
        return $this->hasOne(PesertaLomba::class);
    }

    public function penilaianLombas()
    {
        return $this->hasManyThrough(PenilaianLomba::class, PesertaLomba::class);
    }
}
