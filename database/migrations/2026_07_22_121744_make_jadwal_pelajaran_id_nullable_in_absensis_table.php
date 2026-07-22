<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MakeJadwalPelajaranIdNullableInAbsensisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropForeign(['jadwal_pelajaran_id']);
        });

        DB::statement('ALTER TABLE absensis MODIFY jadwal_pelajaran_id BIGINT UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE absensis MODIFY jadwal_pelajaran_id BIGINT UNSIGNED NOT NULL');

        Schema::table('absensis', function (Blueprint $table) {
            $table->foreign('jadwal_pelajaran_id')->references('id')->on('jadwal_pelajaran')->onDelete('cascade');
        });
    }
}
