<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToHaflah;

class JuriLomba extends Model
{
    use BelongsToHaflah;

    protected $guarded = ['id'];

    public function lomba()
    {
        return $this->belongsTo(Lomba::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function penilaian()
    {
        return $this->hasMany(PenilaianLomba::class);
    }
}
