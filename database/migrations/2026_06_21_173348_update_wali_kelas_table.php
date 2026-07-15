<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wali_kelas', function (Blueprint $table) {

            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            $table->unsignedBigInteger('guru_id')->nullable()->after('id');
            $table->unsignedBigInteger('tahun_ajaran_id')->nullable()->after('guru_id');

            $table->foreign('guru_id')
                ->references('id')
                ->on('gurus')
                ->onDelete('set null');

            $table->foreign('tahun_ajaran_id')
                ->references('id')
                ->on('tahun_ajaran')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('wali_kelas', function (Blueprint $table) {

            $table->dropForeign(['guru_id']);
            $table->dropForeign(['tahun_ajaran_id']);

            $table->dropColumn(['guru_id', 'tahun_ajaran_id']);

            // optional: kembalikan user_id kalau rollback
            $table->unsignedBigInteger('user_id')->nullable();
        });
    }
};
