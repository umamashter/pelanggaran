<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pelanggaran - PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 5px; text-align: left; }
        th { background-color: #eee; }
        .filter-info { margin-bottom: 10px; }
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
            @forelse ($histories as $history)
                <tr>
                    <td>{{ $history->siswa->nama }}</td>
                    <td>{{ $history->kelasSnapshot->nama_kelas ?? '-' }}</td>
                    <td>{{ $history->rule->nama }}</td>
                    <td>{{ $history->rule->poin }}</td>
                    <td>{{ \Carbon\Carbon::parse($history->tanggal)->format('d M Y') }}</td>
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
