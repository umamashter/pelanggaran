<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToHaflah;

class PesertaLomba extends Model
{
    use BelongsToHaflah;

    protected $guarded = ['id'];

    public function lomba()
    {
        return $this->belongsTo(Lomba::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function kelompokLomba()
    {
        return $this->belongsTo(KelompokLomba::class);
    }

    public function penilaian()
    {
        return $this->hasMany(PenilaianLomba::class);
    }

    public function hasil()
    {
        return $this->hasOne(HasilLomba::class, 'peserta_lomba_id');
    }

    public function isIndividu(): bool
    {
        return !is_null($this->student_id);
    }

    public function isTim(): bool
    {
        return !is_null($this->kelompok_lomba_id);
    }
}
