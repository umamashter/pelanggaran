<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RenameMingguToAhadInJadwalPelajaran extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE jadwal_pelajaran MODIFY COLUMN hari ENUM('Senin','Selasa','Rabu','Kamis','Sabtu','Minggu','Ahad') NOT NULL");
        DB::table('jadwal_pelajaran')->where('hari', 'Minggu')->update(['hari' => 'Ahad']);
        DB::statement("ALTER TABLE jadwal_pelajaran MODIFY COLUMN hari ENUM('Senin','Selasa','Rabu','Kamis','Sabtu','Ahad') NOT NULL");
    }

    public function down()
    {
        DB::statement("ALTER TABLE jadwal_pelajaran MODIFY COLUMN hari ENUM('Senin','Selasa','Rabu','Kamis','Sabtu','Ahad','Minggu') NOT NULL");
        DB::table('jadwal_pelajaran')->where('hari', 'Ahad')->update(['hari' => 'Minggu']);
        DB::statement("ALTER TABLE jadwal_pelajaran MODIFY COLUMN hari ENUM('Senin','Selasa','Rabu','Kamis','Sabtu','Minggu') NOT NULL");
    }
}
