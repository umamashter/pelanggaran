<?php

use App\Models\Semester;
use App\Models\TahunAjaran;
use Illuminate\Database\Migrations\Migration;

class PopulateSemestersForExistingTahunAjaran extends Migration
{
    public function up()
    {
        TahunAjaran::each(function ($ta) {
            Semester::updateOrCreate(
                ['tahun_ajaran_id' => $ta->id, 'nama' => 'Ganjil'],
                ['aktif' => true]
            );
            Semester::updateOrCreate(
                ['tahun_ajaran_id' => $ta->id, 'nama' => 'Genap'],
                ['aktif' => false]
            );
        });
    }

    public function down()
    {
        // Not easily reversible — semesters created by this migration are indistinguishable
    }
}
