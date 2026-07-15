<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnggotaKelompok;
use App\Models\KelompokLomba;
use App\Models\Lomba;
use App\Models\Student;
use App\Models\HaflatulImtihan;
use App\Models\Scopes\HaflahScope;
use App\Http\Controllers\Traits\ProtectsCompletedHaflah;

class AnggotaKelompokController extends Controller
{
    use ProtectsCompletedHaflah;

    public function index(Request $request)
    {
        $query = KelompokLomba::with(['lomba', 'anggota'])
            ->withCount(['anggota', 'penilaianLombas']);

        if ($request->filled('haflah_id')) {
            $query->withoutGlobalScope(HaflahScope::class)
                  ->where('haflah_id', $request->haflah_id);
        }

        if ($request->filled('lomba_id')) {
            $query->where('lomba_id', $request->lomba_id);
        }

        $kelompoks = $query->orderBy('nama_kelompok')->paginate(20)->withQueryString();

        $lombas = Lomba::where('jenis', 'Tim')->orderBy('nama')->get();
        $haflatuls = HaflatulImtihan::with('tahunAjaran')->orderBy('nama_acara')->get();

        return view('admin.anggota-kelompok.index', compact('kelompoks', 'lombas', 'haflatuls'));
    }

    public function create()
    {
        $sudahAda = AnggotaKelompok::select('kelompok_lomba_id')->distinct()->pluck('kelompok_lomba_id');
        $kelompokLombas = KelompokLomba::with('lomba')
            ->whereNotIn('id', $sudahAda)
            ->orderBy('nama_kelompok')
            ->get();

        return view('admin.anggota-kelompok.create', compact('kelompokLombas'));
    }

    public function getStudentsByKelompok(Request $request, $kelompokLombaId)
    {
        $kelompok = KelompokLomba::with('lomba')->findOrFail($kelompokLombaId);
        $lomba = $kelompok->lomba;

        $kelasMin = $lomba->kelas_min;
        $kelasMax = $lomba->kelas_max;

        $query = Student::with(['user', 'kelasAktif.kelas']);

        $query->whereHas('kelasAktif.kelas', function ($q) use ($kelasMin, $kelasMax) {
            if ($kelasMin !== null && $kelasMin !== '' && $kelasMax !== null && $kelasMax !== '') {
                $q->whereRaw('CAST(tingkat AS UNSIGNED) BETWEEN ? AND ?', [(int) $kelasMin, (int) $kelasMax]);
            }
        });

        $occupiedIds = AnggotaKelompok::where('kelompok_lomba_id', '!=', $kelompokLombaId)
            ->pluck('student_id')
            ->unique()
            ->toArray();

        $keepIds = collect($request->input('selected_ids', []))
            ->filter()
            ->map(function ($id) {
                return (int) $id;
            })
            ->values()
            ->toArray();

        $query->where(function ($q) use ($occupiedIds, $keepIds) {
            if (!empty($occupiedIds)) {
                $q->whereNotIn('id', $occupiedIds);
            }

            if (!empty($keepIds)) {
                $q->orWhereIn('id', $keepIds);
            }
        });

        $students = $query->orderBy('nisn')->get()->map(function ($student) {
            return [
                'id' => $student->id,
                'text' => ($student->user->name ?? $student->nama ?? '-') . ' (' . ($student->nisn ?? '-') . ')',
            ];
        });

        return response()->json([
            'students' => $students,
            'kelas_min' => $kelasMin,
            'kelas_max' => $kelasMax,
        ]);
    }

