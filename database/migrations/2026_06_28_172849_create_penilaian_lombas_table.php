<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenilaianLombasTable extends Migration
{
    public function up()
    {
        Schema::create('penilaian_lombas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_lomba_id')
                ->constrained('peserta_lombas')
                ->cascadeOnDelete();
            $table->foreignId('juri_lomba_id')
                ->constrained('juri_lombas')
                ->cascadeOnDelete();
            $table->foreignId('kriteria_penilaian_id')
                ->constrained('kriteria_penilaians')
                ->cascadeOnDelete();
            $table->decimal('nilai', 5, 2);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['peserta_lomba_id', 'juri_lomba_id', 'kriteria_penilaian_id'], 'penilaian_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('penilaian_lombas');
    }
}
