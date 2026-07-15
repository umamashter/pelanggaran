<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <title>Leger Nilai Siswa</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid black;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header h2,
        .header h3,
        .header p {
            margin: 2px;
        }

        .info {
            margin-bottom: 15px;
        }

        .info table {
            width: 100%;
        }

        .info td {
            border: none;
            padding: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th {
            background: #efefef;
        }

        th,
        td {
            padding: 5px;
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .summary {
            margin-top: 20px;
        }

        .summary table {
            width: 50%;
        }

        .footer {
            margin-top: 50px;
            width: 100%;
        }

        .ttd-kiri {
            width: 45%;
            float: left;
            text-align: center;
        }

        .ttd-kanan {
            width: 45%;
            float: right;
            text-align: center;
        }
    </style>

</head>

<body>

    @php

    $kkm = 75;

    $jumlahSiswa = $penilaian->details->count();

    $rataRata = $penilaian->details->avg('nilai_akhir');

    $tertinggi = $penilaian->details->max('nilai_akhir');

    $terendah = $penilaian->details->min('nilai_akhir');

    $tuntas = $penilaian->details
    ->filter(fn($item) => $item->nilai_akhir >= $kkm)
    ->count();

    $tidakTuntas = $jumlahSiswa - $tuntas;

    @endphp

    <div class="header">

        <h2>MADRASAH IBTIDAIYAH NURUL ULUM</h2>

        <h3>LEGER NILAI SISWA</h3>

        <p>Tahun Pelajaran 2025 / 2026</p>

    </div>

    <div class="info">

        <table>

            <tr>
                <td width="150">Kelas</td>
                <td width="10">:</td>
                <td>{{ $penilaian->jadwal->kelas->nama_kelas }}</td>
            </tr>

            <tr>
                <td>Mata Pelajaran</td>
                <td>:</td>
                <td>{{ $penilaian->jadwal->mapel->nama_mapel }}</td>
            </tr>

            <tr>
                <td>Guru Pengampu</td>
                <td>:</td>
                <td>{{ $penilaian->jadwal->guru->nama }}</td>
            </tr>

        </table>

    </div>

    <table>

        <thead>

            <tr>

                <th>No</th>

                <th>Nama Siswa</th>

                <th>Tugas</th>

                <th>UH</th>

                <th>PTS</th>

                <th>PAS</th>

                <th>Nilai Akhir</th>

                <th>Predikat</th>

                <th>Status</th>

            </tr>

        </thead>

        <tbody>

            @foreach($penilaian->details as $detail)

            @php

            $akhir = $detail->nilai_akhir;

            if($akhir >= 90){
            $predikat = 'A';
            } elseif($akhir >= 80){
            $predikat = 'B';
            } elseif($akhir >= 70){
            $predikat = 'C';
            } else {
            $predikat = 'D';
            }

            @endphp

            <tr>

                <td>
                    {{ $loop->iteration }}
                </td>

                <td class="text-left">
                    {{ $detail->student->nama }}
                </td>

                <td>
                    {{ $detail->tugas }}
                </td>

                <td>
                    {{ $detail->uh }}
                </td>

                <td>
                    {{ $detail->pts }}
                </td>

                <td>
                    {{ $detail->pas }}
                </td>

                <td>
                    {{ number_format($akhir,2) }}
                </td>

                <td>
                    {{ $predikat }}
                </td>

                <td>

                    @if($akhir >= $kkm)

                    Tuntas

                    @else

                    Tidak Tuntas

                    @endif

                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

    <div class="summary">

        <h4>Statistik Nilai Kelas</h4>

        <table>

            <tr>

                <td>Jumlah Siswa</td>
                <td>{{ $jumlahSiswa }}</td>

            </tr>

            <tr>

                <td>KKM</td>
                <td>{{ $kkm }}</td>

            </tr>

            <tr>

                <td>Rata-rata Kelas</td>
                <td>{{ number_format($rataRata,2) }}</td>

            </tr>

            <tr>

                <td>Nilai Tertinggi</td>
                <td>{{ number_format($tertinggi,2) }}</td>

            </tr>

            <tr>

                <td>Nilai Terendah</td>
                <td>{{ number_format($terendah,2) }}</td>

            </tr>

            <tr>

                <td>Tuntas</td>
                <td>{{ $tuntas }}</td>

            </tr>

            <tr>

                <td>Tidak Tuntas</td>
                <td>{{ $tidakTuntas }}</td>

            </tr>

        </table>

    </div>

    <div class="footer">

        <div class="ttd-kiri">

            Mengetahui,

            <br>

            Kepala Madrasah

            <br><br><br><br>

            <strong>
                ____________________
            </strong>

        </div>

        <div class="ttd-kanan">

            {{ date('d F Y') }}

            <br>

            Guru Mata Pelajaran

            <br><br><br><br>

            <strong>
                {{ $penilaian->jadwal->guru->nama }}
            </strong>

        </div>

    </div>

</body>

</html>