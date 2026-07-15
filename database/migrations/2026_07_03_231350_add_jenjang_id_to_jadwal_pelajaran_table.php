<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddJenjangIdToJadwalPelajaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jadwal_pelajaran', function (Blueprint $table) {
            $table->foreignId('jenjang_id')
                ->nullable()
                ->after('kelas_id')
                ->constrained('jenjangs')
                ->cascadeOnDelete();
        });

        // Isi jenjang_id dari relasi kelas untuk data existing
        DB::statement('
            UPDATE jadwal_pelajaran jp
            JOIN kelas k ON k.id = jp.kelas_id
            SET jp.jenjang_id = k.jenjang_id
            WHERE jp.jenjang_id IS NULL
        ');
    }

    public function down()
    {
        Schema::table('jadwal_pelajaran', function (Blueprint $table) {
            $table->dropForeign(['jenjang_id']);
            $table->dropColumn('jenjang_id');
        });
    }
}
