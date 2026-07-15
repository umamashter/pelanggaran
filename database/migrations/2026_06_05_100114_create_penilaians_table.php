<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenilaiansTable extends Migration
{
    public function up()
    {
        Schema::create('penilaians', function (Blueprint $table) {

            $table->id();

            $table->foreignId('jadwal_pelajaran_id')
                ->constrained('jadwal_pelajaran')
                ->onDelete('cascade');

            $table->foreignId('tahun_ajaran_id')
                ->constrained('tahun_ajaran')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penilaians');
    }
}
