<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropMinimalMaksimalPesertaFromLombasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lombas', function (Blueprint $table) {
            $table->dropColumn(['minimal_peserta', 'maksimal_peserta']);
        });
    }

    public function down()
    {
        Schema::table('lombas', function (Blueprint $table) {
            $table->integer('minimal_peserta')->after('jenis')->default(1);
            $table->integer('maksimal_peserta')->after('minimal_peserta')->default(1);
        });
    }
}
