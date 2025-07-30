<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenangananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penanganan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->references('id')->on('students')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('tindak_lanjut_id')->references('id')->on('tindak_lanjut')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->boolean('status')->default(0);
            $table->string('berkas', 255)->nullable();
            // $table->string('berita_acara', 255)->nullable();
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
        Schema::dropIfExists('penanganan');
    }
}