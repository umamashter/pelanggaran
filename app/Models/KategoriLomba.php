<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToHaflah;

class KategoriLomba extends Model
{
    use BelongsToHaflah;

    protected $guarded = ['id'];

    public function lombas()
    {
        return $this->hasMany(Lomba::class);
    }
}
