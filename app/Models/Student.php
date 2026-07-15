<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'poin' => 'integer',
    ];

    /**
     * Scope Filter
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('nama', 'like', '%' . $search . '%')
                ->orWhere('nisn', 'like', '%' . $search . '%')
                ->orWhere('alamat', 'like', '%' . $search . '%');
        });

        $query->when($filters['kelas'] ?? false, function ($query, $kelas) {
            return $query->whereHas('kelasAktif.kelas', function ($query) use ($kelas) {
                $query->where('nama_kelas', $kelas);
            });
        });
    }

    /**
     * Relasi User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi Pelanggaran
     */
    public function rule()
    {
        return $this->belongsToMany(
            Peraturan::class,
            'histories'
        )->withPivot('tanggal', 'created_at');
    }

    public function history()
    {
        return $this->hasMany(History::class);
    }

    public function penanganan()
    {
        return $this->hasMany(Penanganan::class);
    }

    /**
     * Relasi Akademik
     */

    // Kelas aktif siswa
    public function kelasAktif()
    {
        return $this->hasOne(StudentKelas::class)
            ->where('aktif', true);
    }

    // Riwayat kelas siswa
    public function riwayatKelas()
    {
        return $this->hasMany(StudentKelas::class);
    }

    /**
     * Relasi Absensi
     */
    public function absensiDetails()
    {
        return $this->hasMany(AbsensiDetail::class);
    }

    /**
     * Relasi Penilaian
     */
    public function penilaianDetails()
    {
        return $this->hasMany(PenilaianDetail::class);
    }

    /**
     * Route Model Binding
     */
    public function getRouteKeyName()
    {
        return 'nisn';
    }
    public function kelasTahunAjaran($tahunAjaranId)
    {
        return $this->riwayatKelas()
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->first();
    }
}
