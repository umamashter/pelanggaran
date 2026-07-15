<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToHaflah;

class AspekPenilaian extends Model
{
    use BelongsToHaflah;

    protected $guarded = ['id'];

    public function lomba()
    {
        return $this->belongsTo(Lomba::class);
    }
}
