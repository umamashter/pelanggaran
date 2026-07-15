<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLombasTable extends Migration
{
    public function up()
    {
        Schema::table('lombas', function (Blueprint $table) {
            $table->dropColumn(['jenis_lomba', 'kelas_min', 'kelas_max']);
        });

        DB::statement("ALTER TABLE lombas CHANGE nama_lomba nama VARCHAR(255) NOT NULL");

        Schema::table('lombas', function (Blueprint $table) {
            $table->unsignedBigInteger('haflah_id')->nullable()->after('id');
            $table->foreignId('sesi_lomba_id')
                ->after('haflah_id')
                ->nullable()
                ->constrained('sesi_lombas')
                ->nullOnDelete();
            $table->foreignId('kategori_lomba_id')
                ->after('sesi_lomba_id')
                ->nullable()
                ->constrained('kategori_lombas')
                ->nullOnDelete();
            $table->enum('jenis', ['Individu', 'Tim'])
                ->after('nama')
                ->default('Individu');
            $table->integer('minimal_peserta')
                ->after('jenis')
                ->default(1);
            $table->integer('maksimal_peserta')
                ->after('minimal_peserta')
                ->default(1);
            $table->string('lokasi')
                ->after('deskripsi')
                ->nullable();
        });

        DB::table('lombas')->update(['haflah_id' => 1]);

        DB::statement("ALTER TABLE `lombas` MODIFY `haflah_id` BIGINT UNSIGNED NOT NULL");

        Schema::table('lombas', function (Blueprint $table) {
            $table->foreign('haflah_id')->references('id')->on('haflatul_imtihans')->cascadeOnDelete();
        });

        DB::statement("ALTER TABLE `lombas` MODIFY `status` ENUM('Belum Mulai','Berlangsung','Selesai') NOT NULL DEFAULT 'Belum Mulai'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE `lombas` MODIFY `status` ENUM('Persiapan','Berlangsung','Selesai','Dibatalkan') NOT NULL DEFAULT 'Persiapan'");

        Schema::table('lombas', function (Blueprint $table) {
            $table->dropForeign(['haflah_id']);
            $table->dropForeign(['kategori_lomba_id']);
            $table->dropForeign(['sesi_lomba_id']);
            $table->dropColumn(['haflah_id', 'sesi_lomba_id', 'kategori_lomba_id', 'jenis', 'minimal_peserta', 'maksimal_peserta', 'lokasi']);
        });

        DB::statement("ALTER TABLE lombas CHANGE nama nama_lomba VARCHAR(255) NOT NULL");

        Schema::table('lombas', function (Blueprint $table) {
            $table->enum('jenis_lomba', ['Siang', 'Malam'])->after('nama_lomba');
            $table->unsignedTinyInteger('kelas_min')->nullable()->after('jenis_lomba');
            $table->unsignedTinyInteger('kelas_max')->nullable()->after('kelas_min');
        });
    }
}
