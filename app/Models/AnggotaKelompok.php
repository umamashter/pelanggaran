<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToHaflah;

class AnggotaKelompok extends Model
{
    use BelongsToHaflah;

    protected $guarded = ['id'];

    public function kelompokLomba()
    {
        return $this->belongsTo(KelompokLomba::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
