<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToHaflah;

class PenilaianLomba extends Model
{
    use BelongsToHaflah;

    protected $guarded = ['id'];

    public function pesertaLomba()
    {
        return $this->belongsTo(PesertaLomba::class);
    }

    public function juriLomba()
    {
        return $this->belongsTo(JuriLomba::class);
    }

    public function aspekPenilaian()
    {
        return $this->belongsTo(AspekPenilaian::class);
    }
}
