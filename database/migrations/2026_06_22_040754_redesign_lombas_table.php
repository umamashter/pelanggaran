<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lombas', function (Blueprint $table) {

            $table->dropForeign(['kategori_lomba_id']);
            $table->dropForeign(['tahun_ajaran_id']);

            $table->dropColumn([
                'kategori_lomba_id',
                'tahun_ajaran_id',
                'tanggal',
                'jam_mulai',
                'jam_selesai',
                'lokasi'
            ]);

            $table->enum('jenis_lomba', [
                'Siang',
                'Malam'
            ])->after('nama_lomba');

            $table->text('deskripsi')
                ->nullable()
                ->after('jenis_lomba');
        });
    }

    public function down(): void
    {
        //
    }
};
