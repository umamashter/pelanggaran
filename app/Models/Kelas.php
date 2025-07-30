<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function siswa()
    {
        return $this->hasOne(Student::class);
    }

    public function wali()
    {
        return $this->hasOne(WaliKelas::class);
    }

    public function bk()
    {
        return $this->hasOne(GuruBk::class);
    }
}