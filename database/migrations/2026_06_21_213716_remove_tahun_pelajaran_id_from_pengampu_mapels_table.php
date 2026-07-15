<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveTahunPelajaranIdFromPengampuMapelsTable extends Migration
{
    public function up()
    {
        Schema::table('pengampu_mapels', function (Blueprint $table) {

            // Hapus foreign key terlebih dahulu
            $table->dropForeign(['tahun_pelajaran_id']);

            // Hapus kolom
            $table->dropColumn('tahun_pelajaran_id');
        });
    }

    public function down()
    {
        Schema::table('pengampu_mapels', function (Blueprint $table) {

            $table->unsignedBigInteger('tahun_pelajaran_id')->nullable();

            $table->foreign('tahun_pelajaran_id')
                ->references('id')
                ->on('tahun_ajaran')
                ->nullOnDelete();
        });
    }
}
