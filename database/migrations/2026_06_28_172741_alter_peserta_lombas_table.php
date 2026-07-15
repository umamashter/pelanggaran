<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPesertaLombasTable extends Migration
{
    public function up()
    {
        Schema::table('peserta_lombas', function (Blueprint $table) {
            $table->dropForeign(['jadwal_lomba_id']);
            $table->dropColumn('jadwal_lomba_id');
        });

        Schema::table('peserta_lombas', function (Blueprint $table) {
            $table->dropColumn('nomor_peserta');
        });

        Schema::table('peserta_lombas', function (Blueprint $table) {
            $table->foreignId('lomba_id')
                ->after('id')
                ->constrained('lombas')
                ->cascadeOnDelete();
            $table->integer('nomor_urut')
                ->after('student_id')
                ->default(0);
        });

        DB::statement("ALTER TABLE `peserta_lombas` MODIFY `status` ENUM('Terdaftar','Hadir','Tidak Hadir','Diskualifikasi') NOT NULL DEFAULT 'Terdaftar'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE `peserta_lombas` MODIFY `status` ENUM('Terdaftar','Tampil','Diskualifikasi','Juara') NOT NULL DEFAULT 'Terdaftar'");

        Schema::table('peserta_lombas', function (Blueprint $table) {
            $table->dropForeign(['lomba_id']);
            $table->dropColumn(['lomba_id', 'nomor_urut']);
        });

        Schema::table('peserta_lombas', function (Blueprint $table) {
            $table->foreignId('jadwal_lomba_id')->constrained('jadwal_lombas')->cascadeOnDelete();
            $table->string('nomor_peserta')->nullable();
        });
    }
}
