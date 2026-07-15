<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKelasIdToPengampuMapelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengampu_mapels', function (Blueprint $table) {
            $table->foreignId('kelas_id')
                ->after('mata_pelajaran_id')
                ->constrained('kelas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pengampu_mapels', function (Blueprint $table) {
            //
        });
    }
}
