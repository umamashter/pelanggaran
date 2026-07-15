<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_kelas', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->boolean('aktif')->default(true)->after('tahun_ajaran_id');
        });
    }

    public function down(): void
    {
        Schema::table('student_kelas', function (Blueprint $table) {
            $table->dropColumn('aktif');
            $table->enum('status', [
                'aktif',
                'naik',
                'tinggal',
                'lulus',
                'keluar'
            ])->default('aktif');
        });
    }
};
