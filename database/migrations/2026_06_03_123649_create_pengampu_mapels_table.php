<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengampuMapelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengampu_mapels', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('guru_id');
            $table->unsignedBigInteger('mata_pelajaran_id');

            $table->foreign('guru_id')
                ->references('id')
                ->on('gurus')
                ->onDelete('cascade');

            $table->foreign('mata_pelajaran_id')
                ->references('id')
                ->on('mata_pelajaran')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengampu_mapels');
    }
}
