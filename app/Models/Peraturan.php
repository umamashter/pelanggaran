<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peraturan extends Model
{
    protected $table = 'peraturan';
    protected $guarded = ['id'];

    public function scopeFilter($query, $search)
    {
        $query->when($search ?? false, function ($query, $search) {
            return $query->where('nama', 'like', '%' . $search . '%')
                ->orWhere('poin', 'like', '%' . $search . '%');
        });
    }

    public function jenisPeraturan()
    {
        return $this->belongsTo(JenisPeraturan::class);
    }

    public function siswa()
    {
        return $this->belongsToMany(Student::class, 'histories');
    }

    public function history()
    {
        return $this->hasMany(History::class);
    }
}