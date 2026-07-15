<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lombas', function (Blueprint $table) {

            $table->unsignedTinyInteger('kelas_min')
                ->nullable()
                ->after('jenis_lomba');

            $table->unsignedTinyInteger('kelas_max')
                ->nullable()
                ->after('kelas_min');
        });
    }

    public function down(): void
    {
        Schema::table('lombas', function (Blueprint $table) {

            $table->dropColumn([
                'kelas_min',
                'kelas_max'
            ]);
        });
    }
};
