<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            // tambah kolom user_id
            $table->unsignedBigInteger('user_id')->nullable()->after('id');

            // tambah kolom kode_guru
            $table->string('kode_guru', 50)->nullable()->after('user_id');

            // jika ingin relasi ke users (opsional tapi disarankan)
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'kode_guru']);
        });
    }
};
