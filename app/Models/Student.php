<?php

namespace App\Models;

use App\Models\History;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    protected $guarded = ['id'];
    protected $with = ['kelas', 'rule'];

    protected $casts = [
        'poin' => 'integer'
    ];

    public function scopeFilter($query, array $filters)
    {

        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('nama', 'like', '%' . $search . '%')
                ->orWhere('nisn', 'like', '%' . $search . '%')
                ->orWhere('alamat', 'like', '%' . $search . '%');
        });

        // versi callback
        $query->when($filters['kelas'] ?? false, function ($query, $kelas) {
            return $query->whereHas('kelas', function ($query) use ($kelas) {
                $query->where('nama_kelas', $kelas);
            });
        });
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function rule()
    {
        return $this->belongsToMany(Peraturan::class, 'histories')->withPivot('tanggal', 'created_at
        ');
    }

    public function history()
    {
        return $this->hasMany(History::class);
    }

    public function penanganan()
    {
        return $this->hasMany(Penanganan::class);
    }

    public function getRouteKeyName()
    {
        return 'nisn';
    }
}