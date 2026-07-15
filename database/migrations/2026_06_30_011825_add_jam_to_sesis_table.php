<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJamToSesisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sesis', function (Blueprint $table) {
            $table->time('jam_mulai')->nullable()->after('urutan');
            $table->time('jam_selesai')->nullable()->after('jam_mulai');
        });
    }

    public function down()
    {
        Schema::table('sesis', function (Blueprint $table) {
            $table->dropColumn(['jam_mulai', 'jam_selesai']);
        });
    }
}
