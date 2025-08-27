<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pelanggaran - PDF</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }

        .filter-info {
            margin-bottom: 10px;
        }

        .total-row {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .siswa-header {
            background-color: #d9edf7;
            font-weight: bold;
        }

        .warning-row {
            background-color: #f8d7da;
            font-weight: bold;
            color: #721c24;
        }
    </style>
</head>

<body>
    <h3 style="text-align: center;">Laporan Data Pelanggaran</h3>

    {{-- Informasi Filter --}}
    <div class="filter-info">
        <p>
            <strong>Tahun Ajaran:</strong> {{ $tahunAjaran }} <br>
            @if ($bulan)
            <strong>Bulan:</strong> {{ \Carbon\Carbon::parse($bulan . '-01')->translatedFormat('F Y') }} <br>
            @endif
            @if (isset($kelas) && $kelas)
            <strong>Kelas:</strong> {{ $kelas->nama_kelas }} <br>
            @endif
            @if ($nisn)
            <strong>NISN:</strong> {{ $nisn }} <br>
            @endif
        </p>
    </div>

    {{-- Tabel Data --}}
    <table>
        <thead>
            <tr>
                <th>Nama Siswa</th>
                <th>Kelas Saat Pelanggaran</th>
                <th>Jenis Pelanggaran</th>
                <th>Poin</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @php
            $grouped = $histories->groupBy('student_id');
            @endphp

            @forelse ($grouped as $siswaId => $pelanggaranSiswa)
            {{-- Header Nama Siswa --}}
            <tr class="siswa-header">
                <td colspan="5">Siswa: {{ $pelanggaranSiswa->first()->student->nama }}</td>
            </tr>

            @php
            $total = 0;
            $tahap = 1;
            @endphp

            {{-- Detail Pelanggaran --}}
            @foreach ($pelanggaranSiswa as $h)
            @php
            $total += $h->rule->poin;
            @endphp
            <tr>
                <td>{{ $h->student->nama }}</td>
                <td>{{ $h->kelasSnapshot->nama_kelas ?? '-' }}</td>
                <td>{{ $h->rule->nama }}</td>
                <td>{{ $h->rule->poin }}</td>
                <td>{{ \Carbon\Carbon::parse($h->tanggal)->format('d M Y') }}</td>
            </tr>

            {{-- Baris peringatan tiap kelipatan 100 poin --}}
            @if ($total >= $tahap * 100)
            <tr class="warning-row">
                <td colspan="5">⚠️ Tahap {{ $tahap }} - Pemanggilan Orang Tua (Total: {{ $total }} poin)</td>
            </tr>
            @php $tahap++; @endphp
            @endif
            @endforeach

            {{-- Total poin per siswa --}}
            <tr class="total-row">
                <td colspan="3" style="text-align: right;">
                    Total Poin {{ $pelanggaranSiswa->first()->student->nama }}
                </td>
                <td>{{ $pelanggaranSiswa->sum(fn($h) => $h->rule->poin) }}</td>
                <td></td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;">Tidak ada data pelanggaran.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>