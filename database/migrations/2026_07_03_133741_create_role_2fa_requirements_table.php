<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRole2faRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_2fa_requirements', function (Blueprint $table) {
            // role cocok tipe users.role (tinyInteger). Nilai 1/2/3/4.
            $table->unsignedTinyInteger('role')->primary();

            $table->boolean('require_2fa')->default(false);

            $table->timestamps();
        });

        // Seed default (TDD §4.4): admin & guru wajib, siswa tidak.
        // BK (role 4) tidak ada route aktif → tidak wajib by default.
        $now = now();
        DB::table('role_2fa_requirements')->insert([
            ['role' => 1, 'require_2fa' => true,  'created_at' => $now, 'updated_at' => $now], // Admin
            ['role' => 2, 'require_2fa' => true,  'created_at' => $now, 'updated_at' => $now], // Guru
            ['role' => 3, 'require_2fa' => false, 'created_at' => $now, 'updated_at' => $now], // Siswa
            ['role' => 4, 'require_2fa' => false, 'created_at' => $now, 'updated_at' => $now], // BK
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_2fa_requirements');
    }
}