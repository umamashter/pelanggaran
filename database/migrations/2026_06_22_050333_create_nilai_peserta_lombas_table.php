<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai_peserta_lombas', function (Blueprint $table) {

            $table->id();

            $table->foreignId('peserta_lomba_id')
                ->constrained('peserta_lombas')
                ->cascadeOnDelete();

            $table->foreignId('aspek_penilaian_id')
                ->constrained('aspek_penilaians')
                ->cascadeOnDelete();

            $table->decimal('nilai', 5, 2);

            $table->text('catatan')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'peserta_lomba_id',
                'aspek_penilaian_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_peserta_lombas');
    }
};
