<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJuriLombasTable extends Migration
{
    public function up()
    {
        Schema::create('juri_lombas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lomba_id')
                ->constrained('lombas')
                ->cascadeOnDelete();
            $table->foreignId('guru_id')
                ->constrained('gurus')
                ->cascadeOnDelete();
            $table->boolean('ketua')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('juri_lombas');
    }
}
