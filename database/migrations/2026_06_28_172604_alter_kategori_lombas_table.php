<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterKategoriLombasTable extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE kategori_lombas CHANGE nama_kategori nama VARCHAR(255) NOT NULL");

        Schema::table('kategori_lombas', function (Blueprint $table) {
            $table->string('warna')->nullable()->after('nama');
            $table->string('icon')->nullable()->after('warna');
            $table->integer('urutan')->default(0)->after('icon');
            $table->dropColumn('keterangan');
        });
    }

    public function down()
    {
        Schema::table('kategori_lombas', function (Blueprint $table) {
            $table->dropColumn(['warna', 'icon', 'urutan']);
            $table->text('keterangan')->nullable();
        });

        DB::statement("ALTER TABLE kategori_lombas CHANGE nama nama_kategori VARCHAR(255) NOT NULL");
    }
}
