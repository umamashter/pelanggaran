<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus kolom semester_id dari tahun_ajaran (tidak lagi diperlukan
        // karena semester aktif ditentukan oleh flag `aktif` di tabel semesters)
        Schema::table('tahun_ajaran', function (Blueprint $table) {
            $table->dropForeign(['semester_id']);
            $table->dropColumn('semester_id');
        });

        // Tambah kolom tanggal_mulai dan tanggal_selesai ke semesters
        Schema::table('semesters', function (Blueprint $table) {
            $table->date('tanggal_mulai')->nullable()->after('aktif');
            $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
        });
    }

    public function down(): void
    {
        Schema::table('tahun_ajaran', function (Blueprint $table) {
            $table->foreignId('semester_id')->nullable()->after('status');
            $table->foreign('semester_id')->references('id')->on('semesters');
        });

        Schema::table('semesters', function (Blueprint $table) {
            $table->dropColumn(['tanggal_mulai', 'tanggal_selesai']);
        });
    }
};
