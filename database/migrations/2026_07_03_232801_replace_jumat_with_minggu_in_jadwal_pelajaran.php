<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReplaceJumatWithMingguInJadwalPelajaran extends Migration
{
    public function up()
    {
        // Tambah Minggu ke ENUM (Jumat masih ada)
        DB::statement("ALTER TABLE jadwal_pelajaran MODIFY COLUMN hari ENUM('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL");

        // Ubah data Jumat -> Minggu
        DB::table('jadwal_pelajaran')
            ->where('hari', 'Jumat')
            ->update(['hari' => 'Minggu']);

        // Hapus Jumat dari ENUM
        DB::statement("ALTER TABLE jadwal_pelajaran MODIFY COLUMN hari ENUM('Senin','Selasa','Rabu','Kamis','Sabtu','Minggu') NOT NULL");
    }

    public function down()
    {
        // Tambah Jumat ke ENUM (Minggu masih ada)
        DB::statement("ALTER TABLE jadwal_pelajaran MODIFY COLUMN hari ENUM('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL");

        // Ubah data Minggu -> Jumat
        DB::table('jadwal_pelajaran')
            ->where('hari', 'Minggu')
            ->update(['hari' => 'Jumat']);

        // Hapus Minggu dari ENUM
        DB::statement("ALTER TABLE jadwal_pelajaran MODIFY COLUMN hari ENUM('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL");
    }
}
