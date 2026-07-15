<?php

namespace App\Exports;

use App\Models\MataPelajaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MataPelajaranExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return MataPelajaran::with('kurikulum')
            ->get()
            ->map(function ($item) {
                return [
                    'kode_mapel'     => $item->kode_mapel,
                    'nama_mapel'     => $item->nama_mapel,
                    'kurikulum'      => $item->kurikulum->nama_kurikulum ?? '-',
                    'kelompok'       => $item->kelompok,
                    'status'         => $item->status,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Kode Mapel',
            'Nama Mata Pelajaran',
            'Kurikulum',
            'Kelompok',
            'Status'
        ];
    }
}
