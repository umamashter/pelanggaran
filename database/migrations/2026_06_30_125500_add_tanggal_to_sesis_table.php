<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTanggalToSesisTable extends Migration
{
    public function up()
    {
        Schema::table('sesis', function (Blueprint $table) {
            $table->date('tanggal')->nullable()->after('nama');
        });
    }

    public function down()
    {
        Schema::table('sesis', function (Blueprint $table) {
            $table->dropColumn('tanggal');
        });
    }
}
