<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenilaianDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('penilaian_details', function (Blueprint $table) {

            $table->id();

            $table->foreignId('penilaian_id')
                ->constrained('penilaians')
                ->onDelete('cascade');

            $table->foreignId('student_id')
                ->constrained('students')
                ->onDelete('cascade');

            $table->decimal('tugas', 5, 2)->default(0);
            $table->decimal('uh', 5, 2)->default(0);
            $table->decimal('pts', 5, 2)->default(0);
            $table->decimal('pas', 5, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penilaian_details');
    }
}
