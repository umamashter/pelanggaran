<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom baru ke tahun_ajaran
        Schema::table('tahun_ajaran', function (Blueprint $table) {
            $table->string('semester', 10)->nullable()->after('status');
            $table->date('tanggal_mulai')->nullable()->after('semester');
            $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
        });

        // 2. Pindahkan data dari semesters ke tahun_ajaran
        $tahunAjarans = DB::table('tahun_ajaran')->get();
        foreach ($tahunAjarans as $ta) {
            $semesterAktif = DB::table('semesters')
                ->where('tahun_ajaran_id', $ta->id)
                ->where('aktif', true)
                ->first();

            DB::table('tahun_ajaran')
                ->where('id', $ta->id)
                ->update([
                    'semester' => $semesterAktif?->nama ?? 'Ganjil',
                    'tanggal_mulai' => $semesterAktif?->tanggal_mulai ?? null,
                    'tanggal_selesai' => $semesterAktif?->tanggal_selesai ?? null,
                ]);
        }

        // 3. Ubah student_kelas: ganti semester_id (FK) jadi semester (string)
        Schema::table('student_kelas', function (Blueprint $table) {
            $table->dropForeign(['semester_id']);
            $table->dropColumn('semester_id');
        });

        Schema::table('student_kelas', function (Blueprint $table) {
            $table->string('semester', 10)->nullable()->after('tahun_ajaran_id');
        });

        // 4. Isi semester string di student_kelas dari tahun_ajaran induk
        DB::statement('
            UPDATE student_kelas sk
            JOIN tahun_ajaran ta ON ta.id = sk.tahun_ajaran_id
            SET sk.semester = ta.semester
        ');

        // 5. Hapus tabel semesters
        Schema::dropIfExists('semesters');
    }

    public function down(): void
    {
        // Tidak ada rollback — terlalu kompleks
    }
};
