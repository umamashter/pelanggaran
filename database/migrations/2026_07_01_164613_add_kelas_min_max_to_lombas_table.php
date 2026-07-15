<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKelasMinMaxToLombasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lombas', function (Blueprint $table) {
            $table->unsignedTinyInteger('kelas_min')->nullable()->after('status');
            $table->unsignedTinyInteger('kelas_max')->nullable()->after('kelas_min');
        });
    }

    public function down()
    {
        Schema::table('lombas', function (Blueprint $table) {
            $table->dropColumn(['kelas_min', 'kelas_max']);
        });
    }
}
