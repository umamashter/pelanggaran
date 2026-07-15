<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSesiLombasTable extends Migration
{
    public function up()
    {
        Schema::create('sesi_lombas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('haflah_id')
                ->constrained('haflatul_imtihans')
                ->cascadeOnDelete();
            $table->string('nama');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('urutan')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sesi_lombas');
    }
}
