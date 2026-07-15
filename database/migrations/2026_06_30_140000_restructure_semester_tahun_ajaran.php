<?php

use App\Models\Semester;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Buat global Ganjil & Genap (pastikan ada)
        $ganjil = Semester::firstOrCreate(
            ['nama' => 'Ganjil'],
            ['aktif' => false]
        );
        $genap = Semester::firstOrCreate(
            ['nama' => 'Genap'],
            ['aktif' => false]
        );

        // 2. Map semua semester ID ke global
        $map = [];
        foreach (Semester::all() as $s) {
            $map[$s->id] = $s->nama === 'Ganjil' ? $ganjil->id : $genap->id;
        }

        // 3. Update student_kelas pakai global semester ID
        foreach ($map as $oldId => $newId) {
            DB::table('student_kelas')
                ->where('semester_id', $oldId)
                ->update(['semester_id' => $newId]);
        }

        // 4. Tambah kolom semester_id ke tahun_ajaran (nullable dulu)
        Schema::table('tahun_ajaran', function (Blueprint $table) {
            $table->foreignId('semester_id')->nullable()->after('status');
        });

        // 5. Populate tahun_ajaran.semester_id dari semester aktif
        foreach (TahunAjaran::all() as $ta) {
            $aktif = DB::table('semesters')
                ->where('tahun_ajaran_id', $ta->id)
                ->where('aktif', true)
                ->first();
            if ($aktif) {
                $globalId = $map[$aktif->id] ?? $ganjil->id;
                DB::table('tahun_ajaran')
                    ->where('id', $ta->id)
                    ->update(['semester_id' => $globalId]);
            }
        }

        // 6. Drop FK & kolom tahun_ajaran_id dari semesters
        Schema::table('semesters', function (Blueprint $table) {
            $table->dropForeign(['tahun_ajaran_id']);
            $table->dropColumn('tahun_ajaran_id');
        });

        // 7. Hapus duplikat semester (sisakan hanya 2 global)
        Semester::whereNotIn('id', [$ganjil->id, $genap->id])->delete();

        // 8. semester_id WAJIB diisi & tambah FK
        DB::statement('ALTER TABLE tahun_ajaran MODIFY COLUMN semester_id BIGINT UNSIGNED NOT NULL');
        Schema::table('tahun_ajaran', function (Blueprint $table) {
            $table->foreign('semester_id')->references('id')->on('semesters');
        });
    }

    public function down(): void
    {
        // Kembalikan semester_id dari tahun_ajaran
        Schema::table('tahun_ajaran', function (Blueprint $table) {
            $table->dropForeign(['semester_id']);
            $table->dropColumn('semester_id');
        });

        // Kembalikan tahun_ajaran_id ke semesters
        Schema::table('semesters', function (Blueprint $table) {
            $table->foreignId('tahun_ajaran_id')->nullable()->constrained('tahun_ajaran')->cascadeOnDelete();
        });

        // Buat ulang relasi per TA
        foreach (TahunAjaran::all() as $ta) {
            $semesterNama = DB::table('semesters')->find($ta->semester_id)?->nama ?? 'Ganjil';
            Semester::firstOrCreate(
                ['tahun_ajaran_id' => $ta->id, 'nama' => $semesterNama],
                ['aktif' => true]
            );
            $semesterLain = $semesterNama === 'Ganjil' ? 'Genap' : 'Ganjil';
            Semester::firstOrCreate(
                ['tahun_ajaran_id' => $ta->id, 'nama' => $semesterLain],
                ['aktif' => false]
            );
        }
    }
};
