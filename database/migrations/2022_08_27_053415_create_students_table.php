<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('kelas_id')->references('id')->on('kelas')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->char('nisn', 10)->unique();
            $table->string('nama', 255)->nullable();
            $table->string('ttl', 255)->nullable();
            $table->string('jk', 20)->nullable();
            $table->string('agama', 20)->nullable();
            $table->string('alamat', 255)->nullable();
            $table->char('no_telp', 20)->unique()->nullable();
            $table->string('n_ayah', 255)->nullable();
            $table->string('n_ibu', 255)->nullable();
            $table->string('alamat_ortu', 255)->nullable();
            $table->char('no_telp_rumah', 20)->nullable();
            $table->integer('poin')->default('0');;
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
        Schema::dropIfExists('students');
    }
}