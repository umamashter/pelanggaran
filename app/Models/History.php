<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $guarded = ['id'];
    protected $with = ['siswa', 'rule'];
    public function rule()
    {
        return $this->belongsTo(Peraturan::class, 'peraturan_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function scopeFilter($q, array $filters)
    {
        $q->when($filters['tanggal'] ?? false, function ($q, $tanggal) {
            return $q->where('tanggal', 'like', '%' . $tanggal . '%');
        });
    }
}