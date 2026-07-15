<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddHaflahIdToAllTables extends Migration
{
    public function up()
    {
        // 1. Ubah status haflatul_imtihans
        DB::statement("ALTER TABLE haflatul_imtihans MODIFY status ENUM('Persiapan','Aktif','Selesai') NOT NULL DEFAULT 'Persiapan'");

        $defaultHaflahId = DB::table('haflatul_imtihans')->orderBy('id')->value('id') ?? 1;

        $tables = [
            'kategori_lombas', 'peserta_lombas', 'kelompok_lombas',
            'anggota_kelompoks', 'juri_lombas', 'aspek_penilaians',
            'penilaian_lombas', 'hasil_lombas', 'sesis',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) use ($defaultHaflahId) {
                $table->unsignedBigInteger('haflah_id')->nullable()->after('id');
            });
            DB::table($table)->update(['haflah_id' => $defaultHaflahId]);
            DB::statement("ALTER TABLE {$table} MODIFY haflah_id BIGINT UNSIGNED NOT NULL");
            Schema::table($table, function (Blueprint $table) {
                $table->foreign('haflah_id')->references('id')->on('haflatul_imtihans')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        $tables = [
            'kategori_lombas', 'peserta_lombas', 'kelompok_lombas',
            'anggota_kelompoks', 'juri_lombas', 'aspek_penilaians',
            'penilaian_lombas', 'hasil_lombas', 'sesis',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropForeign(['haflah_id']);
                $t->dropColumn('haflah_id');
            });
        }

        DB::statement("ALTER TABLE haflatul_imtihans MODIFY status ENUM('Persiapan','Berlangsung','Selesai','Dibatalkan') NOT NULL DEFAULT 'Persiapan'");
    }
}
