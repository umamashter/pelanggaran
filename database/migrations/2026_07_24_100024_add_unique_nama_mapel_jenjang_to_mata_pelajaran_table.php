<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueNamaMapelJenjangToMataPelajaranTable extends Migration
{
    public function up()
    {
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            $table->unique(['nama_mapel', 'jenjang_id'], 'unique_nama_mapel_jenjang');
        });
    }

    public function down()
    {
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            $table->dropIndex('unique_nama_mapel_jenjang');
        });
    }
}
