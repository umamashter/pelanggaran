<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTahunAjaranIdToHistories extends Migration
{
    public function up()
    {
        Schema::table('histories', function (Blueprint $table) {
            $table->unsignedBigInteger('tahun_ajaran_id')->nullable()->after('kelas_saat_pelanggaran');
        });
    }

    public function down()
    {
        Schema::table('histories', function (Blueprint $table) {
            $table->dropColumn('tahun_ajaran_id');
        });
    }
}
