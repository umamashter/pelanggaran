<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyTahunAjaranToPengampuMapelsTable extends Migration
{
    public function up()
    {
        Schema::table('pengampu_mapels', function (Blueprint $table) {
            $table->foreign('tahun_ajaran_id')
                ->references('id')
                ->on('tahun_ajaran')
                ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::table('pengampu_mapels', function (Blueprint $table) {
            $table->dropForeign(['tahun_ajaran_id']);
        });
    }
}
