<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peserta_lombas', function (Blueprint $table) {

            $table->id();

            $table->foreignId('jadwal_lomba_id')
                ->constrained('jadwal_lombas')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->string('nomor_peserta')->nullable();

            $table->enum('status', [
                'Terdaftar',
                'Tampil',
                'Diskualifikasi',
                'Juara'
            ])->default('Terdaftar');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta_lombas');
    }
};
