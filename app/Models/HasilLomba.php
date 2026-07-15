<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToHaflah;

class HasilLomba extends Model
{
    use BelongsToHaflah;

    protected $guarded = ['id'];

    public function lomba()
    {
        return $this->belongsTo(Lomba::class);
    }

    public function pesertaLomba()
    {
        return $this->belongsTo(PesertaLomba::class);
    }
}
