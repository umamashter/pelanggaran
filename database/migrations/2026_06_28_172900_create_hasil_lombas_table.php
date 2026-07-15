<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHasilLombasTable extends Migration
{
    public function up()
    {
        Schema::create('hasil_lombas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lomba_id')
                ->constrained('lombas')
                ->cascadeOnDelete();
            $table->foreignId('peserta_lomba_id')
                ->constrained('peserta_lombas')
                ->cascadeOnDelete();
            $table->decimal('total_nilai', 8, 2)->nullable();
            $table->integer('peringkat')->nullable();
            $table->string('juara')->nullable();
            $table->timestamps();

            $table->unique(['lomba_id', 'peserta_lomba_id'], 'hasil_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hasil_lombas');
    }
}
