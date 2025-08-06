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
    </style>
</head>
<body>
    <h3>Data Pelanggaran Tahun Ajaran {{ $tahunAjaran }}</h3>

    @if ($bulan)
        <p>Bulan: {{ $bulan }}</p>
    @endif

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
            @foreach ($histories as $history)
                <tr>
                    <td>{{ $history->siswa->nama }}</td>
                    <td>{{ $history->kelasSnapshot->nama_kelas ?? '-' }}</td>
                    <td>{{ $history->rule->nama }}</td>
                    <td>{{ $history->rule->poin }}</td>
                    <td>{{ $history->tanggal }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
