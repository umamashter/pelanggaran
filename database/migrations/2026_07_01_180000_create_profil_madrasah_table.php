<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilMadrasahTable extends Migration
{
    public function up()
    {
        Schema::create('profil_madrasah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_madrasah', 100)->default('MIS Nurul Ulum');
            $table->text('visi');
            $table->text('alamat');
            $table->string('telepon', 30);
            $table->string('email', 100);
            $table->text('map_embed')->nullable();
            $table->string('foto', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('misi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profil_madrasah_id')->constrained('profil_madrasah')->cascadeOnDelete();
            $table->text('item');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('misi');
        Schema::dropIfExists('profil_madrasah');
    }
}
