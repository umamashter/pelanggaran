<?php

namespace App\Observers;

use App\Models\Semester;
use Illuminate\Support\Facades\DB;

class SemesterObserver
{
    public function saving(Semester $semester)
    {
        if ($semester->aktif) {
            DB::transaction(function () use ($semester) {
                Semester::where('tahun_ajaran_id', $semester->tahun_ajaran_id)
                    ->where('id', '!=', $semester->id)
                    ->update(['aktif' => false]);
            });
        }
    }
}
