<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Absensi Siswa</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px; }
        .header h2, .header h3, .header p { margin: 2px; }
        .info { margin-bottom: 15px; }
        .info table { width: 100%; }
        .info td { border: none; padding: 3px; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid #000; }
        th { background: #efefef; }
        th, td { padding: 5px; text-align: center; }
        .text-left { text-align: left; }
        .summary { margin-top: 20px; }
        .summary table { width: 50%; }
        .summary td { padding: 5px; }
        .footer { margin-top: 50px; }
        .ttd { width: 250px; float: right; text-align: center; }
    </style>
</head>
<body>
    @php
    $totalHadir = 0;
    $totalIzin = 0;
    $totalSakit = 0;
    $totalAlpha = 0;
    @endphp

    <div class="header">
        <h2>MADRASAH IBTIDAIYAH NURUL ULUM</h2>
        <h3>REKAP ABSENSI SISWA</h3>
        <p>Tahun Pelajaran {{ $tahunAktif->tahun_ajaran }}</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td width="150">Kelas</td>
                <td width="10">:</td>
                <td>{{ $kelas->nama_kelas }}</td>
            </tr>
            <tr>
                <td>Periode</td>
                <td>:</td>
                <td>{{ $periodeLabel }}</td>
            </tr>
            <tr>
                <td>Tanggal Cetak</td>
                <td>:</td>
                <td>{{ now()->translatedFormat('d F Y') }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Hadir</th>
                <th>Izin</th>
                <th>Sakit</th>
                <th>Alpha</th>
                <th>Total</th>
                <th>% Hadir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekapData as $data)
            @php
                $hadir = $data['hadir'];
                $izin = $data['izin'];
                $sakit = $data['sakit'];
                $alpha = $data['alpa'];
                $total = $data['total'];
                $persen = $total > 0 ? ($hadir / $total) * 100 : 0;
                $totalHadir += $hadir;
                $totalIzin += $izin;
                $totalSakit += $sakit;
                $totalAlpha += $alpha;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="text-left">{{ $data['siswa']->nama }}</td>
                <td>{{ $hadir }}</td>
                <td>{{ $izin }}</td>
                <td>{{ $sakit }}</td>
                <td>{{ $alpha }}</td>
                <td>{{ $total }}</td>
                <td>{{ number_format($persen, 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">TOTAL</th>
                <th>{{ $totalHadir }}</th>
                <th>{{ $totalIzin }}</th>
                <th>{{ $totalSakit }}</th>
                <th>{{ $totalAlpha }}</th>
                <th>{{ $totalHadir + $totalIzin + $totalSakit + $totalAlpha }}</th>
                <th>-</th>
            </tr>
        </tfoot>
    </table>

    <div class="summary">
        <h4>Ringkasan Absensi</h4>
        <table>
            <tr><td>Jumlah Siswa</td><td>{{ count($rekapData) }}</td></tr>
            <tr><td>Total Hadir</td><td>{{ $totalHadir }}</td></tr>
            <tr><td>Total Izin</td><td>{{ $totalIzin }}</td></tr>
            <tr><td>Total Sakit</td><td>{{ $totalSakit }}</td></tr>
            <tr><td>Total Alpha</td><td>{{ $totalAlpha }}</td></tr>
        </table>
    </div>

    <div class="footer">
        <div class="ttd">
            <p>{{ now()->translatedFormat('d F Y') }}</p>
            <p>Wali Kelas</p>
            <br><br><br>
            <strong>________________________</strong>
        </div>
    </div>
</body>
</html>
