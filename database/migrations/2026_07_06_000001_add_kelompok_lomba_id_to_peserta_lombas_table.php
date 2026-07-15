<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKelompokLombaIdToPesertaLombasTable extends Migration
{
    public function up()
    {
        Schema::table('peserta_lombas', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
        });

        DB::statement('ALTER TABLE peserta_lombas MODIFY student_id BIGINT UNSIGNED NULL');

        Schema::table('peserta_lombas', function (Blueprint $table) {
            $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete();

            $table->foreignId('kelompok_lomba_id')->nullable()->after('student_id')
                ->constrained('kelompok_lombas')
                ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::table('peserta_lombas', function (Blueprint $table) {
            $table->dropForeign(['kelompok_lomba_id']);
            $table->dropColumn('kelompok_lomba_id');
        });

        Schema::table('peserta_lombas', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
        });

        DB::statement('ALTER TABLE peserta_lombas MODIFY student_id BIGINT UNSIGNED NOT NULL');

        Schema::table('peserta_lombas', function (Blueprint $table) {
            $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete();
        });
    }
}
