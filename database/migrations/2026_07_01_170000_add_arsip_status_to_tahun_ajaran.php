<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddArsipStatusToTahunAjaran extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE tahun_ajaran MODIFY COLUMN status ENUM('Aktif', 'Tidak Aktif', 'Arsip') NOT NULL DEFAULT 'Tidak Aktif'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE tahun_ajaran MODIFY COLUMN status ENUM('Aktif', 'Tidak Aktif') NOT NULL DEFAULT 'Tidak Aktif'");
    }
}
