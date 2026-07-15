<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aspek_penilaians', function (Blueprint $table) {

            $table->id();

            $table->foreignId('lomba_id')
                ->constrained('lombas')
                ->cascadeOnDelete();

            $table->string('nama_aspek');

            $table->integer('bobot')
                ->default(100);

            $table->text('keterangan')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aspek_penilaians');
    }
};
