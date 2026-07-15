<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveSemesterColumnFromStudentKelas extends Migration
{
    public function up()
    {
        Schema::table('student_kelas', function (Blueprint $table) {
            if (Schema::hasColumn('student_kelas', 'semester')) {
                $table->dropColumn('semester');
            }
        });
    }

    public function down()
    {
        Schema::table('student_kelas', function (Blueprint $table) {
            $table->string('semester', 10)->nullable()->after('tahun_ajaran_id');
        });
    }
}
