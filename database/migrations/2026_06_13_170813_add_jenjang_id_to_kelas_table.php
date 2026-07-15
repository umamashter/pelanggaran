<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJenjangIdToKelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kelas', function (Blueprint $table) {

            $table->foreignId('jenjang_id')
                ->nullable()
                ->after('id')
                ->constrained('jenjangs')
                ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::table('kelas', function (Blueprint $table) {

            $table->dropForeign(['jenjang_id']);
            $table->dropColumn('jenjang_id');
        });
    }
}
