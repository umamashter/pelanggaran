<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    // Nama tabel (sesuaikan jika beda)
    protected $table = 'notifikasis';

    // Kolom yang boleh diisi massal
    protected $fillable = [
        'history_id',
        'student_id',
        'message',
        'sent_at',
    ];

    // Jika kamu ingin timestamps created_at dan updated_at aktif, biarkan default true
    public $timestamps = true;

    /**
     * Relasi ke History (riwayat pelanggaran)
     */
    public function history()
    {
        return $this->belongsTo(History::class);
    }

    /**
     * Relasi ke Student (siswa)
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
