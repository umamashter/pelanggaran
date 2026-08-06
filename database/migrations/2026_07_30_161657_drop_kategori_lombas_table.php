<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropKategoriLombasTable extends Migration
{
    public function up()
    {
        Schema::table('lombas', function (Blueprint $table) {
            $table->dropColumn('kategori_lomba_id');
        });

        Schema::dropIfExists('kategori_lombas');
    }

    public function down()
    {
        Schema::create('kategori_lombas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('haflah_id');
            $table->string('nama');
            $table->string('warna')->nullable();
            $table->string('icon')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        Schema::table('lombas', function (Blueprint $table) {
            $table->foreignId('kategori_lomba_id')
                ->nullable()
                ->after('sesi_lomba_id')
                ->constrained('kategori_lombas')
                ->nullOnDelete();
        });
    }
}
