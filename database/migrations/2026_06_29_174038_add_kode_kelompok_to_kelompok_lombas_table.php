<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKodeKelompokToKelompokLombasTable extends Migration
{
    public function up()
    {
        Schema::table('kelompok_lombas', function (Blueprint $table) {
            $table->string('kode_kelompok')->nullable()->after('nama_kelompok');
        });
    }

    public function down()
    {
        Schema::table('kelompok_lombas', function (Blueprint $table) {
            $table->dropColumn('kode_kelompok');
        });
    }
}
