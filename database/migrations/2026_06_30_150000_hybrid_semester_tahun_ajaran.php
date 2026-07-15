<?php

use App\Models\Semester;
use App\Models\StudentKelas;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Simpan mapping old global semester ID → nama
        $globalSemesterNama = [];
        foreach (Semester::all() as $s) {
            $globalSemesterNama[$s->id] = $s->nama;
        }

        // 1. Tambah tahun_ajaran_id ke semesters (nullable)
        Schema::table('semesters', function (Blueprint $table) {
            $table->foreignId('tahun_ajaran_id')
                ->nullable()
                ->after('id')
                ->constrained('tahun_ajaran')
                ->cascadeOnDelete();
        });

        // 2. Buat per-TA semester & mapping
        // map: (tahun_ajaran_id, global_semester_id) → new_semester_id
        $map = [];
        $taSemesterBaru = []; // tahun_ajaran_id → id semester aktif baru

        foreach (TahunAjaran::all() as $ta) {
            $activeGlobalId = $ta->semester_id;
            $activeNama = $globalSemesterNama[$activeGlobalId] ?? 'Ganjil';
            $inactiveNama = $activeNama === 'Ganjil' ? 'Genap' : 'Ganjil';

            // Buat semester aktif
            $active = Semester::create([
                'tahun_ajaran_id' => $ta->id,
                'nama' => $activeNama,
                'aktif' => true,
            ]);

            // Buat semester tidak aktif
            $inactive = Semester::create([
                'tahun_ajaran_id' => $ta->id,
                'nama' => $inactiveNama,
                'aktif' => false,
            ]);

            $map[$ta->id][$activeGlobalId] = $active->id;
            $taSemesterBaru[$ta->id] = $active->id;

            // Cari global inactive ID
            $inactiveGlobalId = null;
            foreach ($globalSemesterNama as $gid => $gnama) {
                if ($gnama === $inactiveNama) {
                    $inactiveGlobalId = $gid;
                    break;
                }
            }
            if ($inactiveGlobalId) {
                $map[$ta->id][$inactiveGlobalId] = $inactive->id;
            }
        }

        // 3. Update student_kelas dengan mapping yang baru
        foreach (StudentKelas::all() as $sk) {
            $taId = $sk->tahun_ajaran_id;
            $oldSemesterId = $sk->semester_id;
            if ($oldSemesterId && isset($map[$taId][$oldSemesterId])) {
                $sk->update(['semester_id' => $map[$taId][$oldSemesterId]]);
            }
        }

        // 4. Update tahun_ajaran.semester_id ke semester baru
        foreach ($taSemesterBaru as $taId => $newSemesterId) {
            DB::table('tahun_ajaran')
                ->where('id', $taId)
                ->update(['semester_id' => $newSemesterId]);
        }

        // 5. Hapus global semesters (yang tidak punya tahun_ajaran_id)
        Semester::whereNull('tahun_ajaran_id')->delete();
    }

    public function down(): void
    {
        // ...
    }
};
