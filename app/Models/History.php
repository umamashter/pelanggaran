<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; 

class History extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Otomatis eager loading relasi
    protected $with = ['siswa', 'rule', 'kelasSnapshot'];

    public function rule()
    {
        return $this->belongsTo(Peraturan::class, 'peraturan_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function kelasSnapshot()
    {
        return $this->belongsTo(Kelas::class, 'kelas_saat_pelanggaran');
    }

public function scopeFilter($query, array $filters)
    {
        $query->when($filters['tanggal'] ?? false, function ($q, $tanggal) {
            return $q->where('tanggal', 'like', '%' . $tanggal . '%');
        });

        $query->when($filters['from'] ?? false, function ($q, $from) {
            return $q->where('tanggal', '>=', $from);
        });

        $query->when($filters['to'] ?? false, function ($q, $to) {
            return $q->where('tanggal', '<=', $to);
        });

        $query->when($filters['semester'] ?? false, function ($q, $semester) {
            if ($semester === 'ganjil') {
                return $q->whereBetween(DB::raw('MONTH(tanggal)'), [7, 12]);
            } elseif ($semester === 'genap') {
                return $q->whereBetween(DB::raw('MONTH(tanggal)'), [1, 6]);
            }
        });

        $query->when($filters['tahun_ajaran'] ?? false, function ($q, $tahun) {
            $tahun_awal = substr($tahun, 0, 4);
            $tahun_akhir = substr($tahun, 5);
            return $q->whereYear('tanggal', '>=', $tahun_awal)
                     ->whereYear('tanggal', '<=', $tahun_akhir);
        });
    }
}
