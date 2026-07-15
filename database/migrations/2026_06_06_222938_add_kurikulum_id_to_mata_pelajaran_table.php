<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKurikulumIdToMataPelajaranTable extends Migration
{
    public function up()
    {
        Schema::table('mata_pelajaran', function (Blueprint $table) {

            $table->foreignId('kurikulum_id')
                ->nullable()
                ->after('id')
                ->constrained('kurikulums')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('mata_pelajaran', function (Blueprint $table) {

            $table->dropForeign(['kurikulum_id']);
            $table->dropColumn('kurikulum_id');
        });
    }
}
