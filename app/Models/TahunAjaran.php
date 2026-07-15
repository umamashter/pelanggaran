<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TahunAjaran extends Model
{
    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'tahun_ajaran',
        'status',
    ];

    protected static function booted()
    {
        static::saved(function ($tahunAjaran) {
            if (!$tahunAjaran->wasRecentlyCreated && !$tahunAjaran->wasChanged('status')) {
                return;
            }
            DB::transaction(function () use ($tahunAjaran) {
                foreach (['Ganjil', 'Genap'] as $nama) {
                    Semester::firstOrCreate([
                        'tahun_ajaran_id' => $tahunAjaran->id,
                        'nama' => $nama,
                    ]);
                }
                if ($tahunAjaran->status === 'Aktif' && !$tahunAjaran->semesterAktif) {
                    $month = (int) now()->format('n');
                    $nama = $month >= 7 ? 'Ganjil' : 'Genap';
                    Semester::where('tahun_ajaran_id', $tahunAjaran->id)
                        ->where('nama', $nama)
                        ->update(['aktif' => true]);
                } elseif ($tahunAjaran->status !== 'Aktif') {
                    $tahunAjaran->semesters()->update(['aktif' => false]);
                }
            });
        });

        static::deleted(function ($tahunAjaran) {
            $tahunAjaran->semesters()->delete();
        });
    }

    public function semesters()
    {
        return $this->hasMany(Semester::class, 'tahun_ajaran_id');
    }

    public function semesterAktif()
    {
        return $this->hasOne(Semester::class, 'tahun_ajaran_id')->where('aktif', true);
    }

    public function siswa()
    {
        return $this->hasMany(StudentKelas::class, 'tahun_ajaran_id');
    }
}
