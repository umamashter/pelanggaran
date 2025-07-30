<?php

namespace App\Models;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Poin extends Model
{
    protected $guarded = ['id'];

    public function siswa()
    {
        return $this->belongsTo(Student::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function scopeFilter($query, $search)
    {
        // $query->when($filters['search'] ?? false, function ($query, $search) {
        //     return $query->whereHas('nama', 'like', '%' . $search . '%')
        //         ->orWhere('no_telp', 'like', '%' . $search . '%')
        //         ->orWhere('alamat', 'like', '%' . $search . '%');
        // });
        $query->when($search, function ($query, $search) {
            $query->whereHas('siswa', function ($query) use ($search) {
                $query->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('no_telp', 'like', '%' . $search . '%')
                    ->orWhere('kelas', 'like', '%' . $search . '%')
                    ->orWhere('alamat', 'like', '%' . $search . '%');
            });
        });

        // $query->when($search, function ($query, $kelas) {
        //     $query->whereHas('kelas', function ($query) use ($kelas) {
        //         $query->whereIn('categories.id', $kelas);
        //     })->get();
        // });
        // $query->when($filters['kelas'] ?? false, function ($query, $kelas) {
        //     return $query->whereHas('kelas', function ($query) use ($kelas) {
        //         $query->where('kelas', $kelas);
        //     });
        // });
    }

    public function getRouteKeyName()
    {
        return 'siswa_id';
    }
}