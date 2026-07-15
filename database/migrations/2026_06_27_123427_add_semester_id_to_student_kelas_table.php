<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_kelas', function (Blueprint $table) {

            $table->foreignId('semester_id')
                ->nullable()
                ->after('tahun_ajaran_id')
                ->constrained('semesters')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('student_kelas', function (Blueprint $table) {

            $table->dropForeign(['semester_id']);
            $table->dropColumn('semester_id');
        });
    }
};
