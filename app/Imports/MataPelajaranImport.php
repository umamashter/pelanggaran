<?php

namespace App\Imports;

use App\Models\Kurikulum;
use App\Models\MataPelajaran;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MataPelajaranImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $kurikulum = Kurikulum::where(
            'nama_kurikulum',
            $row['kurikulum']
        )->first();

        $lastMapel = MataPelajaran::latest('id')->first();

        if ($lastMapel) {

            $lastNumber = (int) substr(
                $lastMapel->kode_mapel,
                3
            );

            $newNumber = $lastNumber + 1;
        } else {

            $newNumber = 1;
        }

        $kodeMapel = 'MAP' .
            str_pad(
                $newNumber,
                3,
                '0',
                STR_PAD_LEFT
            );

        return new MataPelajaran([

            'kode_mapel'   => $kodeMapel,

            'nama_mapel'   => $row['nama_mapel'],

            'kurikulum_id' => $kurikulum?->id,

            'kelompok'     => $row['kelompok'],

            'status'       => $row['status'] ?? 'Aktif',

        ]);
    }
}
