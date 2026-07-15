<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToHaflah;

class SesiLomba extends Model
{
    use BelongsToHaflah;

    protected $guarded = ['id'];

    public function haflatulImtihan()
    {
        return $this->belongsTo(HaflatulImtihan::class, 'haflah_id');
    }

    public function lombas()
    {
        return $this->hasMany(Lomba::class);
    }
}
