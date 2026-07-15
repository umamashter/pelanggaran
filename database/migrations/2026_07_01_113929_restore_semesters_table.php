<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RestoreSemestersTable extends Migration
{
    public function up()
    {
        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->string('nama', 10);
            $table->boolean('aktif')->default(false);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();
        });

        $tahunAjaran = DB::table('tahun_ajaran')->whereNotNull('semester')->get();
        foreach ($tahunAjaran as $ta) {
            DB::table('semesters')->insert([
                'tahun_ajaran_id' => $ta->id,
                'nama' => $ta->semester,
                'aktif' => $ta->status === 'Aktif',
                'tanggal_mulai' => $ta->tanggal_mulai,
                'tanggal_selesai' => $ta->tanggal_selesai,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (!Schema::hasColumn('student_kelas', 'semester_id')) {
            Schema::table('student_kelas', function (Blueprint $table) {
                $table->foreignId('semester_id')->nullable()->constrained('semesters');
            });
        }

        $semesters = DB::table('semesters')->get()->groupBy('tahun_ajaran_id')
            ->map(fn($group) => $group->keyBy('nama'));

        DB::table('student_kelas')->whereNotNull('semester')->orderBy('id')->chunk(100, function ($rows) use ($semesters) {
            foreach ($rows as $sk) {
                $sem = $semesters[$sk->tahun_ajaran_id][$sk->semester] ?? null;
                if ($sem) {
                    DB::table('student_kelas')
                        ->where('id', $sk->id)
                        ->update(['semester_id' => $sem->id]);
                }
            }
        });
    }

    public function down()
    {
        Schema::table('student_kelas', function (Blueprint $table) {
            $table->dropForeign(['semester_id']);
            $table->dropColumn('semester_id');
        });
        Schema::dropIfExists('semesters');
    }
}
