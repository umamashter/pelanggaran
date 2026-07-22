<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKelasTahunAjaranToAbsensisTable extends Migration
{
    public function up()
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->foreignId('kelas_id')
                ->nullable()
                ->constrained('kelas')
                ->cascadeOnDelete()
                ->after('jadwal_pelajaran_id');

            $table->foreignId('tahun_ajaran_id')
                ->nullable()
                ->constrained('tahun_ajaran')
                ->cascadeOnDelete()
                ->after('kelas_id');

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->after('tahun_ajaran_id');

            $table->unique(['kelas_id', 'tahun_ajaran_id', 'tanggal']);
        });
    }

    public function down()
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->dropForeign(['tahun_ajaran_id']);
            $table->dropForeign(['user_id']);
            $table->dropIndex('absensis_kelas_id_tahun_ajaran_id_tanggal_unique');
            $table->dropColumn(['kelas_id', 'tahun_ajaran_id', 'user_id']);
        });
    }
}