    public function store(Request $request)
    {
        $kelompok = KelompokLomba::findOrFail($request->kelompok_lomba_id);

        if ($redirect = $this->blockStoreIfHaflahSelesai($kelompok->haflah_id)) {
            return $redirect;
        }

        $request->validate([
            'kelompok_lomba_id' => 'required|exists:kelompok_lombas,id',
            'student_ids' => 'required|array|min:2',
            'student_ids.*' => 'exists:students,id',
        ], [
            'student_ids.min' => 'Anggota kelompok minimal 2 siswa.',
        ]);

        $kelompokLombaId = $request->kelompok_lomba_id;

        $sudahAda = AnggotaKelompok::where('kelompok_lomba_id', $kelompokLombaId)->exists();
        if ($sudahAda) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['kelompok_lomba_id' => 'Kelompok ini sudah memiliki anggota. Gunakan halaman edit untuk menambah anggota.']);
        }

        $allowedIds = $this->allowedStudentIdsForKelompok($kelompokLombaId, $request->student_ids);
        $invalidIds = array_values(array_diff(array_unique($request->student_ids), $allowedIds));
        if (!empty($invalidIds)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['student_ids' => 'Ada siswa yang tidak sesuai dengan aturan kelas lomba.']);
        }

        foreach (array_unique($request->student_ids) as $studentId) {
            // skip if already a member
            $exists = AnggotaKelompok::where('kelompok_lomba_id', $kelompokLombaId)
                ->where('student_id', $studentId)
                ->exists();

            if (!$exists) {
                AnggotaKelompok::create([
                    'kelompok_lomba_id' => $kelompokLombaId,
                    'student_id' => $studentId,
                ]);
            }
        }

        return redirect()->route('anggota-kelompok.index')
            ->with('success', 'Anggota kelompok berhasil ditambahkan.');
    }

    public function show($id)
    {
        $anggotaKelompok = AnggotaKelompok::with(['kelompokLomba.lomba', 'student.user', 'student.kelasAktif.kelas.jenjang'])->findOrFail($id);

        return view('admin.anggota-kelompok.show', compact('anggotaKelompok'));
    }

    public function edit($id)
    {
        $anggotaKelompok = AnggotaKelompok::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($anggotaKelompok->haflah_id)) {
            return $redirect;
        }

        $kelompokLombas = KelompokLomba::with('lomba')->orderBy('nama_kelompok')->get();
        $currentMemberIds = AnggotaKelompok::where('kelompok_lomba_id', $anggotaKelompok->kelompok_lomba_id)
            ->pluck('student_id')
            ->toArray();

        return view('admin.anggota-kelompok.edit', compact('anggotaKelompok', 'kelompokLombas', 'currentMemberIds'));
    }

    public function update(Request $request, $id)
    {
        $kelompokLombaId = $request->kelompok_lomba_id;
        $kelompokLomba = KelompokLomba::findOrFail($kelompokLombaId);

        if ($redirect = $this->blockIfHaflahSelesai($kelompokLomba->haflah_id)) {
            return $redirect;
        }

        $request->validate([
            'kelompok_lomba_id' => 'required|exists:kelompok_lombas,id',
            'student_ids' => 'required|array|min:2',
            'student_ids.*' => 'exists:students,id',
        ], [
            'student_ids.min' => 'Anggota kelompok minimal 2 siswa.',
        ]);

        $allowedIds = $this->allowedStudentIdsForKelompok($kelompokLombaId, $request->student_ids);
        $invalidIds = array_values(array_diff(array_unique($request->student_ids), $allowedIds));
        if (!empty($invalidIds)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['student_ids' => 'Ada siswa yang tidak sesuai dengan aturan kelas lomba.']);
        }

        // get existing member IDs
        $existingIds = AnggotaKelompok::where('kelompok_lomba_id', $kelompokLombaId)
            ->pluck('student_id')
            ->toArray();

        $newIds = array_unique($request->student_ids);
        $occupiedIds = AnggotaKelompok::where('kelompok_lomba_id', '!=', $kelompokLombaId)
            ->pluck('student_id')
            ->unique()
            ->toArray();
        $invalidIds = array_values(array_intersect($newIds, $occupiedIds));

        if (!empty($invalidIds)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['student_ids' => 'Ada siswa yang sudah memiliki kelompok lain. Silakan pilih siswa yang belum masuk kelompok.']);
        }

        // delete removed members
        $toDelete = array_diff($existingIds, $newIds);
        if (!empty($toDelete)) {
            AnggotaKelompok::where('kelompok_lomba_id', $kelompokLombaId)
                ->whereIn('student_id', $toDelete)
                ->delete();
        }

        // add new members
        $toAdd = array_diff($newIds, $existingIds);
        foreach ($toAdd as $studentId) {
            AnggotaKelompok::create([
                'kelompok_lomba_id' => $kelompokLombaId,
                'student_id' => $studentId,
            ]);
        }

        return redirect()->route('anggota-kelompok.index')
            ->with('success', 'Anggota kelompok berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $anggotaKelompok = AnggotaKelompok::findOrFail($id);

        if ($redirect = $this->blockIfHaflahSelesai($anggotaKelompok->haflah_id)) {
            return $redirect;
        }

        $anggotaKelompok->delete();

        return redirect()->route('anggota-kelompok.index')
            ->with('success', 'Anggota kelompok berhasil dihapus.');
    }

    public function hapusSemua($kelompokLombaId)
    {
        $kelompok = KelompokLomba::findOrFail($kelompokLombaId);

        if ($redirect = $this->blockIfHaflahSelesai($kelompok->haflah_id)) {
            return $redirect;
        }

        AnggotaKelompok::where('kelompok_lomba_id', $kelompokLombaId)->delete();

        return redirect()->route('anggota-kelompok.index')
            ->with('success', 'Semua anggota kelompok ' . $kelompok->nama_kelompok . ' berhasil dihapus.');
    }

    private function allowedStudentIdsForKelompok($kelompokLombaId, array $studentIds)
    {
        $kelompok = KelompokLomba::with('lomba')->findOrFail($kelompokLombaId);
        $lomba = $kelompok->lomba;

        $query = Student::whereIn('id', $studentIds)->whereHas('kelasAktif.kelas', function ($q) use ($lomba) {
            if ($lomba && $lomba->kelas_min !== null && $lomba->kelas_max !== null && $lomba->kelas_min !== '' && $lomba->kelas_max !== '') {
                $q->whereRaw('CAST(tingkat AS UNSIGNED) BETWEEN ? AND ?', [(int) $lomba->kelas_min, (int) $lomba->kelas_max]);
            }
        });

        return $query->pluck('id')->map(function ($id) {
            return (int) $id;
        })->all();
    }
}
