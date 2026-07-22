<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiMadrasah extends Model
{
    protected $table = 'lokasi_madrasah';

    protected $guarded = ['id'];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'aktif' => 'boolean',
    ];

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    public function getLokasiAktif()
    {
        return static::aktif()->first();
    }
}
