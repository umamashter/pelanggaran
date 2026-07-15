<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusAndKelompokToMataPelajaranTable extends Migration
{
    public function up()
    {
        Schema::table('mata_pelajaran', function (Blueprint $table) {

            $table->string('kelompok')
                ->nullable()
                ->after('nama_mapel');

            $table->enum('status', ['Aktif', 'Nonaktif'])
                ->default('Aktif')
                ->after('kelompok');
        });
    }

    public function down()
    {
        Schema::table('mata_pelajaran', function (Blueprint $table) {

            $table->dropColumn('status');
            $table->dropColumn('kelompok');
        });
    }
}
