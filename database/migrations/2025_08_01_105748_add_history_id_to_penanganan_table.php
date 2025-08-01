<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHistoryIdToPenangananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penanganan', function (Blueprint $table) {
            $table->foreignId('history_id')
                  ->nullable()
                  ->constrained('histories')
                  ->onDelete('cascade')
                  ->after('tindak_lanjut_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penanganan', function (Blueprint $table) {
            $table->dropForeign(['history_id']);
            $table->dropColumn('history_id');
        });
    }
}
