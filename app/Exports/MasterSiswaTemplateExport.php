<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MasterSiswaTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [];
    }

    public function headings(): array
    {
        return [
            'nama',
            'nisn',
            'email',
            'ttl',
            'jk',
            'agama',
            'alamat',
            'no_telp',
            'n_ayah',
            'n_ibu',
            'alamat_ortu',
            'no_telp_rumah',
            'kelas',
        ];
    }
}
