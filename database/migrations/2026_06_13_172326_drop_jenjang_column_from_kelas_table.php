<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropJenjangColumnFromKelasTable extends Migration
{
    public function up()
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn('jenjang');
        });
    }

    public function down()
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->string('jenjang')->nullable();
        });
    }
}
