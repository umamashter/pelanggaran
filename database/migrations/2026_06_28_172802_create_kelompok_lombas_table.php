<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKelompokLombasTable extends Migration
{
    public function up()
    {
        Schema::create('kelompok_lombas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lomba_id')
                ->constrained('lombas')
                ->cascadeOnDelete();
            $table->string('nama_kelompok');
            $table->string('asal')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kelompok_lombas');
    }
}
