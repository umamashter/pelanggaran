<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiGuru extends Model
{
    protected $table = 'absensi_gurus';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date',
        'jam_masuk' => 'datetime:H:i:s',
        'latitude_masuk' => 'decimal:7',
        'longitude_masuk' => 'decimal:7',
        'jarak_masuk' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
