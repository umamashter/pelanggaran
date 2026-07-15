<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJamKeToJadwalPelajaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jadwal_pelajaran', function (Blueprint $table) {
            $table->tinyInteger('jam_ke')->after('hari');
        });
    }

    public function down()
    {
        Schema::table('jadwal_pelajaran', function (Blueprint $table) {
            $table->dropColumn('jam_ke');
        });
    }
}
