<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalPelajaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jadwal_pelajaran', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kelas_id')
                ->constrained('kelas')
                ->onDelete('cascade');

            $table->foreignId('guru_id')
                ->constrained('gurus')
                ->onDelete('cascade');

            $table->foreignId('mata_pelajaran_id')
                ->constrained('mata_pelajaran')
                ->onDelete('cascade');

            $table->foreignId('tahun_ajaran_id')
                ->constrained('tahun_ajaran')
                ->onDelete('cascade');

            $table->enum('hari', [
                'Senin',
                'Selasa',
                'Rabu',
                'Kamis',
                'Jumat',
                'Sabtu'
            ]);

            $table->time('jam_mulai');
            $table->time('jam_selesai');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jadwal_pelajaran');
    }
}
