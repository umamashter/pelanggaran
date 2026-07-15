<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsensiDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absensi_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('absensi_id')
                ->constrained('absensis')
                ->onDelete('cascade');

            $table->foreignId('student_id')
                ->constrained('students')
                ->onDelete('cascade');

            $table->enum('status', [
                'H',
                'I',
                'S',
                'A'
            ])->default('H');

            $table->text('keterangan')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('absensi_details');
    }
}
