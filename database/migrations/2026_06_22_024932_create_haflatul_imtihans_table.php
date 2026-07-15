<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('haflatul_imtihans', function (Blueprint $table) {

            $table->id();

            $table->foreignId('tahun_ajaran_id')
                ->constrained('tahun_ajaran')
                ->cascadeOnDelete();

            $table->string('nama_acara');

            $table->date('tanggal_mulai');

            $table->date('tanggal_selesai');

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
        Schema::dropIfExists('haflatul_imtihans');
    }
};
