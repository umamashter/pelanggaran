<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraintToAbsensiDetailsTable extends Migration
{
    public function up()
    {
        Schema::table('absensi_details', function (Blueprint $table) {
            $table->unique(['absensi_id', 'student_id']);
        });
    }

    public function down()
    {
        Schema::table('absensi_details', function (Blueprint $table) {
            $table->dropIndex('absensi_details_absensi_id_student_id_unique');
        });
    }
}
