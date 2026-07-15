<?php

namespace App\Exports;

use App\Models\Lomba;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class FormPenilaianExport implements FromCollection, WithHeadings, WithTitle
{
    protected $lomba;

    public function __construct(Lomba $lomba)
    {
        $this->lomba = $lomba;
    }

    public function collection()
    {
        $pesertas = $this->lomba->peserta()
            ->with('student.user')
            ->orderBy('id')
            ->get();

        $rows = [];
        $no = 1;
        foreach ($pesertas as $p) {
            $nama = $p->student->user->name ?? $p->student->nama ?? '-';
            $row = [
                $no++,
                $p->student->nisn ?? '-',
                $nama,
            ];
            foreach ($this->lomba->aspekPenilaians as $aspek) {
                $row[] = '';
            }
            $row[] = '';
            $rows[] = $row;
        }

        return collect($rows);
    }

    public function headings(): array
    {
        $headers = ['No', 'NISN', 'Nama Peserta'];
        foreach ($this->lomba->aspekPenilaians as $aspek) {
            $headers[] = $aspek->nama_aspek;
        }
        $headers[] = 'Total';
        return $headers;
    }

    public function title(): string
    {
        return 'Form Penilaian';
    }
}
