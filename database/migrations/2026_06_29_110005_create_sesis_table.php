<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSesisTable extends Migration
{
    public function up()
    {
        Schema::create('sesis', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sesis');
    }
}
