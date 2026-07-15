<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnggotaKelompoksTable extends Migration
{
    public function up()
    {
        Schema::create('anggota_kelompoks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelompok_lomba_id')
                ->constrained('kelompok_lombas')
                ->cascadeOnDelete();
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('anggota_kelompoks');
    }
}
