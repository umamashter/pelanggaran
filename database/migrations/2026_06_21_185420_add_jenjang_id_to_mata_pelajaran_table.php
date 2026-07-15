<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mata_pelajaran', function (Blueprint $table) {

            if (!Schema::hasColumn('mata_pelajaran', 'jenjang_id')) {
                $table->unsignedBigInteger('jenjang_id')->nullable()->after('id');
            }

            // foreign key (sesuaikan nama tabel jenjang kamu)
            $table->foreign('jenjang_id')
                ->references('id')
                ->on('jenjangs') // <- pastikan ini sesuai tabel kamu
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('mata_pelajaran', function (Blueprint $table) {

            $table->dropForeign(['jenjang_id']);
            $table->dropColumn('jenjang_id');
        });
    }
};
