<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom kelas_saat_pelanggaran ke tabel histories.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('histories', function (Blueprint $table) {
            // Tambah kolom snapshot kelas (nullable)
            $table->unsignedBigInteger('kelas_saat_pelanggaran')->nullable()->after('student_id');

            // Jika mau pakai foreign key (opsional, disarankan untuk integritas data)
            // $table->foreign('kelas_saat_pelanggaran')->references('id')->on('kelas')->onDelete('set null');
        });
    }

    /**
     * Rollback kolom jika migration di-rollback.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('histories', function (Blueprint $table) {
            // Kalau FK aktif, drop FK dulu
            // $table->dropForeign(['kelas_saat_pelanggaran']);

            // Hapus kolom snapshot kelas
            $table->dropColumn('kelas_saat_pelanggaran');
        });
    }
};
