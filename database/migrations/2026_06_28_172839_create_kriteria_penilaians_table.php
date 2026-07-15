<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKriteriaPenilaiansTable extends Migration
{
    public function up()
    {
        Schema::create('kriteria_penilaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lomba_id')
                ->constrained('lombas')
                ->cascadeOnDelete();
            $table->string('nama');
            $table->decimal('bobot', 5, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kriteria_penilaians');
    }
}
