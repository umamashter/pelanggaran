<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lombas', function (Blueprint $table) {

            $table->id();

            $table->foreignId('kategori_lomba_id')
                ->constrained('kategori_lombas')
                ->cascadeOnDelete();

            $table->foreignId('tahun_ajaran_id')
                ->constrained('tahun_ajaran')
                ->cascadeOnDelete();

            $table->string('nama_lomba');

            $table->date('tanggal');

            $table->time('jam_mulai')->nullable();

            $table->time('jam_selesai')->nullable();

            $table->string('lokasi')->nullable();

            $table->enum('status', [
                'Persiapan',
                'Berlangsung',
                'Selesai',
                'Dibatalkan'
            ])->default('Persiapan');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lombas');
    }
};
